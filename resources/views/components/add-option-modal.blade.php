{{--
    موديل عام لإضافة خيار جديد لأي قائمة منسدلة
    يُضاف مرة واحدة في الـ layout ويعمل مع جميع x-select-with-add
--}}

<div class="modal fade" id="add-option-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border:2px solid var(--brutal-black);border-radius:0;box-shadow:4px 4px 0 var(--brutal-black);">
            <div class="modal-header" style="background:var(--brutal-black);border-bottom:2px solid var(--brutal-black);">
                <h5 id="add-option-modal-title" class="modal-title neon-text fw-bold" style="font-size:.875rem;letter-spacing:.08em;">
                    إضافة خيار جديد
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label id="add-option-modal-label" for="add-option-input" class="form-label fw-bold text-xs tracking-widest">
                        الاسم
                    </label>
                    <input
                        type="text"
                        id="add-option-input"
                        class="form-control"
                        placeholder="أدخل الخيار الجديد..."
                        autocomplete="off"
                    >
                    <div id="add-option-error" class="text-danger mt-1" style="font-size:.75rem;display:none;"></div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-brutal-ghost px-4" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" id="add-option-save-btn" class="btn btn-brutal-primary px-5">حفظ</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const AddOptionModal = (() => {
    let _group    = null;
    let _selectId = null;
    let _modal    = null;

    function getModal() {
        if (!_modal) _modal = new bootstrap.Modal(document.getElementById('add-option-modal'));
        return _modal;
    }

    function open(group, selectId, label) {
        _group    = group;
        _selectId = selectId;

        document.getElementById('add-option-modal-title').textContent = 'إضافة ' + label + ' جديد';
        document.getElementById('add-option-modal-label').textContent = label;
        document.getElementById('add-option-input').value = '';

        const errEl = document.getElementById('add-option-error');
        errEl.style.display = 'none';
        errEl.textContent   = '';

        getModal().show();
        setTimeout(() => document.getElementById('add-option-input').focus(), 300);
    }

    async function save() {
        const input  = document.getElementById('add-option-input');
        const errEl  = document.getElementById('add-option-error');
        const saveBtn = document.getElementById('add-option-save-btn');
        const value  = input.value.trim();

        errEl.style.display = 'none';

        if (!value) {
            errEl.textContent   = 'الرجاء إدخال الاسم';
            errEl.style.display = 'block';
            input.focus();
            return;
        }

        saveBtn.disabled    = true;
        saveBtn.textContent = 'جاري الحفظ...';

        try {
            const res = await fetch('{{ route("lookups.options.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept':       'application/json',
                },
                body: JSON.stringify({ group: _group, value }),
            });

            const data = await res.json();

            if (res.ok && data.success) {
                const select = document.getElementById(_selectId);
                if (select) {
                    // إذا كانت القيمة موجودة مسبقاً فقط حدّدها
                    let existing = Array.from(select.options).find(o => o.value === data.data.value);
                    if (!existing) {
                        const opt  = document.createElement('option');
                        opt.value  = data.data.value;
                        opt.text   = data.data.value;
                        select.add(opt);
                        existing = opt;
                    }
                    existing.selected = true;
                    select.dispatchEvent(new Event('change'));
                }
                getModal().hide();
            } else {
                const msg = data.errors?.value?.[0]
                    || data.errors?.group?.[0]
                    || data.message
                    || 'حدث خطأ. تأكد أن الاسم صحيح.';
                errEl.textContent   = msg;
                errEl.style.display = 'block';
            }
        } catch (e) {
            errEl.textContent   = 'حدث خطأ في الاتصال بالخادم.';
            errEl.style.display = 'block';
        } finally {
            saveBtn.disabled    = false;
            saveBtn.textContent = 'حفظ';
        }
    }

    // تسجيل الأحداث عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('add-option-save-btn').addEventListener('click', save);

        // حفظ بـ Enter داخل الـ input
        document.getElementById('add-option-input').addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); save(); }
        });
    });

    return { open };
})();
</script>
