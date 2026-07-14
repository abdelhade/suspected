<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * عرض سجل التدقيق مع إمكانية البحث والفلترة
     */
    public function index(Request $request): View
    {
        $query = AuditLog::query()->latest('created_at');

        // فلترة بنوع الحدث
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // فلترة بنوع الكيان
        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }

        // فلترة بالمستخدم
        if ($request->filled('user_name')) {
            $query->where('user_name', 'like', '%' . $request->user_name . '%');
        }

        // فلترة بالتاريخ (من)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // فلترة بالتاريخ (إلى)
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // بحث نصي في الوصف
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(25)->withQueryString();

        // إحصائيات سريعة
        $totalToday  = AuditLog::whereDate('created_at', today())->count();
        $totalEvents = AuditLog::count();

        // توزيع الأحداث اليوم
        $todayByEvent = AuditLog::whereDate('created_at', today())
            ->selectRaw('event, count(*) as cnt')
            ->groupBy('event')
            ->pluck('cnt', 'event');

        $eventOptions = array_keys(AuditLog::EVENT_LABELS);
        $typeOptions  = array_keys(AuditLog::AUDITABLE_LABELS);

        return view('audit-log.index', compact(
            'logs',
            'totalToday',
            'totalEvents',
            'todayByEvent',
            'eventOptions',
            'typeOptions',
        ));
    }

    /**
     * عرض تفاصيل حدث واحد
     */
    public function show(AuditLog $auditLog): View
    {
        return view('audit-log.show', compact('auditLog'));
    }
}
