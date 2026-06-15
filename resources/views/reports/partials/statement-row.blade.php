<div class="d-flex align-items-center justify-content-between mb-2">
    <span class="text-xs text-muted-brutal tracking-widest">إفادة {{ $index + 1 }}</span>
    @if($index > 0)
        <button type="button" onclick="removeRow(this, '.statement-row')"
                class="btn btn-sm" style="color:#dc3545;font-size:.75rem;font-weight:700;">✕ إزالة</button>
    @endif
</div>
<div class="row g-2 mb-2">
    <div class="col-12 col-sm-6">
        <label class="form-label">اسم الطرف</label>
        <input type="text" data-stmt="{{ $index }}" data-field="party" placeholder="اسم الشخص"
               class="form-control stmt-input">
    </div>
    <div class="col-12 col-sm-6">
        <label class="form-label">الصفة</label>
        <input type="text" data-stmt="{{ $index }}" data-field="role" placeholder="مشتكي / شاهد / ..."
               class="form-control stmt-input">
    </div>
</div>
<div>
    <label class="form-label">نص الإفادة (سؤال وجواب)</label>
    <textarea data-stmt="{{ $index }}" data-field="text" rows="3"
              placeholder="س: ... &#10;ج: ..."
              class="form-control stmt-textarea"></textarea>
</div>
