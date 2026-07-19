{{-- Lookup Modal --}}
<div class="modal fade" id="lookup-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border:1px solid var(--brutal-black);">
            <div class="modal-header" style="background:var(--brutal-black);border-bottom:1px solid var(--brutal-black);">
                <h5 id="lookup-modal-title" class="modal-title neon-text fw-bold" style="font-size:.875rem;letter-spacing:.08em;">
                    إضافة عنصر جديد
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label id="lookup-modal-label" class="form-label">الاسم</label>
                    <input type="text" id="lookup-input" class="form-control">
                    <div id="lookup-error" class="text-danger mt-1" style="font-size:.75rem;display:none;"></div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-brutal-ghost px-4" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" id="lookup-save-btn" class="btn btn-brutal-primary px-4">حفظ</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentLookupType = null;
    let lookupModalInstance = null;

    function openLookupModal(type) {
        currentLookupType = type;
        document.getElementById('lookup-modal-title').textContent = type === 'type' ? 'إضافة نوع محضر' : 'إضافة حالة محضر';
        document.getElementById('lookup-modal-label').textContent = type === 'type' ? 'اسم النوع' : 'اسم الحالة';
        document.getElementById('lookup-input').value = '';
        document.getElementById('lookup-error').style.display = 'none';
        if (!lookupModalInstance) {
            lookupModalInstance = new bootstrap.Modal(document.getElementById('lookup-modal'));
        }
        lookupModalInstance.show();
        setTimeout(() => document.getElementById('lookup-input').focus(), 300);
    }

    document.getElementById('lookup-save-btn').addEventListener('click', async function() {
        const input = document.getElementById('lookup-input');
        const val = input.value.trim();
        const errorEl = document.getElementById('lookup-error');

        if (!val) {
            errorEl.textContent = 'الرجاء إدخال الاسم';
            errorEl.style.display = 'block';
            return;
        }

        const url = currentLookupType === 'type'
            ? '{{ route("lookups.types.store") }}'
            : '{{ route("lookups.statuses.store") }}';
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
                const select = document.getElementById(selectId);
                const option = document.createElement('option');
                option.value = data.data.name;
                option.text = data.data.name;
                option.selected = true;
                select.add(option);
                lookupModalInstance.hide();
            } else {
                errorEl.textContent = data.message || 'حدث خطأ. تأكد أن الاسم غير مكرر.';
                errorEl.style.display = 'block';
            }
        } catch (e) {
            errorEl.textContent = 'حدث خطأ في الاتصال بالخادم.';
            errorEl.style.display = 'block';
        } finally {
            this.disabled = false;
            this.textContent = 'حفظ';
        }
    });
</script>
