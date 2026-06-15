@extends('layouts.app')

@section('title', 'تعديل بيانات مسجل')
@section('page-title', 'تعديل بيانات: ' . $suspect->full_name)
@section('page-subtitle', 'تحديث بيانات المسجل أو المطلوب أمنياً')

@section('content')

@if($errors->any())
    <div class="alert mb-4 p-3" style="border:1px solid var(--brutal-black);background:var(--brutal-black);">
        <div class="neon-text fw-bold mb-2" style="font-size:.875rem;">يوجد أخطاء في البيانات:</div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li style="font-size:.75rem;color:var(--neon);opacity:.85;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('suspects.update', $suspect) }}" enctype="multipart/form-data" id="suspect-form">
    @csrf @method('PUT')

    {{-- Toolbar --}}
    <div class="d-flex align-items-center justify-content-between mb-4 p-3 position-sticky top-0 bg-white"
         style="z-index:100;border-bottom:3px solid var(--brutal-black);">
        <h1 class="mb-0 fw-bold" style="font-size:1.1rem;">تعديل المسجل</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('suspects.show', $suspect) }}" class="btn btn-brutal-ghost px-4">← إلغاء</a>
            <button type="submit" id="submit-btn" class="btn btn-brutal-primary px-5">حفظ التعديلات</button>
        </div>
    </div>

    <div class="d-flex flex-column gap-4">

        {{-- القسم 1: البيانات الشخصية --}}
        <div class="brutal-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="section-num">١</span> البيانات الشخصية
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        <label for="full_name" class="form-label">الاسم الرباعي</label>
                        <input id="full_name" type="text" name="full_name"
                               value="{{ old('full_name', $suspect->full_name) }}"
                               class="form-control @error('full_name') is-invalid @enderror">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="alias_name" class="form-label">اسم الشهرة / اللقب</label>
                        <input id="alias_name" type="text" name="alias_name"
                               value="{{ old('alias_name', $suspect->alias_name) }}"
                               class="form-control @error('alias_name') is-invalid @enderror">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="national_id" class="form-label">الرقم القومي</label>
                        <input id="national_id" type="text" name="national_id"
                               value="{{ old('national_id', $suspect->national_id) }}"
                               class="form-control @error('national_id') is-invalid @enderror">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="birth_date" class="form-label">تاريخ الميلاد</label>
                        <input id="birth_date" type="date" name="birth_date"
                               value="{{ old('birth_date', $suspect->birth_date?->format('Y-m-d')) }}"
                               class="form-control @error('birth_date') is-invalid @enderror">
                    </div>
                    <div class="col-12">
                        <label for="current_address" class="form-label">محل الإقامة</label>
                        <textarea id="current_address" name="current_address" rows="2"
                                  class="form-control @error('current_address') is-invalid @enderror">{{ old('current_address', $suspect->current_address) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- القسم 2: التصنيف الجنائي --}}
        <div class="brutal-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="section-num">٢</span> التصنيف الجنائي
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label for="registration_category" class="form-label">فئة المسجل</label>
                        <select id="registration_category" name="registration_category"
                                class="form-select @error('registration_category') is-invalid @enderror">
                            <option value="">— اختر —</option>
                            @foreach(['مسجل شقي خطر', 'معلومات', 'مطلوب', 'مشتبه به', 'زائر'] as $cat)
                                <option value="{{ $cat }}" @selected(old('registration_category', $suspect->registration_category) === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="danger_level" class="form-label">مستوى الخطورة</label>
                        <select id="danger_level" name="danger_level"
                                class="form-select @error('danger_level') is-invalid @enderror">
                            <option value="">— اختر —</option>
                            @foreach(['عالية جداً', 'عالية', 'متوسطة', 'منخفضة'] as $danger)
                                <option value="{{ $danger }}" @selected(old('danger_level', $suspect->danger_level) === $danger)>{{ $danger }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="current_status" class="form-label">الحالة الجنائية الحالية</label>
                        <select id="current_status" name="current_status"
                                class="form-select @error('current_status') is-invalid @enderror">
                            <option value="">— اختر —</option>
                            @foreach(['هارب', 'محبوس', 'مفرج عنه', 'تحت المراقبة', 'حر'] as $status)
                                <option value="{{ $status }}" @selected(old('current_status', $suspect->current_status) === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="criminal_activity" class="form-label">النشاط الإجرامي</label>
                        <input id="criminal_activity" type="text" name="criminal_activity"
                               value="{{ old('criminal_activity', $suspect->criminal_activity) }}"
                               placeholder="سرقة، مخدرات، بلطجة..."
                               class="form-control @error('criminal_activity') is-invalid @enderror">
                    </div>
                </div>
            </div>
        </div>

        {{-- القسم 3: المواصفات الجسدية والصورة --}}
        <div class="brutal-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="section-num">٣</span> المواصفات الجسدية والصورة
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label for="height_cm" class="form-label">الطول (سم)</label>
                        <input id="height_cm" type="number" name="height_cm"
                               value="{{ old('height_cm', $suspect->height_cm) }}"
                               class="form-control @error('height_cm') is-invalid @enderror">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="body_build" class="form-label">بنية الجسم</label>
                        <select id="body_build" name="body_build"
                                class="form-select @error('body_build') is-invalid @enderror">
                            <option value="">— اختر —</option>
                            @foreach(['نحيف', 'متوسط', 'رياضي', 'ممتلئ', 'بدين'] as $build)
                                <option value="{{ $build }}" @selected(old('body_build', $suspect->body_build) === $build)>{{ $build }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="skin_color" class="form-label">لون البشرة</label>
                        <select id="skin_color" name="skin_color"
                                class="form-select @error('skin_color') is-invalid @enderror">
                            <option value="">— اختر —</option>
                            @foreach(['أبيض', 'قمحي', 'أسمر', 'أسود'] as $color)
                                <option value="{{ $color }}" @selected(old('skin_color', $suspect->skin_color ?? '') === $color)>{{ $color }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="distinguishing_marks" class="form-label">العلامات المميزة والوشوم</label>
                        <textarea id="distinguishing_marks" name="distinguishing_marks" rows="2"
                                  class="form-control @error('distinguishing_marks') is-invalid @enderror">{{ old('distinguishing_marks', $suspect->distinguishing_marks) }}</textarea>
                    </div>
                    <div class="col-12 pt-2" style="border-top:1px solid rgba(10,10,10,.15);">
                        <label for="profile_image" class="form-label">تغيير الصورة الشخصية (اختياري)</label>
                        <div class="d-flex align-items-start gap-3">
                            @if($suspect->profile_image_path)
                                <img src="{{ asset('storage/' . $suspect->profile_image_path) }}" alt="الصورة الحالية"
                                     style="width:64px;height:64px;object-fit:cover;border:1px solid var(--brutal-black);">
                            @endif
                            <input id="profile_image" type="file" name="profile_image" accept="image/*"
                                   class="form-control @error('profile_image') is-invalid @enderror">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

@push('scripts')
<script>
document.addEventListener('keydown', function(e) {
    if (!['Enter', 'ArrowUp', 'ArrowDown'].includes(e.key)) return;
    if (!['INPUT', 'SELECT'].includes(e.target.tagName)) return;
    if (e.target.tagName === 'SELECT' && (e.key === 'ArrowUp' || e.key === 'ArrowDown')) return;

    const formElements = Array.from(document.querySelectorAll(
        '#suspect-form input:not([type="hidden"]):not([disabled]), #suspect-form select:not([disabled]), #submit-btn'
    ));
    const index = formElements.indexOf(e.target);
    if (index > -1) {
        let next = index;
        if (e.key === 'Enter' || e.key === 'ArrowDown') { e.preventDefault(); next = index + 1; }
        else if (e.key === 'ArrowUp') { e.preventDefault(); next = index - 1; }
        if (next >= 0 && next < formElements.length) {
            formElements[next].focus();
            if (formElements[next].select && !['button','submit','file'].includes(formElements[next].type)) {
                formElements[next].select();
            }
        }
    }
});
</script>
@endpush

@endsection
