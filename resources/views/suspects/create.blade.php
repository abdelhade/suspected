@extends('layouts.app')

@section('title', 'إضافة مسجل')
@section('page-title', 'إضافة مسجل أو مطلوب')
@section('page-subtitle', 'إدخال بيانات التسجيل الجنائي والمواصفات')

@section('content')

@if($errors->any())
    <div class="mb-5 border border-brutal-black bg-brutal-black p-4">
        <p class="mb-2 text-sm font-bold text-neon">يوجد أخطاء في البيانات:</p>
        <ul class="space-y-1 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li class="text-xs font-bold text-neon/80">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('suspects.store') }}" enctype="multipart/form-data" id="suspect-form">
    @csrf

    {{-- Toolbar --}}
    <div class="mb-5 flex items-center justify-between sticky top-0 z-10 bg-brutal-white/90 backdrop-blur-sm p-3 border-b-4 border-brutal-black">
        <h1 class="text-xl font-black uppercase text-brutal-black">إضافة مسجل جديد</h1>
        <div class="flex gap-2">
            <a href="{{ route('suspects.index') }}" class="brutal-btn-ghost px-5 py-2">
                ← إلغاء
            </a>
            <button type="submit" id="submit-btn" class="brutal-btn-primary px-8 py-2">
                حفظ البيانات
            </button>
        </div>
    </div>

    <div class="space-y-5">

        {{-- =====================================================
             القسم 1: البيانات الشخصية
        ====================================================== --}}
        <div class="brutal-card">
            <div class="flex items-center gap-3 border-b border-brutal-black bg-brutal-black px-5 py-3">
                <span class="flex h-6 w-6 items-center justify-center bg-neon text-xs font-bold text-brutal-black">١</span>
                <h2 class="text-sm font-bold uppercase tracking-widest text-neon">البيانات الشخصية</h2>
            </div>
            <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-3">

                {{-- الاسم الرباعي --}}
                <div class="flex flex-col gap-1.5">
                    <label for="full_name" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">الاسم الرباعي</label>
                    <input id="full_name" type="text" name="full_name"
                           value="{{ old('full_name') }}"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon focus:bg-neon/5 @error('full_name') border-red-500 @enderror">
                </div>

                {{-- اسم الشهرة --}}
                <div class="flex flex-col gap-1.5">
                    <label for="alias_name" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">اسم الشهرة / اللقب</label>
                    <input id="alias_name" type="text" name="alias_name"
                           value="{{ old('alias_name') }}"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('alias_name') border-red-500 @enderror">
                </div>

                {{-- الرقم القومي --}}
                <div class="flex flex-col gap-1.5">
                    <label for="national_id" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">الرقم القومي</label>
                    <input id="national_id" type="text" name="national_id"
                           value="{{ old('national_id') }}"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('national_id') border-red-500 @enderror">
                </div>

                {{-- تاريخ الميلاد --}}
                <div class="flex flex-col gap-1.5">
                    <label for="birth_date" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">تاريخ الميلاد</label>
                    <input id="birth_date" type="date" name="birth_date"
                           value="{{ old('birth_date') }}"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('birth_date') border-red-500 @enderror">
                </div>

                {{-- محل الإقامة --}}
                <div class="flex flex-col gap-1.5 md:col-span-2">
                    <label for="current_address" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">محل الإقامة</label>
                    <textarea id="current_address" name="current_address" rows="1"
                              class="border border-brutal-black bg-brutal-white px-3 py-2 text-sm font-bold resize-y focus:outline-none focus:border-neon @error('current_address') border-red-500 @enderror">{{ old('current_address') }}</textarea>
                </div>

            </div>
        </div>

        {{-- =====================================================
             القسم 2: التصنيف الجنائي
        ====================================================== --}}
        <div class="brutal-card">
            <div class="flex items-center gap-3 border-b border-brutal-black bg-brutal-black px-5 py-3">
                <span class="flex h-6 w-6 items-center justify-center bg-neon text-xs font-bold text-brutal-black">٢</span>
                <h2 class="text-sm font-bold uppercase tracking-widest text-neon">التصنيف الجنائي</h2>
            </div>
            <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-3">

                {{-- فئة المسجل --}}
                <div class="flex flex-col gap-1.5">
                    <label for="registration_category" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">فئة المسجل</label>
                    <select id="registration_category" name="registration_category"
                            class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('registration_category') border-red-500 @enderror">
                        <option value="">— اختر —</option>
                        @foreach(['مسجل شقي خطر', 'معلومات', 'مطلوب', 'مشتبه به', 'زائر'] as $cat)
                            <option value="{{ $cat }}" @selected(old('registration_category') === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- درجة الخطورة --}}
                <div class="flex flex-col gap-1.5">
                    <label for="danger_level" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">مستوى الخطورة</label>
                    <select id="danger_level" name="danger_level"
                            class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('danger_level') border-red-500 @enderror">
                        <option value="">— اختر —</option>
                        @foreach(['عالية جداً', 'عالية', 'متوسطة', 'منخفضة'] as $danger)
                            <option value="{{ $danger }}" @selected(old('danger_level') === $danger)>{{ $danger }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- الحالة --}}
                <div class="flex flex-col gap-1.5">
                    <label for="current_status" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">الحالة الجنائية الحالية</label>
                    <select id="current_status" name="current_status"
                            class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('current_status') border-red-500 @enderror">
                        <option value="">— اختر —</option>
                        @foreach(['هارب', 'محبوس', 'مفرج عنه', 'تحت المراقبة', 'حر'] as $status)
                            <option value="{{ $status }}" @selected(old('current_status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- النشاط الإجرامي --}}
                <div class="flex flex-col gap-1.5 md:col-span-3">
                    <label for="criminal_activity" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">النشاط الإجرامي</label>
                    <input id="criminal_activity" type="text" name="criminal_activity"
                           value="{{ old('criminal_activity') }}"
                           placeholder="سرقة، مخدرات، بلطجة..."
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('criminal_activity') border-red-500 @enderror">
                </div>

            </div>
        </div>

        {{-- =====================================================
             القسم 3: المواصفات والمرفقات
        ====================================================== --}}
        <div class="brutal-card">
            <div class="flex items-center gap-3 border-b border-brutal-black bg-brutal-black px-5 py-3">
                <span class="flex h-6 w-6 items-center justify-center bg-neon text-xs font-bold text-brutal-black">٣</span>
                <h2 class="text-sm font-bold uppercase tracking-widest text-neon">المواصفات الجسدية والصورة</h2>
            </div>
            <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-3">

                {{-- الطول --}}
                <div class="flex flex-col gap-1.5">
                    <label for="height_cm" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">الطول (سم)</label>
                    <input id="height_cm" type="number" name="height_cm"
                           value="{{ old('height_cm') }}"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('height_cm') border-red-500 @enderror">
                </div>

                {{-- بنية الجسم --}}
                <div class="flex flex-col gap-1.5 md:col-span-2">
                    <label for="body_build" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">بنية الجسم</label>
                    <select id="body_build" name="body_build"
                            class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('body_build') border-red-500 @enderror">
                        <option value="">— اختر —</option>
                        @foreach(['نحيف', 'متوسط', 'رياضي', 'ممتلئ', 'بدين'] as $build)
                            <option value="{{ $build }}" @selected(old('body_build') === $build)>{{ $build }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- العلامات المميزة --}}
                <div class="flex flex-col gap-1.5 md:col-span-3">
                    <label for="distinguishing_marks" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">العلامات المميزة والوشوم</label>
                    <textarea id="distinguishing_marks" name="distinguishing_marks" rows="2"
                              class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold resize-y focus:outline-none focus:border-neon @error('distinguishing_marks') border-red-500 @enderror">{{ old('distinguishing_marks') }}</textarea>
                </div>

                {{-- الصورة الشخصية --}}
                <div class="flex flex-col gap-1.5 md:col-span-3 border-t border-brutal-black/20 pt-4 mt-2">
                    <label for="profile_image" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">الصورة الشخصية (اختياري)</label>
                    <input id="profile_image" type="file" name="profile_image" accept="image/*"
                           class="border border-brutal-black bg-brutal-white p-2 text-sm font-bold focus:outline-none focus:border-neon file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-sm file:font-semibold file:bg-brutal-black file:text-neon hover:file:bg-brutal-smoke @error('profile_image') border-red-500 @enderror">
                </div>

            </div>
        </div>

    </div>
</form>

@push('scripts')
<script>
document.addEventListener('keydown', function(e) {
    if (!['Enter', 'ArrowUp', 'ArrowDown'].includes(e.key)) return;
    if (!['INPUT', 'SELECT', 'TEXTAREA'].includes(e.target.tagName)) return;
    if (e.target.tagName === 'SELECT' && (e.key === 'ArrowUp' || e.key === 'ArrowDown')) return;

    const formElements = Array.from(document.querySelectorAll('#suspect-form input:not([type="hidden"]):not([disabled]), #suspect-form select:not([disabled]), #suspect-form textarea:not([disabled]), #submit-btn'));
    const index = formElements.indexOf(e.target);
    
    if (index > -1) {
        let nextIndex = index;
        if (e.key === 'Enter' || e.key === 'ArrowDown') {
            e.preventDefault();
            nextIndex = index + 1;
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            nextIndex = index - 1;
        }
        if (nextIndex >= 0 && nextIndex < formElements.length) {
            formElements[nextIndex].focus();
            if(formElements[nextIndex].select && formElements[nextIndex].type !== 'button' && formElements[nextIndex].type !== 'submit' && formElements[nextIndex].type !== 'file') {
                formElements[nextIndex].select();
            }
        }
    }
});
</script>
@endpush

@endsection