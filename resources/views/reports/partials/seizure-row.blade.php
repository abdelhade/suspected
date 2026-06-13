<div class="flex flex-col gap-1">
    <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">اسم الحرز</label>
    <input type="text" data-seize="{{ $index }}" data-field="name" placeholder="وصف الحرز"
           class="seize-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
</div>
<div class="flex flex-col gap-1">
    <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">الكمية</label>
    <input type="text" data-seize="{{ $index }}" data-field="quantity" placeholder="1 / طرد / ..."
           class="seize-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
</div>
<div class="flex flex-col gap-1">
    <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">الحالة</label>
    <input type="text" data-seize="{{ $index }}" data-field="condition" placeholder="سليم / تالف / ..."
           class="seize-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
</div>
<div class="flex flex-col gap-1">
    <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">وصف إضافي</label>
    <div class="flex gap-1">
        <input type="text" data-seize="{{ $index }}" data-field="description" placeholder="تفاصيل..."
               class="seize-input flex-1 border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
        @if($index > 0)
            <button type="button" onclick="removeRow(this, '.seizure-row')" class="border border-red-400 px-2 text-xs text-red-500 hover:bg-red-50">✕</button>
        @endif
    </div>
</div>
