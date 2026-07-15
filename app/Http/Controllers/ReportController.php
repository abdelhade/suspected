<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportPerson;
use App\Models\ReportStatus;
use App\Models\ReportType;
use App\Models\AuditLog;
use App\Models\Suspect;
use App\Models\Weapon;
use App\Services\ReportSuggestionService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;


class ReportController extends Controller
{
    /**
     * عرض قائمة المحاضر مع إمكانية البحث والفلترة
     */
    public function index(Request $request): View
    {
        $query = Report::query()->latest();

        // فلترة بنوع المحضر
        if ($request->filled('report_type')) {
            $query->ofType($request->report_type);
        }

        // فلترة بحالة المحضر
        if ($request->filled('current_status')) {
            $query->withStatus($request->current_status);
        }

        // فلترة بالمحافظة
        if ($request->filled('location_governorate')) {
            $query->inGovernorate($request->location_governorate);
        }

        // بحث بالنص في رقم المحضر أو الموضوع أو اسم المحرر
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('report_number', 'like', "%{$search}%")
                  ->orWhere('report_subject', 'like', "%{$search}%")
                  ->orWhere('officer_name', 'like', "%{$search}%");
            });
        }

        $reports = $query->paginate(15)->withQueryString();
        $reportTypes = ReportType::pluck('name');
        $reportStatuses = ReportStatus::pluck('name');

        return view('reports.index', compact('reports', 'reportTypes', 'reportStatuses'));
    }

    /**
     * عرض نموذج إنشاء محضر جديد
     */
    public function create(): View
    {
        $reportTypes = ReportType::pluck('name');
        $reportStatuses = ReportStatus::pluck('name');
        
        return view('reports.create', compact('reportTypes', 'reportStatuses'));
    }

    /**
     * حفظ محضر جديد في قاعدة البيانات
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'report_number'        => 'nullable|string|max:255',
            'report_type'          => 'nullable|string|max:255',
            'open_date_time'       => 'nullable|date',
            'incident_date_time'   => 'nullable|date',
            'location_governorate' => 'nullable|string|max:255',
            'location_details'     => 'nullable|string',
            'officer_name'         => 'nullable|string|max:255',
            'parties_details'      => 'nullable',
            'report_subject'       => 'nullable|string|max:255',
            'statements_details'   => 'nullable',
            'seizures_details'     => 'nullable',
            'current_status'       => 'nullable|string|max:255',
            'prosecution_decision' => 'nullable|string',
            'attachments_paths'    => 'nullable',
        ]);

        foreach (['parties_details', 'statements_details', 'seizures_details', 'attachments_paths'] as $field) {
            if (is_string($validated[$field] ?? null)) {
                $decoded = json_decode($validated[$field], true);
                $validated[$field] = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
            }

            if (!is_array($validated[$field] ?? null)) {
                $validated[$field] = [];
            }
        }

        $report = Report::create($validated);
        $parties = $validated['parties_details'] ?? [];
        $seizures = $validated['seizures_details'] ?? [];

        $this->syncReportPersons($report, $parties);
        $this->syncReportWeapons($report, $seizures);

        AuditLog::record(
            'create',
            'Report',
            $report->id,
            "إنشاء محضر: {$report->report_number}",
            [],
            $report->only(['current_status', 'report_number', 'report_type']),
        );

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'تم إنشاء المحضر بنجاح.');
    }

    /**
     * عرض تفاصيل محضر واحد
     */
    public function show(Report $report): View
    {
        return view('reports.show', compact('report'));
    }

    /**
     * عرض نموذج تعديل محضر
     */
    public function edit(Report $report): View
    {
        $reportTypes = ReportType::pluck('name');
        $reportStatuses = ReportStatus::pluck('name');
        
        return view('reports.edit', compact('report', 'reportTypes', 'reportStatuses'));
    }

    public function suggestions(Request $request, ReportSuggestionService $service): JsonResponse
    {
        $data = $request->validate([
            'crime_type' => 'nullable|string',
            'crime_method' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        return response()->json($service->suggest($data));
    }

    /**
     * تحديث بيانات المحضر في قاعدة البيانات
     */
    public function update(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'report_number'        => 'nullable|string|max:255',
            'report_type'          => 'nullable|string|max:255',
            'open_date_time'       => 'nullable|date',
            'incident_date_time'   => 'nullable|date',
            'location_governorate' => 'nullable|string|max:255',
            'location_details'     => 'nullable|string',
            'officer_name'         => 'nullable|string|max:255',
            'parties_details'      => 'nullable',
            'report_subject'       => 'nullable|string|max:255',
            'statements_details'   => 'nullable',
            'seizures_details'     => 'nullable',
            'current_status'       => 'nullable|string|max:255',
            'prosecution_decision' => 'nullable|string',
            'attachments_paths'    => 'nullable',
        ]);

        foreach (['parties_details', 'statements_details', 'seizures_details', 'attachments_paths'] as $field) {
            if (is_string($validated[$field] ?? null)) {
                $decoded = json_decode($validated[$field], true);
                $validated[$field] = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
            }

            if (!is_array($validated[$field] ?? null)) {
                $validated[$field] = [];
            }
        }

        $oldValues = $report->only(['current_status', 'report_number', 'report_type']);
        $oldReportNumber = $report->report_number;
        $report->update($validated);

        $parties = $validated['parties_details'] ?? [];
        $seizures = $validated['seizures_details'] ?? [];

        $this->syncReportPersons($report, $parties);
        $this->syncReportWeapons($report, $seizures, $oldReportNumber);

        // بعد التحديث نلتقط القيم الجديدة (خصوصًا current_status)
        $newValues = $report->only(['current_status', 'report_number', 'report_type']);

        // تحديدًا لو اتغيرت current_status إلى pending => نسجّل في سجل التدقيق
        if (($oldValues['current_status'] ?? null) !== ($newValues['current_status'] ?? null) && ($newValues['current_status'] ?? null) === 'pending') {
            AuditLog::record(
                'update',
                'Report',
                $report->id,
                "تحويل محضر إلى pending: {$report->report_number}",
                $oldValues,
                $newValues,
            );
        } else {
            // تسجيل حدث تعديل عام
            AuditLog::record(
                'update',
                'Report',
                $report->id,
                "تعديل محضر: {$report->report_number}",
                $oldValues,
                $newValues,
            );
        }

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'تم تحديث المحضر بنجاح.');
    }

    /**
     * حذف المحضر من قاعدة البيانات
     */
    public function destroy(Report $report): RedirectResponse
    {
        $reportNumber = $report->report_number;
        $reportId = $report->id;

        // حذف الأسلحة المرتبطة التي تم إنشاؤها لهذا المحضر
        Weapon::where('related_report_number', $reportNumber)->delete();
        $report->delete();

        AuditLog::record(
            'delete',
            'Report',
            $reportId,
            "حذف محضر: {$reportNumber}",
            ['deleted' => true],
            [],
        );

        return redirect()
            ->route('reports.index')
            ->with('success', 'تم حذف المحضر بنجاح.');
    }

    private function syncReportPersons(Report $report, array $parties): void
    {
        $report->persons()->delete();

        foreach ($parties as $party) {
            if (!is_array($party) || empty($party['role']) && empty($party['full_name'])) {
                continue;
            }

            $personId = null;
            $nationalId = isset($party['national_id']) ? trim((string) $party['national_id']) : null;
            $nationalIdHash = $nationalId ? hash('sha256', $nationalId) : null;

            if ($nationalIdHash) {
                $suspect = Suspect::where('national_id_hash', $nationalIdHash)->first();
                if ($suspect) {
                    $personId = $suspect->id;
                }
            }

            if (!$personId && !empty($party['full_name'])) {
                $suspect = Suspect::where('full_name', trim((string) $party['full_name']))->first();
                if ($suspect) {
                    $personId = $suspect->id;
                }
            }

            $report->persons()->create([
                'person_id' => $personId,
                'role' => $party['role'] ?? null,
                'full_name' => $party['full_name'] ?? null,
                'national_id' => $nationalId,
                'national_id_hash' => $nationalIdHash,
                'nationality' => $party['nationality'] ?? null,
                'age' => isset($party['age']) ? (string) $party['age'] : null,
                'occupation' => $party['occupation'] ?? null,
                'address' => $party['address'] ?? null,
                'phone' => $party['phone'] ?? null,
            ]);
        }
    }

    private function syncReportWeapons(Report $report, array $seizures, ?string $oldReportNumber = null): void
    {
        $report->weapons()->delete();

        if ($oldReportNumber) {
            Weapon::where('related_report_number', $oldReportNumber)->delete();
        }

        foreach ($seizures as $seizure) {
            if (!is_array($seizure) || empty($seizure['name'])) {
                continue;
            }

            $weapon = Weapon::create([
                'weapon_type' => $seizure['name'] ?? null,
                'classification' => 'حرز قضية',
                'current_status' => 'مضبوط',
                'related_report_number' => $report->report_number,
                'weapon_condition' => $seizure['condition'] ?? null,
                'notes' => $seizure['description'] ?? null,
            ]);

            $report->weapons()->create([
                'weapon_id' => $weapon->id,
                'name' => $seizure['name'] ?? null,
                'quantity' => $seizure['quantity'] ?? null,
                'condition' => $seizure['condition'] ?? null,
                'description' => $seizure['description'] ?? null,
                'link_source' => 'manual',
                'confidence_score' => null,
            ]);
        }
    }
}
