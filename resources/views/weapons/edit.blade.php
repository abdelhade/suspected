@extends('layouts.app')

@section('title', 'تعديل بيانات سلاح')
@section('page-title', 'تعديل: ' . ($weapon->weapon_type ?? 'سلاح'))
@section('page-subtitle', 'تحديث بيانات السلاح والموقف القانوني')

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

<form method="POST" action="{{ route('weapons.update', $weapon) }}" id="weapon-form">
    @csrf @method('PUT')

    {{-- Toolbar --}}
    <div class="d-flex align-items-center justify-content-between mb-4 p-3 position-sticky top-0 bg-white"
         style="z-index:100;border-bottom:3px solid var(--brutal-black);">
        <h1 class="mb-0 fw-bold" style="font-size:1.1rem;">تعديل بيانات السلاح</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('weapons.show', $weapon) }}" class="btn btn-brutal-ghost px-4">← إلغاء</a>
            <button type="submit" id="submit-btn" class="btn btn-brutal-primary px-5">حفظ التعديلات</button>
        </div>
    </div>

    <div class="d-flex flex-column gap-4">

        {{-- القسم 1: البيانات الفنية --}}
        <div class="brutal-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="section-num">١</span> البيانات الفنية للسلاح
            </div>
            <div class="p-4">
                <div class="row g-3">

                    <div class="col-12 col-md-3">
                        <label for="weapon_type" class="form-label">نوع السلاح</label>
                        <x-select-with-add
                            name="weapon_type"
                            group="weapon_type"
                            :options="$weaponTypes"
                            :selected="old('weapon_type', $weapon->weapon_type)"
                            placeholder="— اختر —"
                            label="نوع السلاح"
                            :has-error="$errors->has('weapon_type')"
                        />
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="caliber" class="form-label">العيار</label>
                        <input id="caliber" type="text" name="caliber"
                               value="{{ old('caliber', $weapon->caliber) }}"
                               placeholder="9mm، 7.62×39، 12 gauge..."
                               class="form-control @error('caliber') is-invalid @enderror">
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="brand_make" class="form-label">الماركة / بلد الصنع</label>
                        <input id="brand_make" type="text" name="brand_make"
                               value="{{ old('brand_make', $weapon->brand_make) }}"
                               placeholder="جلوك، بيريتا، روسي..."
                               class="form-control @error('brand_make') is-invalid @enderror">
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="serial_number" class="form-label">الرقم التسلسلي</label>
                        <input id="serial_number" type="text" name="serial_number"
                               value="{{ old('serial_number', $weapon->serial_number) }}"
                               class="form-control font-monospace @error('serial_number') is-invalid @enderror">
                    </div>

                    <div class="col-12">
                        <label for="weapon_condition" class="form-label">الحالة الفنية للسلاح</label>
                        <textarea id="weapon_condition" name="weapon_condition" rows="2"
                                  placeholder="صالح للاستخدام، تالف، معدل..."
                                  class="form-control @error('weapon_condition') is-invalid @enderror">{{ old('weapon_condition', $weapon->weapon_condition) }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        {{-- القسم 2: الموقف القانوني والربط --}}
        <div class="brutal-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="section-num">٢</span> الموقف القانوني والربط بالمحاضر
            </div>
            <div class="p-4">
                <div class="row g-3">

                    <div class="col-12 col-md-4">
                        <label for="classification" class="form-label">تصنيف السلاح</label>
                        <x-select-with-add
                            name="classification"
                            group="weapon_classification"
                            :options="$classifications"
                            :selected="old('classification', $weapon->classification)"
                            placeholder="— اختر —"
                            label="تصنيف السلاح"
                            :has-error="$errors->has('classification')"
                        />
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="current_status" class="form-label">الحالة والمكان الحالي</label>
                        <x-select-with-add
                            name="current_status"
                            group="weapon_status"
                            :options="$statuses"
                            :selected="old('current_status', $weapon->current_status)"
                            placeholder="— اختر —"
                            label="حالة السلاح"
                            :has-error="$errors->has('current_status')"
                        />
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="related_report_number" class="form-label">رقم المحضر المرتبط</label>
                        <input id="related_report_number" type="text" name="related_report_number"
                               value="{{ old('related_report_number', $weapon->related_report_number) }}"
                               placeholder="2026/1234"
                               class="form-control font-monospace @error('related_report_number') is-invalid @enderror">
                        <div class="mt-1" style="font-size:.7rem;color:rgba(26,26,26,.45);">ربط نصي — لا يشترط وجود المحضر في النظام</div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="capture_date_time" class="form-label">تاريخ ووقت الضبط أو التسجيل</label>
                        <input id="capture_date_time" type="datetime-local" name="capture_date_time"
                               value="{{ old('capture_date_time', $weapon->capture_date_time?->format('Y-m-d\TH:i')) }}"
                               class="form-control @error('capture_date_time') is-invalid @enderror">
                    </div>

                    <div class="col-12">
                        <label for="holder_info" class="form-label">بيانات الحائز للسلاح</label>
                        <textarea id="holder_info" name="holder_info" rows="3"
                                  placeholder="الاسم الرباعي — الرقم القومي — العنوان — رقم الرخصة..."
                                  class="form-control @error('holder_info') is-invalid @enderror">{{ old('holder_info', $weapon->holder_info) }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        {{-- القسم 3: ملاحظات --}}
        <div class="brutal-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="section-num">٣</span> ملاحظات وبيانات الذخيرة
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="notes" class="form-label">ملاحظات عامة / بيانات الذخيرة المضبوطة</label>
                        <textarea id="notes" name="notes" rows="4"
                                  placeholder="مثال: ضُبط معه 15 طلقة 9mm..."
                                  class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $weapon->notes) }}</textarea>
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
    if (!['INPUT', 'SELECT', 'TEXTAREA'].includes(e.target.tagName)) return;
    if (e.target.tagName === 'SELECT' && (e.key === 'ArrowUp' || e.key === 'ArrowDown')) return;
    if (e.target.tagName === 'TEXTAREA' && e.key === 'Enter') return;

    const formElements = Array.from(document.querySelectorAll(
        '#weapon-form input:not([type="hidden"]):not([disabled]), #weapon-form select:not([disabled]), #weapon-form textarea:not([disabled]), #submit-btn'
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
