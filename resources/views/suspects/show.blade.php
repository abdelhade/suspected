@extends('layouts.app')

@section('title', 'تفاصيل المسجل')
@section('page-title', 'تفاصيل المسجل')
@section('page-subtitle', 'الاسم: ' . ($suspect->full_name ?? 'بدون اسم'))

@section('content')

{{-- Toolbar --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 p-3"
     style="background:var(--brutal-black);border-bottom:3px solid var(--neon);">
    <div class="d-flex gap-2">
        <a href="{{ route('suspects.edit', $suspect) }}"
           class="btn btn-sm d-flex align-items-center gap-2"
           style="background:var(--brutal-white);color:var(--brutal-black);border:1px solid var(--brutal-white);font-weight:700;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            تعديل البيانات
        </a>
        <form method="POST" action="{{ route('suspects.destroy', $suspect) }}"
              onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل بشكل نهائي؟');">
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
    <a href="{{ route('suspects.index') }}" class="text-decoration-none fw-bold"
       style="color:var(--brutal-white);font-size:.875rem;">← عودة للقائمة</a>
</div>

<div class="row g-4">

    {{-- Main Column --}}
    <div class="col-12 col-lg-8">

        {{-- البيانات الشخصية --}}
        <div class="brutal-card mb-4">
            <div class="card-header">البيانات الشخصية</div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">الاسم الرباعي</div>
                        <div class="fw-bold">{{ $suspect->full_name ?? '-' }}</div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">اسم الشهرة</div>
                        <div class="fw-bold">{{ $suspect->alias_name ?? '-' }}</div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">الرقم القومي</div>
                        <div class="fw-bold font-monospace">{{ $suspect->national_id ?? '-' }}</div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">تاريخ الميلاد</div>
                        <div class="fw-bold">{{ $suspect->birth_date?->format('Y-m-d') ?? '-' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">محل الإقامة</div>
                        <div class="fw-bold">{{ $suspect->current_address ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- المواصفات الجسدية --}}
        <div class="brutal-card">
            <div class="card-header">المواصفات الجسدية</div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-6 col-md-4">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">الطول (سم)</div>
                        <div class="fw-bold">{{ $suspect->height_cm ?? '-' }}</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">بنية الجسم</div>
                        <div class="fw-bold">{{ $suspect->body_build ?? '-' }}</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">لون البشرة</div>
                        <div class="fw-bold">{{ $suspect->skin_color ?? '-' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">العلامات المميزة</div>
                        <div class="fw-bold">{{ $suspect->distinguishing_marks ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Side Column --}}
    <div class="col-12 col-lg-4">
        <div class="brutal-card overflow-hidden">
            <div style="height:240px;background:rgba(10,10,10,.05);display:flex;align-items:center;justify-content:center;border-bottom:1px solid var(--brutal-black);">
                @if($suspect->profile_image_path)
                    <img src="{{ asset('storage/' . $suspect->profile_image_path) }}" alt="الصورة"
                         style="width:100%;height:100%;object-fit:cover;">
                @else
                    <svg width="80" height="80" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity:.25;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                @endif
            </div>
            <div class="p-4">
                <div class="text-center mb-3">
                    <span class="badge-brutal badge-neon" style="font-size:.8rem;">
                        {{ $suspect->current_status ?? 'الحالة غير محددة' }}
                    </span>
                </div>
                <div class="section-divider pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs text-muted-brutal tracking-widest">الفئة:</span>
                        <span class="fw-bold" style="font-size:.875rem;">{{ $suspect->registration_category ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs text-muted-brutal tracking-widest">درجة الخطورة:</span>
                        <span class="fw-bold {{ $suspect->danger_level === 'عالية جداً' ? 'text-danger' : '' }}" style="font-size:.875rem;">
                            {{ $suspect->danger_level ?? '-' }}
                        </span>
                    </div>
                    <div class="mt-2">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">النشاط الإجرامي:</div>
                        <div class="fw-bold" style="font-size:.875rem;">{{ $suspect->criminal_activity ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
