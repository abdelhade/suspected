@extends('layouts.app')

@section('title', 'المحاضر')
@section('page-title', 'المحاضر')
@section('page-subtitle', 'قائمة جميع المحاضر المسجّلة في النظام')

@section('content')

@if(session('success'))
    <div class="alert p-3 mb-4 d-flex align-items-center gap-3"
         style="border:1px solid var(--brutal-black);background:var(--neon);">
        <svg width="16" height="16" fill="none" stroke="var(--brutal-black)" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="fw-bold" style="font-size:.875rem;color:var(--brutal-black);">{{ session('success') }}</span>
    </div>
@endif

{{-- Toolbar --}}
<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <a href="{{ route('reports.create') }}" class="btn btn-brutal-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        محضر جديد
    </a>

    <form method="GET" action="{{ route('reports.index') }}" class="d-flex flex-wrap gap-2 align-items-end">
        <div>
            <label class="form-label">بحث</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="رقم أو موضوع أو محرر..."
                   class="form-control" style="width:220px;">
        </div>
        <div>
            <label class="form-label">النوع</label>
            <select name="report_type" class="form-select">
                <option value="">كل الأنواع</option>
                @foreach($reportTypes as $type)
                    <option value="{{ $type }}" @selected(request('report_type') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">الحالة</label>
            <select name="current_status" class="form-select">
                <option value="">كل الحالات</option>
                @foreach($reportStatuses as $status)
                    <option value="{{ $status }}" @selected(request('current_status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-brutal-secondary">تصفية</button>
            @if(request()->hasAny(['search','report_type','current_status']))
                <a href="{{ route('reports.index') }}" class="btn btn-brutal-ghost">مسح</a>
            @endif
        </div>
    </form>
</div>

{{-- Table --}}
<div class="brutal-card overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>رقم المحضر</th>
                    <th>النوع</th>
                    <th class="d-none d-md-table-cell">الموضوع</th>
                    <th class="d-none d-lg-table-cell">المحافظة</th>
                    <th class="d-none d-xl-table-cell">المحرر</th>
                    <th class="d-none d-lg-table-cell">تاريخ الفتح</th>
                    <th>الحالة</th>
                    <th class="text-center">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    @php
                        $statusBadge = match($report->current_status) {
                            'مفتوح'        => 'badge-neon',
                            'محول للنيابة' => 'badge-dark',
                            default        => 'badge-light',
                        };
                    @endphp
                    <tr>
                        <td class="text-muted-brutal" style="font-size:.75rem;">{{ $report->id }}</td>
                        <td><span class="fw-bold">{{ $report->report_number ?? '—' }}</span></td>
                        <td>
                            <span class="badge-brutal badge-light">{{ $report->report_type ?? '—' }}</span>
                        </td>
                        <td class="d-none d-md-table-cell text-muted-brutal">
                            {{ Str::limit($report->report_subject, 40) ?? '—' }}
                        </td>
                        <td class="d-none d-lg-table-cell text-muted-brutal">{{ $report->location_governorate ?? '—' }}</td>
                        <td class="d-none d-xl-table-cell text-muted-brutal">{{ $report->officer_name ?? '—' }}</td>
                        <td class="d-none d-lg-table-cell text-muted-brutal" style="font-size:.75rem;">
                            {{ $report->open_date_time?->format('Y-m-d') ?? '—' }}
                        </td>
                        <td>
                            <span class="badge-brutal {{ $statusBadge }}">{{ $report->current_status ?? '—' }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('reports.show', $report) }}" class="btn-icon" title="عرض">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('reports.edit', $report) }}" class="btn-icon btn-icon-dark" title="تعديل">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 112.97 2.97L7.5 19.788l-4 1 1-4 12.362-12.301z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('reports.destroy', $report) }}"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المحضر؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-danger" title="حذف">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <svg class="mb-2" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity:.3;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div class="text-muted-brutal text-xs tracking-widest mb-2">لا توجد محاضر مطابقة</div>
                            <a href="{{ route('reports.create') }}" class="btn btn-brutal-primary btn-sm px-3">إضافة أول محضر</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($reports->hasPages())
        <div class="p-3" style="border-top:1px solid var(--brutal-black);">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <span class="text-muted-brutal" style="font-size:.75rem;">
                    عرض {{ $reports->firstItem() }}–{{ $reports->lastItem() }} من {{ $reports->total() }} محضر
                </span>
                <div class="d-flex align-items-center gap-1">
                    @if($reports->onFirstPage())
                        <span class="btn-icon" style="opacity:.3;cursor:not-allowed;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    @else
                        <a href="{{ $reports->previousPageUrl() }}" class="btn-icon">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endif
                    <span class="fw-bold" style="font-size:.875rem;padding:0 .5rem;">
                        {{ $reports->currentPage() }} / {{ $reports->lastPage() }}
                    </span>
                    @if($reports->hasMorePages())
                        <a href="{{ $reports->nextPageUrl() }}" class="btn-icon">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @else
                        <span class="btn-icon" style="opacity:.3;cursor:not-allowed;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@endsection
