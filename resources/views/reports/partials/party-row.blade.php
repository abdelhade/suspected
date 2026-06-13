<div class="mb-3 flex items-center justify-between">
    <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">الطرف رقم {{ $index + 1 }}</span>
    @if($index > 0)
        <button type="button" onclick="removeRow(this, '.party-row')" class="text-xs font-bold text-red-500 hover:text-red-700">✕ إزالة</button>
    @endif
</div>
<div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
    <div class="flex flex-col gap-1">
        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">الصفة</label>
        <input type="text" data-party="{{ $index }}" data-field="role" placeholder="مشتكي / مشتكى عليه / شاهد"
               class="party-input border border-brutal-black/40 bg-brutal-white px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
    </div>
    <div class="flex flex-col gap-1">
        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">الاسم الكامل</label>
        <input type="text" data-party="{{ $index }}" data-field="full_name" placeholder="الاسم الرباعي"
               class="party-input border border-brutal-black/40 bg-brutal-white px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
    </div>
    <div class="flex flex-col gap-1">
        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">الرقم القومي</label>
        <input type="text" data-party="{{ $index }}" data-field="national_id" placeholder="14 رقم"
               class="party-input border border-brutal-black/40 bg-brutal-white px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
    </div>
    <div class="flex flex-col gap-1">
        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">الجنسية</label>
        <input type="text" data-party="{{ $index }}" data-field="nationality" placeholder="مصري / أجنبي"
               class="party-input border border-brutal-black/40 bg-brutal-white px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
    </div>
    <div class="flex flex-col gap-1">
        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">السن</label>
        <input type="text" data-party="{{ $index }}" data-field="age" placeholder="العمر بالسنوات"
               class="party-input border border-brutal-black/40 bg-brutal-white px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
    </div>
    <div class="flex flex-col gap-1">
        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">المهنة</label>
        <input type="text" data-party="{{ $index }}" data-field="occupation" placeholder="الوظيفة أو المهنة"
               class="party-input border border-brutal-black/40 bg-brutal-white px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
    </div>
    <div class="flex flex-col gap-1">
        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">محل الإقامة</label>
        <input type="text" data-party="{{ $index }}" data-field="address" placeholder="العنوان الكامل"
               class="party-input border border-brutal-black/40 bg-brutal-white px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
    </div>
    <div class="flex flex-col gap-1">
        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">رقم الهاتف</label>
        <input type="text" data-party="{{ $index }}" data-field="phone" placeholder="01XXXXXXXXX"
               class="party-input border border-brutal-black/40 bg-brutal-white px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
    </div>
</div>
