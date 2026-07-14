<?php

namespace App\Http\Controllers;

use App\Models\Suspect;
use App\Models\LookupOption;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class SuspectController extends Controller
{
    private function lookups(): array
    {
        return [
            'registrationCategories' => LookupOption::valuesFor('registration_category'),
            'dangerLevels'           => LookupOption::valuesFor('danger_level'),
            'suspectStatuses'        => LookupOption::valuesFor('suspect_status'),
            'bodyBuilds'             => LookupOption::valuesFor('body_build'),
            'skinColors'             => LookupOption::valuesFor('skin_color'),
        ];
    }

    /**
     * عرض قائمة المسجلين والمطلوبين مع إمكانية البحث والفلترة
     */
    public function index(Request $request): View
    {
        $query = Suspect::query()->latest();

        // الفلترة
        if ($request->filled('registration_category')) {
            $query->where('registration_category', $request->registration_category);
        }
        if ($request->filled('danger_level')) {
            $query->where('danger_level', $request->danger_level);
        }
        if ($request->filled('current_status')) {
            $query->where('current_status', $request->current_status);
        }

        // البحث بالنص في الاسم، الرقم القومي، اسم الشهرة أو رقم/موضوع/مكان المحضر المرتبط
        if ($request->filled('search')) {
            $search = $request->search;

            // جلب المحاضر التي يطابق رقمها أو موضوعها أو مكانها نص البحث
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
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhere('alias_name', 'like', "%{$search}%");

                if (!empty($matchingIdsOrNames['national_ids'])) {
                    $q->orWhereIn('national_id', $matchingIdsOrNames['national_ids']);
                }
                if (!empty($matchingIdsOrNames['names'])) {
                    $q->orWhereIn('full_name', $matchingIdsOrNames['names']);
                }
            });
        }

        $suspects = $query->paginate(15)->withQueryString();
        Suspect::attachLinkedReports($suspects->getCollection());

        return view('suspects.index', compact('suspects'));
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
