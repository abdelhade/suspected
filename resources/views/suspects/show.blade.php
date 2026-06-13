@extends('layouts.app')

@section('title', 'تفاصيل المسجل')
@section('page-title', 'تفاصيل المسجل')
@section('page-subtitle', 'الاسم: ' . ($suspect->full_name ?? 'بدون اسم'))

@section('content')

{{-- Toolbar --}}
<div class="mb-5 flex flex-wrap items-center justify-between gap-4 border-b-4 border-neon bg-brutal-black px-5 py-4">
    <div class="flex gap-3">
        <form method="POST" action="{{ route('suspects.destroy', $suspect) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل بشكل نهائي؟');">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex items-center gap-2 border border-brutal-white px-4 py-2 text-sm font-bold text-brutal-white hover:bg-red-600 hover:border-red-600 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                حذف
            </button>
        </form>
        <a href="{{ route('suspects.edit', $suspect) }}" class="flex items-center gap-2 bg-brutal-white px-4 py-2 text-sm font-bold text-brutal-black hover:bg-neon transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            تعديل البيانات
        </a>
    </div>
    <a href="{{ route('suspects.index') }}" class="text-sm font-bold tracking-wide text-brutal-white hover:text-neon">← عودة للقائمة</a>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- Main Column --}}
    <div class="flex flex-col gap-6 lg:col-span-2">
        
        {{-- التفاصيل الشخصية --}}
        <div class="brutal-card">
            <div class="border-b border-brutal-black bg-brutal-black px-5 py-3 text-sm font-bold uppercase tracking-widest text-neon">
                البيانات الشخصية
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x sm:divide-x-reverse divide-brutal-black/20">
                <div class="p-4 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-brutal-smoke/50 uppercase">الاسم الرباعي</p>
                        <p class="text-sm font-black">{{ $suspect->full_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-brutal-smoke/50 uppercase">اسم الشهرة</p>
                        <p class="text-sm font-black">{{ $suspect->alias_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-brutal-smoke/50 uppercase">الرقم القومي</p>
                        <p class="text-sm font-black font-mono">{{ $suspect->national_id ?? '-' }}</p>
                    </div>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-brutal-smoke/50 uppercase">تاريخ الميلاد</p>
                        <p class="text-sm font-black">{{ $suspect->birth_date?->format('Y-m-d') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-brutal-smoke/50 uppercase">محل الإقامة</p>
                        <p class="text-sm font-black">{{ $suspect->current_address ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- المواصفات --}}
        <div class="brutal-card">
            <div class="border-b border-brutal-black bg-brutal-black px-5 py-3 text-sm font-bold uppercase tracking-widest text-neon">
                المواصفات الجسدية
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs font-bold text-brutal-smoke/50 uppercase">الطول (سم)</p>
                    <p class="text-sm font-black">{{ $suspect->height_cm ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-brutal-smoke/50 uppercase">بنية الجسم</p>
                    <p class="text-sm font-black">{{ $suspect->body_build ?? '-' }}</p>
                </div>
                <div class="sm:col-span-3">
                    <p class="text-xs font-bold text-brutal-smoke/50 uppercase">العلامات المميزة</p>
                    <p class="text-sm font-black">{{ $suspect->distinguishing_marks ?? '-' }}</p>
                </div>
            </div>
        </div>

    </div>

    {{-- Side Column --}}
    <div class="flex flex-col gap-6">

        {{-- الصورة والتصنيف --}}
        <div class="brutal-card flex flex-col overflow-hidden">
            <div class="h-64 border-b border-brutal-black bg-brutal-black/5 flex items-center justify-center">
                @if($suspect->profile_image_path)
                    <img src="{{ asset('storage/' . $suspect->profile_image_path) }}" alt="الصورة" class="h-full w-full object-cover">
                @else
                    <svg class="h-24 w-24 text-brutal-smoke/30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                @endif
            </div>
            
            <div class="p-4 space-y-4">
                <div class="text-center">
                    <span class="inline-block bg-neon px-3 py-1 text-sm font-black uppercase tracking-widest text-brutal-black border border-brutal-black">
                        {{ $suspect->current_status ?? 'الحالة غير محددة' }}
                    </span>
                </div>
                
                <div class="space-y-3 pt-2 border-t border-brutal-black/20">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-brutal-smoke/50 uppercase">الفئة:</span>
                        <span class="text-sm font-black">{{ $suspect->registration_category ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-brutal-smoke/50 uppercase">درجة الخطورة:</span>
                        <span class="text-sm font-black {{ $suspect->danger_level === 'عالية جداً' ? 'text-red-600' : '' }}">{{ $suspect->danger_level ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-bold text-brutal-smoke/50 uppercase">النشاط الإجرامي:</span>
                        <span class="text-sm font-black">{{ $suspect->criminal_activity ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
