{{-- Lookup Modal --}}
<div id="lookup-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-brutal-black/60 p-4">
    <div class="brutal-card w-full max-w-sm">
        <div class="flex items-center justify-between border-b border-brutal-black bg-brutal-black px-4 py-3">
            <h3 id="lookup-modal-title" class="text-sm font-bold uppercase tracking-widest text-neon">إضافة عنصر جديد</h3>
            <button type="button" onclick="closeLookupModal()" class="text-neon hover:text-white">✕</button>
        </div>
        <div class="p-4 space-y-4">
            <div class="flex flex-col gap-1.5">
                <label id="lookup-modal-label" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">الاسم</label>
                <input type="text" id="lookup-input" class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon">
                <p id="lookup-error" class="text-xs text-red-600 hidden"></p>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeLookupModal()" class="brutal-btn-ghost px-4 py-2">إلغاء</button>
                <button type="button" id="lookup-save-btn" class="brutal-btn-primary px-4 py-2">حفظ</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentLookupType = null;

    function openLookupModal(type) {
        currentLookupType = type;
        document.getElementById('lookup-modal-title').textContent = type === 'type' ? 'إضافة نوع محضر' : 'إضافة حالة محضر';
        document.getElementById('lookup-modal-label').textContent = type === 'type' ? 'اسم النوع' : 'اسم الحالة';
        document.getElementById('lookup-input').value = '';
        document.getElementById('lookup-error').classList.add('hidden');
        document.getElementById('lookup-modal').classList.remove('hidden');
        document.getElementById('lookup-modal').classList.add('flex');
        document.getElementById('lookup-input').focus();
    }

    function closeLookupModal() {
        document.getElementById('lookup-modal').classList.add('hidden');
        document.getElementById('lookup-modal').classList.remove('flex');
    }

    document.getElementById('lookup-save-btn').addEventListener('click', async function() {
        const input = document.getElementById('lookup-input');
        const val = input.value.trim();
        const errorEl = document.getElementById('lookup-error');
        
        if (!val) {
            errorEl.textContent = 'الرجاء إدخال الاسم';
            errorEl.classList.remove('hidden');
            return;
        }

        const url = currentLookupType === 'type' ? '{{ route("lookups.types.store") }}' : '{{ route("lookups.statuses.store") }}';
        const selectId = currentLookupType === 'type' ? 'report_type' : 'current_status';
        
        this.disabled = true;
        this.textContent = 'جاري الحفظ...';

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name: val })
            });
            const data = await res.json();
            
            if (data.success) {
                // إضافة الـ option الجديد
                const select = document.getElementById(selectId);
                const option = document.createElement('option');
                option.value = data.data.name;
                option.text = data.data.name;
                option.selected = true;
                select.add(option);
                closeLookupModal();
            } else {
                errorEl.textContent = data.message || 'حدث خطأ. تأكد أن الاسم غير مكرر.';
                errorEl.classList.remove('hidden');
            }
        } catch (e) {
            errorEl.textContent = 'حدث خطأ في الاتصال بالخادم.';
            errorEl.classList.remove('hidden');
        } finally {
            this.disabled = false;
            this.textContent = 'حفظ';
        }
    });
</script>
