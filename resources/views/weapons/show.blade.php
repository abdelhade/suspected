@extends('layouts.app')

@section('title', 'تفاصيل السلاح')
@section('page-title', 'تفاصيل السلاح')
@section('page-subtitle', ($weapon->weapon_type ?? 'سلاح') . ' — ' . ($weapon->serial_number ?? 'بدون رقم تسلسلي'))

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
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 p-3"
     style="background:var(--brutal-black);border-bottom:3px solid var(--neon);">
    <div class="d-flex gap-2">
        <a href="{{ route('weapons.edit', $weapon) }}"
           class="btn btn-sm d-flex align-items-center gap-2"
           style="background:var(--brutal-white);color:var(--brutal-black);border:1px solid var(--brutal-white);font-weight:700;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            تعديل البيانات
        </a>
        <form method="POST" action="{{ route('weapons.destroy', $weapon) }}"
              onsubmit="return confirm('هل أنت متأكد من حذف هذا السلاح؟');">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm d-flex align-items-center gap-2"
                    style="background:transparent;color:var(--brutal-white);border:1px solid var(--brutal-white);font-weight:700;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                حذف
            </button>
        </form>
    </div>
    <a href="{{ route('weapons.index') }}" class="text-decoration-none fw-bold"
       style="color:var(--brutal-white);font-size:.875rem;">← عودة للقائمة</a>
</div>

<div class="row g-4">

    {{-- العمود الرئيسي --}}
    <div class="col-12 col-lg-8">

        {{-- البيانات الفنية --}}
        <div class="brutal-card mb-4">
            <div class="card-header">البيانات الفنية للسلاح</div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">نوع السلاح</div>
                        <div class="fw-bold">{{ $weapon->weapon_type ?? '—' }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">العيار</div>
                        <div class="fw-bold">{{ $weapon->caliber ?? '—' }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">الماركة / بلد الصنع</div>
                        <div class="fw-bold">{{ $weapon->brand_make ?? '—' }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">الرقم التسلسلي</div>
                        <div class="fw-bold font-monospace">{{ $weapon->serial_number ?? '—' }}</div>
                    </div>
                    @if($weapon->weapon_condition)
                        <div class="col-12" style="border-top:1px solid rgba(10,10,10,.1);padding-top:.75rem;">
                            <div class="text-xs text-muted-brutal tracking-widest mb-1">الحالة الفنية</div>
                            <div class="fw-bold">{{ $weapon->weapon_condition }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- بيانات الحائز --}}
        <div class="brutal-card mb-4">
            <div class="card-header">بيانات الحائز</div>
            <div class="p-4">
                @if($weapon->holder_info)
                    <p class="fw-bold mb-0" style="white-space:pre-line;line-height:1.8;">{{ $weapon->holder_info }}</p>
                @else
                    <p class="text-muted-brutal mb-0 text-xs tracking-widest">لا توجد بيانات حائز مسجّلة.</p>
                @endif
            </div>
        </div>

        {{-- الملاحظات والذخيرة --}}
        @if($weapon->notes)
            <div class="brutal-card">
                <div class="card-header">ملاحظات وبيانات الذخيرة</div>
                <div class="p-4">
                    <p class="fw-bold mb-0" style="white-space:pre-line;line-height:1.8;">{{ $weapon->notes }}</p>
                </div>
            </div>
        @endif

    </div>

    {{-- العمود الجانبي --}}
    <div class="col-12 col-lg-4">

        {{-- بطاقة الحالة والتصنيف --}}
        <div class="brutal-card mb-4">
            <div class="card-header">الموقف القانوني</div>
            <div class="p-4">

                <div class="text-center mb-4">
                    @php
                        $clsBadge = match($weapon->classification) {
                            'حرز قضية', 'مضبوط بدون ترخيص' => 'badge-dark',
                            default => 'badge-light',
                        };
                    @endphp
                    <span class="badge-brutal {{ $clsBadge }}" style="font-size:.8rem;padding:.4rem .8rem;">
                        {{ $weapon->classification ?? 'غير مصنّف' }}
                    </span>
                </div>

                <div class="section-divider pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-xs text-muted-brutal tracking-widest">الحالة الحالية:</span>
                        @php
                            $statusBadge = match($weapon->current_status) {
                                'في المخزن'         => 'badge-neon',
                                'في المعمل الجنائي' => 'badge-dark',
                                default             => 'badge-light',
                            };
                        @endphp
                        <span class="badge-brutal {{ $statusBadge }}">{{ $weapon->current_status ?? '—' }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-xs text-muted-brutal tracking-widest">رقم المحضر:</span>
                        <span class="fw-bold font-monospace" style="font-size:.875rem;">
                            {{ $weapon->related_report_number ?? '—' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-xs text-muted-brutal tracking-widest">تاريخ الضبط:</span>
                        <span class="fw-bold" style="font-size:.875rem;">
                            {{ $weapon->capture_date_time?->format('Y-m-d H:i') ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- بيانات النظام --}}
        <div class="brutal-card">
            <div class="card-header">بيانات النظام</div>
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-xs text-muted-brutal tracking-widest">رقم السجل:</span>
                    <span class="fw-bold font-monospace">#{{ $weapon->id }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-xs text-muted-brutal tracking-widest">تاريخ الإضافة:</span>
                    <span class="fw-bold" style="font-size:.8rem;">{{ $weapon->created_at?->format('Y-m-d H:i') ?? '—' }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-xs text-muted-brutal tracking-widest">آخر تعديل:</span>
                    <span class="fw-bold" style="font-size:.8rem;">{{ $weapon->updated_at?->format('Y-m-d H:i') ?? '—' }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
