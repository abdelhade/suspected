<div class="col-6 col-md-3">
    <label class="form-label">اسم الحرز</label>
    <input type="text" data-seize="{{ $index }}" data-field="name" placeholder="وصف الحرز"
           class="form-control seize-input">
</div>
<div class="col-6 col-md-3">
    <label class="form-label">الكمية</label>
    <input type="text" data-seize="{{ $index }}" data-field="quantity" placeholder="1 / طرد / ..."
           class="form-control seize-input">
</div>
<div class="col-6 col-md-3">
    <label class="form-label">الحالة</label>
    <input type="text" data-seize="{{ $index }}" data-field="condition" placeholder="سليم / تالف / ..."
           class="form-control seize-input">
</div>
<div class="col-6 col-md-3">
    <label class="form-label">وصف إضافي</label>
    <div class="d-flex gap-1">
        <input type="text" data-seize="{{ $index }}" data-field="description" placeholder="تفاصيل..."
               class="form-control seize-input flex-grow-1">
        @if($index > 0)
            <button type="button" onclick="removeRow(this, '.seizure-row')"
                    class="btn btn-sm" style="border:1px solid #f87171;color:#dc3545;font-weight:700;">✕</button>
        @endif
    </div>
</div>
