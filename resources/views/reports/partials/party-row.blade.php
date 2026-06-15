<div class="d-flex align-items-center justify-content-between mb-2">
    <span class="text-xs text-muted-brutal tracking-widest">الطرف رقم {{ $index + 1 }}</span>
    @if($index > 0)
        <button type="button" onclick="removeRow(this, '.party-row')"
                class="btn btn-sm" style="color:#dc3545;font-size:.75rem;font-weight:700;">✕ إزالة</button>
    @endif
</div>
<div class="row g-2">
    @foreach([
        'role'         => 'الصفة',
        'full_name'    => 'الاسم الكامل',
        'national_id'  => 'الرقم القومي',
        'nationality'  => 'الجنسية',
        'age'          => 'السن',
        'occupation'   => 'المهنة',
        'address'      => 'محل الإقامة',
        'phone'        => 'رقم الهاتف',
    ] as $field => $label)
        <div class="col-6 col-md-3">
            <label class="form-label">{{ $label }}</label>
            <input type="text" data-party="{{ $index }}" data-field="{{ $field }}"
                   value="{{ $party[$field] ?? '' }}"
                   placeholder="{{ $label }}"
                   class="form-control party-input">
        </div>
    @endforeach
</div>
