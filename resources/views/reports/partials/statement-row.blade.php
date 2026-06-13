<div class="mb-2 flex items-center justify-between">
    <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">إفادة {{ $index + 1 }}</span>
    @if($index > 0)
        <button type="button" onclick="removeRow(this, '.statement-row')" class="text-xs font-bold text-red-500">✕ إزالة</button>
    @endif
</div>
<div class="grid grid-cols-1 gap-2 sm:grid-cols-2 mb-2">
    <div class="flex flex-col gap-1">
        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">اسم الطرف</label>
        <input type="text" data-stmt="{{ $index }}" data-field="party" placeholder="اسم الشخص"
               class="stmt-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
    </div>
    <div class="flex flex-col gap-1">
        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">الصفة</label>
        <input type="text" data-stmt="{{ $index }}" data-field="role" placeholder="مشتكي / شاهد / ..."
               class="stmt-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
    </div>
</div>
<div class="flex flex-col gap-1">
    <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">نص الإفادة (سؤال وجواب)</label>
    <textarea data-stmt="{{ $index }}" data-field="text" rows="3" placeholder="س: ... &#10;ج: ..."
              class="stmt-textarea border border-brutal-black/30 px-2.5 py-2 text-sm font-bold resize-y focus:outline-none focus:border-neon"></textarea>
</div>
