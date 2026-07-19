<?php

namespace App\Http\Controllers;

use App\Exports\SuspectsExport;
use App\Models\Suspect;
use App\Models\LookupOption;
use App\Models\PersonAddress;
use App\Models\Report;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SuspectController extends Controller
{
    private function lookups(): array
    {
        return [
            'registrationCategories' => LookupOption::valuesFor('registration_category'),
            'dangerLevels'           => LookupOption::valuesFor('danger_level'),
            'suspectStatuses'        => LookupOption::valuesFor('suspect_status'),
            'weaponTypes'            => LookupOption::valuesFor('weapon_type'),
            'bodyBuilds'             => LookupOption::valuesFor('body_build'),
            'skinColors'             => LookupOption::valuesFor('skin_color'),
            'governorates'           => $this->getGovernorates(),
            'crimeActivities'        => $this->getCrimeActivities(),
        ];
    }

    private function getGovernorates()
    {
        $addressGovernorates = PersonAddress::query()
            ->whereNotNull('governorate')
            ->where('governorate', '<>', '')
            ->distinct()
            ->orderBy('governorate')
            ->pluck('governorate');

        $reportGovernorates = Report::query()
            ->whereNotNull('location_governorate')
            ->where('location_governorate', '<>', '')
            ->distinct()
            ->orderBy('location_governorate')
            ->pluck('location_governorate');

        return $addressGovernorates->merge($reportGovernorates)->unique()->sort()->values();
    }

    private function getCrimeActivities()
    {
        return Suspect::query()
            ->whereNotNull('criminal_activity')
            ->where('criminal_activity', '<>', '')
            ->distinct()
            ->orderBy('criminal_activity')
            ->pluck('criminal_activity');
    }

    private function getSortColumn(Request $request): string
    {
        $allowed = [
            'full_name' => 'full_name',
            'registration_category' => 'registration_category',
            'criminal_activity' => 'criminal_activity',
            'danger_level' => 'danger_level',
            'current_status' => 'current_status',
            'governorate' => 'governorate',
            'created_at' => 'created_at',
        ];

        return $allowed[$request->query('sort')] ?? 'created_at';
    }

    private function getSortDirection(Request $request): string
    {
        return $request->query('direction') === 'asc' ? 'asc' : 'desc';
    }

    private function buildSearchQuery(Request $request): Builder
    {
        $query = Suspect::query();

        if ($request->filled('registration_category')) {
            $query->where('registration_category', $request->registration_category);
        }

        if ($request->filled('danger_level')) {
            $query->where('danger_level', $request->danger_level);
        }

        if ($request->filled('current_status')) {
            $query->where('current_status', $request->current_status);
        }

        if ($request->filled('criminal_activity')) {
            $query->where('criminal_activity', $request->criminal_activity);
        }

        if ($request->filled('weapon_type')) {
            $query->whereHas('weapons', fn ($q) => $q->where('weapon_type', $request->weapon_type));
        }

        if ($request->filled('governorate')) {
            $query->where(function (Builder $filter) use ($request) {
                $filter->whereHas('addresses', fn ($q) => $q->where('governorate', $request->governorate))
                    ->orWhereHas('reportPersons.report', fn ($q) => $q->where('location_governorate', $request->governorate));
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $matchingReportParties = Report::where('report_number', 'like', "%{$search}%")
                ->orWhere('report_subject', 'like', "%{$search}%")
                ->orWhere('location_governorate', 'like', "%{$search}%")
                ->orWhere('location_details', 'like', "%{$search}%")
                ->pluck('parties_details');

            $matchingIdsOrNames = ['national_ids' => [], 'names' => []];
            foreach ($matchingReportParties as $parties) {
                if (is_array($parties)) {
                    foreach ($parties as $party) {
                        if (!empty($party['national_id'])) {
                            $matchingIdsOrNames['national_ids'][] = $party['national_id'];
                        }
                        if (!empty($party['full_name'])) {
                            $matchingIdsOrNames['names'][] = $party['full_name'];
                        }
                    }
                }
            }

            $query->where(function ($q) use ($search, $matchingIdsOrNames) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('alias_name', 'like', "%{$search}%")
                  ->orWhere('criminal_activity', 'like', "%{$search}%")
                  ->orWhere('current_address', 'like', "%{$search}%");

                if (preg_match('/^\d{14}$/', $search)) {
                    $q->orWhere('national_id_hash', hash('sha256', $search));
                }

                if (!empty($matchingIdsOrNames['national_ids'])) {
                    $q->orWhereIn('national_id_hash', array_map(fn($value) => hash('sha256', $value), $matchingIdsOrNames['national_ids']));
                }
                if (!empty($matchingIdsOrNames['names'])) {
                    $q->orWhereIn('full_name', $matchingIdsOrNames['names']);
                }
            });
        }

        $query->orderBy($this->getSortColumn($request), $this->getSortDirection($request));

        return $query;
    }

    public function index(Request $request): View
    {
        $query = $this->buildSearchQuery($request);
        $suspects = $query->paginate(15)->withQueryString();
        Suspect::attachLinkedReports($suspects->getCollection());

        return view('suspects.index', array_merge(['suspects' => $suspects], $this->lookups()));
    }

    public function export(Request $request)
    {
        $query = $this->buildSearchQuery($request);

        return Excel::download(new SuspectsExport($query), 'suspects-search.xlsx');
    }

    /**
     * عرض نموذج إضافة مسجل جديد
     */
    public function create(): View
    {
        return view('suspects.create', $this->lookups());
    }

    /**
     * حفظ مسجل جديد في قاعدة البيانات
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => 'nullable|string|max:255',
            'alias_name' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'current_address' => 'nullable|string',
            'registration_category' => 'nullable|string|max:255',
            'danger_level' => 'nullable|string|max:255',
            'criminal_activity' => 'nullable|string|max:255',
            'current_status' => 'nullable|string|max:255',
            'distinguishing_marks' => 'nullable|string',
            'height_cm' => 'nullable|integer',
            'body_build' => 'nullable|string|max:255',
            'skin_color' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|max:2048', // 2MB Max
        ]);

        // التعامل مع رفع الصورة
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('suspects', 'public');
            $validated['profile_image_path'] = $path;
        }

        // إزالة الحقل الوهمي الخاص بالصورة من المصفوفة لتجنب خطأ قاعدة البيانات
        unset($validated['profile_image']);

        $suspect = Suspect::create($validated);

        return redirect()
            ->route('suspects.show', $suspect)
            ->with('success', 'تم إضافة المسجل بنجاح.');
    }

    /**
     * عرض تفاصيل المسجل
     */
    public function show(Suspect $suspect): View
    {
        return view('suspects.show', compact('suspect'));
    }

    /**
     * عرض نموذج التعديل
     */
    public function edit(Suspect $suspect): View
    {
        return view('suspects.edit', array_merge(['suspect' => $suspect], $this->lookups()));
    }

    /**
     * تحديث بيانات المسجل
     */
    public function update(Request $request, Suspect $suspect): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => 'nullable|string|max:255',
            'alias_name' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'current_address' => 'nullable|string',
            'registration_category' => 'nullable|string|max:255',
            'danger_level' => 'nullable|string|max:255',
            'criminal_activity' => 'nullable|string|max:255',
            'current_status' => 'nullable|string|max:255',
            'distinguishing_marks' => 'nullable|string',
            'height_cm' => 'nullable|integer',
            'body_build' => 'nullable|string|max:255',
            'skin_color' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            // مسح الصورة القديمة إن وجدت
            if ($suspect->profile_image_path) {
                Storage::disk('public')->delete($suspect->profile_image_path);
            }
            $path = $request->file('profile_image')->store('suspects', 'public');
            $validated['profile_image_path'] = $path;
        }

        unset($validated['profile_image']);

        $suspect->update($validated);

        return redirect()
            ->route('suspects.show', $suspect)
            ->with('success', 'تم تحديث بيانات المسجل بنجاح.');
    }

    /**
     * حذف المسجل
     */
    public function destroy(Suspect $suspect): RedirectResponse
    {
        if ($suspect->profile_image_path) {
            Storage::disk('public')->delete($suspect->profile_image_path);
        }
        
        $suspect->delete();

        return redirect()
            ->route('suspects.index')
            ->with('success', 'تم حذف السجل بنجاح.');
    }
}
