<?php

namespace App\Http\Controllers;

use App\Models\Suspect;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * بانتظار الاعتماد
 * ==================
 * يعرض المسجّلين الذين تصنيفهم "بانتظار الاعتماد" ويتيح اعتمادهم أو رفضهم.
 *
 * المنطق المؤقت (حتى يُضاف نظام الصلاحيات والـ approval workflow الكامل):
 *  - أي مسجّل current_status = 'pending' يظهر هنا
 *  - الاعتماد يغيّر الحالة إلى 'active'
 *  - الرفض يغيّر الحالة إلى 'rejected'
 */
class PendingApprovalsController extends Controller
{
    /**
     * قائمة المسجّلين بانتظار الاعتماد
     */
    public function index(Request $request): View
    {
        $query = Suspect::query()
            ->where('current_status', 'pending')
            ->latest();

        // فلترة بالفئة
        if ($request->filled('registration_category')) {
            $query->where('registration_category', $request->registration_category);
        }

        // بحث بالاسم أو الرقم القومي
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('alias_name', 'like', "%{$search}%");

                if (preg_match('/^\d{14}$/', $search)) {
                    $q->orWhere('national_id_hash', hash('sha256', $search));
                }
            });
        }

        $pending = $query->paginate(20)->withQueryString();
        $total   = Suspect::where('current_status', 'pending')->count();

        // إحصائيات سريعة مقسّمة حسب الفئة
        $byCategory = Suspect::where('current_status', 'pending')
            ->selectRaw('registration_category, count(*) as cnt')
            ->groupBy('registration_category')
            ->pluck('cnt', 'registration_category');

        return view('pending-approvals.index', compact('pending', 'total', 'byCategory'));
    }

    /**
     * اعتماد مسجّل
     */
    public function approve(Request $request, Suspect $suspect): RedirectResponse
    {
        if ($suspect->current_status !== 'pending') {
            return back()->with('error', 'هذا السجل ليس في حالة انتظار الاعتماد.');
        }

        $oldStatus = $suspect->current_status;
        $suspect->update(['current_status' => 'active']);

        AuditLog::record(
            event: 'approve',
            auditableType: 'Suspect',
            auditableId: $suspect->id,
            description: "اعتماد المسجّل: {$suspect->full_name}",
            oldValues: ['current_status' => $oldStatus],
            newValues: ['current_status' => 'active'],
        );

        return back()->with('success', "تم اعتماد السجل: {$suspect->full_name}");
    }

    /**
     * رفض مسجّل مع سبب الرفض
     */
    public function reject(Request $request, Suspect $suspect): RedirectResponse
    {
        if ($suspect->current_status !== 'pending') {
            return back()->with('error', 'هذا السجل ليس في حالة انتظار الاعتماد.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $oldStatus = $suspect->current_status;
        $suspect->update(['current_status' => 'rejected']);

        AuditLog::record(
            event: 'update',
            auditableType: 'Suspect',
            auditableId: $suspect->id,
            description: "رفض المسجّل: {$suspect->full_name}" . ($validated['rejection_reason'] ? " — {$validated['rejection_reason']}" : ''),
            oldValues: ['current_status' => $oldStatus],
            newValues: ['current_status' => 'rejected'],
        );

        return back()->with('success', "تم رفض السجل: {$suspect->full_name}");
    }
}
