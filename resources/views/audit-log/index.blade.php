@extends('layouts.app')

@section('title', 'سجل التدقيق')
@section('page-title', 'سجل التدقيق')
@section('page-subtitle', 'تتبع جميع العمليات والأحداث في النظام')

@section('content')

{{-- إحصائيات سريعة --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="brutal-card p-4 text-center neon-bg neon-glow">
            <div class="fw-bold" style="font-size:2rem;font-variant-numeric:tabular-nums;">
                {{ number_format($totalToday) }}
            </div>
            <div class="text-xs tracking-widest mt-1">حدث اليوم</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="brutal-card p-4 text-center">
            <div class="fw-bold" style="font-size:2rem;font-variant-numeric:tabular-nums;">
                {{ number_format($totalEvents) }}
            </div>
            <div class="text-xs tracking-widest mt-1">إجمالي السجل</div>
        </div>
    </div>

    @php
        $todayHighlights = [
            'create'  => ['label' => 'إنشاء',  'today'],
            'update'  => ['label' => 'تعديل',  'today'],
            'approve' => ['label' => 'اعتماد', 'today'],
        ];
    @endphp
    <div class="col-6 col-lg-3">
        <div class="brutal-card p-4 text-center">
            <div class="fw-bold" style="font-size:2rem;font-variant-numeric:tabular-nums;">
                {{ number_format(($todayByEvent['create'] ?? 0) + ($todayByEvent['approve'] ?? 0) + ($todayByEvent['promote'] ?? 0)) }}
            </div>
            <div class="text-xs tracking-widest mt-1">إنشاء + اعتماد اليوم</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="brutal-card p-4 text-center">
            <div class="fw-bold" style="font-size:2rem;font-variant-numeric:tabular-nums;">
                {{ number_format($todayByEvent['update'] ?? 0) }}
            </div>
            <div class="text-xs tracking-widest mt-1">تعديل اليوم</div>
        </div>
    </div>
</div>

{{-- فلاتر البحث --}}
<form method="GET" action="{{ route('audit-log.index') }}" class="brutal-card p-4 mb-4">
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <label class="text-xs fw-bold tracking-widest mb-1 d-block">بحث</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control"
                   placeholder="الوصف أو المستخدم أو IP...">
        </div>
        <div class="col-12 col-sm-6 col-lg-2">
            <label class="text-xs fw-bold tracking-widest mb-1 d-block">نوع الحدث</label>
            <select name="event" class="form-select">
                <option value="">— الكل —</option>
                @foreach(\App\Models\AuditLog::EVENT_LABELS as $key => $info)
                    <option value="{{ $key }}" @selected(request('event') === $key)>
                        {{ $info['label'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-6 col-lg-2">
            <label class="text-xs fw-bold tracking-widest mb-1 d-block">الكيان</label>
            <select name="auditable_type" class="form-select">
                <option value="">— الكل —</option>
                @foreach(\App\Models\AuditLog::AUDITABLE_LABELS as $key => $label)
                    <option value="{{ $key }}" @selected(request('auditable_type') === $key)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-6 col-lg-2">
            <label class="text-xs fw-bold tracking-widest mb-1 d-block">من تاريخ</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
        </div>
        <div class="col-12 col-sm-6 col-lg-2">
            <label class="text-xs fw-bold tracking-widest mb-1 d-block">إلى تاريخ</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
        </div>
        <div class="col-12 col-sm-6 col-lg-1 d-flex align-items-end">
            <button type="submit" class="btn btn-brutal-primary w-100">بحث</button>
        </div>
    </div>

    @if(request()->hasAny(['search','event','auditable_type','date_from','date_to']))
        <div class="mt-2">
            <a href="{{ route('audit-log.index') }}" class="text-xs text-muted-brutal text-decoration-underline">
                مسح الفلاتر
            </a>
        </div>
    @endif
</form>

{{-- الجدول --}}
<div class="brutal-card">
    <div class="d-flex align-items-center justify-content-between px-4 py-3"
         style="border-bottom:1px solid var(--brutal-black);">
        <div class="d-flex align-items-center gap-2">
            <div class="fw-bold tracking-widest text-xs">السجلات</div>
            <span class="badge-brutal badge-light">{{ $logs->total() }}</span>
        </div>
        <div class="text-muted-brutal text-xs">
            صفحة {{ $logs->currentPage() }} من {{ $logs->lastPage() }}
        </div>
    </div>

    @if($logs->isEmpty())
        <div class="text-center py-5 text-muted-brutal">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"
                 class="mx-auto mb-3 d-block" style="opacity:.3;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="fw-bold">لا توجد سجلات</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:130px;">الوقت</th>
                        <th>الحدث</th>
                        <th>الوصف</th>
                        <th class="d-none d-sm-table-cell">الكيان</th>
                        <th class="d-none d-md-table-cell">المستخدم</th>
                        <th class="d-none d-lg-table-cell">IP</th>
                        <th style="width:60px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td style="font-size:.7rem;font-family:monospace;white-space:nowrap;">
                                <div>{{ $log->created_at?->format('Y/m/d') }}</div>
                                <div class="text-muted-brutal">{{ $log->created_at?->format('H:i:s') }}</div>
                            </td>
                            <td>
                                <span class="badge-brutal {{ $log->event_badge }}">
                                    {{ $log->event_label }}
                                </span>
                            </td>
                            <td style="max-width:300px;">
                                <span class="text-truncate d-block" style="max-width:280px;"
                                      title="{{ $log->description }}">
                                    {{ $log->description ?? '—' }}
                                </span>
                            </td>
                            <td class="d-none d-sm-table-cell text-muted-brutal" style="font-size:.8rem;">
                                @if($log->auditable_type)
                                    <span class="fw-bold">{{ $log->auditable_label }}</span>
                                    @if($log->auditable_id)
                                        <span class="text-muted-brutal">#{{ $log->auditable_id }}</span>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell" style="font-size:.8rem;">
                                {{ $log->user_name ?? '<نظام>' }}
                            </td>
                            <td class="d-none d-lg-table-cell text-muted-brutal" style="font-size:.7rem;font-family:monospace;">
                                {{ $log->ip_address ?? '—' }}
                            </td>
                            <td>
                                @if($log->old_values || $log->new_values)
                                    <a href="{{ route('audit-log.show', $log) }}"
                                       class="btn btn-brutal-secondary btn-sm px-2"
                                       title="عرض التفاصيل">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div class="d-flex justify-content-center p-4" style="border-top:1px solid var(--brutal-black);">
                {{ $logs->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
