@php $seizure = $seizure ?? []; @endphp
<div class="col-6 col-md-3">
    <label class="form-label">اسم الحرز</label>
    <input type="text" name="seizures_details[{{ $index }}][name]" data-seize="{{ $index }}" data-field="name" placeholder="وصف الحرز"
           value="{{ $seizure['name'] ?? '' }}"
           class="form-control seize-input">
</div>
<div class="col-6 col-md-3">
    <label class="form-label">الكمية</label>
    <input type="text" name="seizures_details[{{ $index }}][quantity]" data-seize="{{ $index }}" data-field="quantity" placeholder="1 / طرد / ..."
           value="{{ $seizure['quantity'] ?? '' }}"
           class="form-control seize-input">
</div>
<div class="col-6 col-md-3">
    <label class="form-label">الحالة</label>
    <input type="text" name="seizures_details[{{ $index }}][condition]" data-seize="{{ $index }}" data-field="condition" placeholder="سليم / تالف / ..."
           value="{{ $seizure['condition'] ?? '' }}"
           class="form-control seize-input">
</div>
<div class="col-6 col-md-3">
    <label class="form-label">وصف إضافي</label>
    <div class="d-flex gap-1">
        <input type="text" name="seizures_details[{{ $index }}][description]" data-seize="{{ $index }}" data-field="description" placeholder="تفاصيل..."
               value="{{ $seizure['description'] ?? '' }}"
               class="form-control seize-input flex-grow-1">
        @if($index > 0)
            <button type="button" onclick="removeRow(this, '.seizure-row')"
                    class="btn btn-sm" style="border:1px solid #f87171;color:#dc3545;font-weight:700;">✕</button>
        @endif
    </div>
</div>
