<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportType;
use App\Models\ReportStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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
            'parties_details'      => 'nullable|string',
            'report_subject'       => 'nullable|string|max:255',
            'statements_details'   => 'nullable|string',
            'seizures_details'     => 'nullable|string',
            'current_status'       => 'nullable|string|max:255',
            'prosecution_decision' => 'nullable|string',
            'attachments_paths'    => 'nullable|string',
        ]);

        // تحويل الحقول JSON من نص إلى مصفوفة قبل الحفظ
        foreach (['parties_details', 'statements_details', 'seizures_details', 'attachments_paths'] as $field) {
            if (!empty($validated[$field])) {
                $decoded = json_decode($validated[$field], true);
                $validated[$field] = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
            }
        }

        $report = Report::create($validated);

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
            'parties_details'      => 'nullable|string',
            'report_subject'       => 'nullable|string|max:255',
            'statements_details'   => 'nullable|string',
            'seizures_details'     => 'nullable|string',
            'current_status'       => 'nullable|string|max:255',
            'prosecution_decision' => 'nullable|string',
            'attachments_paths'    => 'nullable|string',
        ]);

        // تحويل الحقول JSON من نص إلى مصفوفة قبل الحفظ
        foreach (['parties_details', 'statements_details', 'seizures_details', 'attachments_paths'] as $field) {
            if (!empty($validated[$field])) {
                $decoded = json_decode($validated[$field], true);
                $validated[$field] = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
            }
        }

        $report->update($validated);

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'تم تحديث المحضر بنجاح.');
    }

    /**
     * حذف المحضر من قاعدة البيانات
     */
    public function destroy(Report $report): RedirectResponse
    {
        $report->delete();

        return redirect()
            ->route('reports.index')
            ->with('success', 'تم حذف المحضر بنجاح.');
    }
}
