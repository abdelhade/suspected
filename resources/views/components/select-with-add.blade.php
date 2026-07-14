{{--
    مكوّن: قائمة منسدلة مع زر إضافة خيار جديد
    =============================================
    الخصائص:
        name          (string)  - اسم الحقل في الفورم
        id            (string)  - id للـ <select>، يساوي name افتراضياً
        group         (string)  - مجموعة lookup_options
        options       (iterable)- قائمة الخيارات الحالية
        selected      (string)  - القيمة المحددة حالياً
        placeholder   (string)  - نص الخيار الفارغ
        label         (string)  - تسمية الحقل داخل الموديل
        hasError      (bool)    - هل فيه خطأ validation؟

    مثال الاستخدام:
        <x-select-with-add
            name="weapon_type"
            group="weapon_type"
            :options="$weaponTypes"
            :selected="old('weapon_type')"
            placeholder="— اختر النوع —"
            label="نوع السلاح"
        />
--}}

@props([
    'name',
    'id'          => null,
    'group',
    'options'     => [],
    'selected'    => null,
    'placeholder' => '— اختر —',
    'label'       => 'الخيار',
    'hasError'    => false,
])

@php $fieldId = $id ?? $name; @endphp

<div class="d-flex gap-1 align-items-stretch" data-select-group="{{ $group }}">
    <select
        id="{{ $fieldId }}"
        name="{{ $name }}"
        class="form-select flex-grow-1 {{ $hasError ? 'is-invalid' : '' }}"
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $option)
            <option value="{{ $option }}" @selected((string)$selected === (string)$option)>
                {{ $option }}
            </option>
        @endforeach
    </select>

    <button
        type="button"
        class="btn btn-brutal-primary px-3 flex-shrink-0"
        title="إضافة {{ $label }} جديد"
        onclick="AddOptionModal.open('{{ $group }}', '{{ $fieldId }}', '{{ $label }}')"
        style="white-space:nowrap;"
    >+</button>
</div>
