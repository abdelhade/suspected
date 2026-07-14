@extends('layouts.app')

@section('title', 'الأسلحة والمضبوطات')
@section('page-title', 'الأسلحة والمضبوطات النارية')
@section('page-subtitle', 'سجل الأسلحة المضبوطة والمرخصة وعهد الأقسام')

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

{{-- Filters --}}
<div class="brutal-card mb-4 p-3">
    <form method="GET" action="{{ route('weapons.index') }}">
        <div class="row g-2 align-items-end">

            <div class="col-12 col-md">
                <label class="form-label">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="رقم تسلسلي، ماركة، رقم محضر، حائز..."
                       class="form-control">
            </div>

            <div class="col-6 col-md-auto">
                <label class="form-label">نوع السلاح</label>
                <select name="weapon_type" class="form-select">
                    <option value="">كل الأنواع</option>
                    @foreach($weaponTypes as $type)
                        <option value="{{ $type }}" @selected(request('weapon_type') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-6 col-md-auto">
                <label class="form-label">التصنيف</label>
                <select name="classification" class="form-select">
                    <option value="">كل التصنيفات</option>
                    @foreach($classifications as $cls)
                        <option value="{{ $cls }}" @selected(request('classification') === $cls)>{{ $cls }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-6 col-md-auto">
                <label class="form-label">الحالة</label>
                <select name="current_status" class="form-select">
                    <option value="">كل الحالات</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(request('current_status') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-6 col-md-auto d-flex gap-2">
                <button type="submit" class="btn btn-brutal-primary">بحث</button>
                @if(request()->anyFilled(['search', 'weapon_type', 'classification', 'current_status']))
                    <a href="{{ route('weapons.index') }}" class="btn btn-brutal-ghost">✕</a>
                @endif
            </div>

            <div class="col-12 col-md-auto ms-md-auto">
                <a href="{{ route('weapons.create') }}" class="btn btn-brutal-primary w-100">+ إضافة سلاح</a>
            </div>

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
                    <th>نوع السلاح</th>
                    <th>العيار / الماركة</th>
                    <th>الرقم التسلسلي</th>
                    <th class="d-none d-md-table-cell">التصنيف</th>
                    <th class="d-none d-lg-table-cell">رقم المحضر</th>
                    <th class="d-none d-lg-table-cell">تاريخ الضبط</th>
                    <th>الحالة</th>
                    <th class="text-center">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($weapons as $weapon)
                    @php
                        $clsBadge = match($weapon->classification) {
                            'حرز قضية'              => 'badge-dark',
                            'مضبوط بدون ترخيص'     => 'badge-dark',
                            default                 => 'badge-light',
                        };
                        $statusBadge = match($weapon->current_status) {
                            'في المخزن'             => 'badge-neon',
                            'في المعمل الجنائي'     => 'badge-dark',
                            default                 => 'badge-light',
                        };
                    @endphp
                    <tr>
                        <td class="text-muted-brutal" style="font-size:.75rem;">{{ $weapon->id }}</td>
                        <td>
                            <span class="fw-bold">{{ $weapon->weapon_type ?? '—' }}</span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $weapon->caliber ?? '—' }}</div>
                            @if($weapon->brand_make)
                                <div class="text-xs text-muted-brutal">{{ $weapon->brand_make }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="font-monospace fw-bold" style="font-size:.8rem;">
                                {{ $weapon->serial_number ?? '—' }}
                            </span>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <span class="badge-brutal {{ $clsBadge }}">{{ $weapon->classification ?? '—' }}</span>
                        </td>
                        <td class="d-none d-lg-table-cell text-muted-brutal" style="font-size:.8rem;">
                            {{ $weapon->related_report_number ?? '—' }}
                        </td>
                        <td class="d-none d-lg-table-cell text-muted-brutal" style="font-size:.75rem;">
                            {{ $weapon->capture_date_time?->format('Y-m-d') ?? '—' }}
                        </td>
                        <td>
                            <span class="badge-brutal {{ $statusBadge }}">{{ $weapon->current_status ?? '—' }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <a href="{{ route('weapons.show', $weapon) }}" class="btn-icon" title="عرض">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('weapons.edit', $weapon) }}" class="btn-icon btn-icon-dark" title="تعديل">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 112.97 2.97L7.5 19.788l-4 1 1-4 12.362-12.301z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('weapons.destroy', $weapon) }}"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا السلاح؟');" class="d-inline">
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <div class="text-muted-brutal text-xs tracking-widest mb-2">لا توجد أسلحة مسجّلة.</div>
                            <a href="{{ route('weapons.create') }}" class="btn btn-brutal-primary btn-sm px-3">إضافة أول سلاح</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($weapons->hasPages())
        <div class="p-3" style="border-top:1px solid var(--brutal-black);">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <span class="text-muted-brutal" style="font-size:.75rem;">
                    عرض {{ $weapons->firstItem() }}–{{ $weapons->lastItem() }} من {{ $weapons->total() }} سلاح
                </span>
                <div class="d-flex align-items-center gap-1">
                    @if($weapons->onFirstPage())
                        <span class="btn-icon" style="opacity:.3;cursor:not-allowed;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    @else
                        <a href="{{ $weapons->previousPageUrl() }}" class="btn-icon">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endif
                    <span class="fw-bold" style="font-size:.875rem;padding:0 .5rem;">
                        {{ $weapons->currentPage() }} / {{ $weapons->lastPage() }}
                    </span>
                    @if($weapons->hasMorePages())
                        <a href="{{ $weapons->nextPageUrl() }}" class="btn-icon">
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
