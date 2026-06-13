@extends('layouts.app')

@section('title', 'محضر جديد')
@section('page-title', 'إنشاء محضر جديد')
@section('page-subtitle', 'تعبئة بيانات المحضر الأمني')

@section('content')

{{-- Validation Errors --}}
@if($errors->any())
    <div class="mb-5 border border-brutal-black bg-brutal-black p-4">
        <p class="mb-2 text-sm font-bold text-neon">يوجد أخطاء في البيانات:</p>
        <ul class="space-y-1 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li class="text-xs font-bold text-neon/80">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('reports.store') }}" id="report-form">
    @csrf

    {{-- Toolbar --}}
    <div class="mb-5 flex items-center justify-between sticky top-0 z-10 bg-brutal-white/90 backdrop-blur-sm p-3 border-b-4 border-brutal-black">
        <h1 class="text-xl font-black uppercase text-brutal-black">إنشاء محضر جديد</h1>
        <div class="flex gap-2">
            <a href="{{ route('reports.index') }}" class="brutal-btn-ghost px-5 py-2">
                ← إلغاء
            </a>
            <button type="submit" id="submit-btn" class="brutal-btn-primary px-8 py-2">
                حفظ المحضر
            </button>
        </div>
    </div>

    <div class="space-y-5">

        {{-- =====================================================
             القسم 1: البيانات العامة
        ====================================================== --}}
        <div class="brutal-card">
            <div class="flex items-center gap-3 border-b border-brutal-black bg-brutal-black px-5 py-3">
                <span class="flex h-6 w-6 items-center justify-center bg-neon text-xs font-bold text-brutal-black">١</span>
                <h2 class="text-sm font-bold uppercase tracking-widest text-neon">البيانات العامة للمحضر</h2>
            </div>
            <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">

                {{-- رقم المحضر --}}
                <div class="flex flex-col gap-1.5">
                    <label for="report_number" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">رقم المحضر</label>
                    <input id="report_number" type="text" name="report_number"
                           value="{{ old('report_number') }}"
                           placeholder="مثال: 2024/1250"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon focus:bg-neon/5 @error('report_number') border-red-500 @enderror">
                    @error('report_number')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- نوع المحضر --}}
                <div class="flex flex-col gap-1.5">
                    <label for="report_type" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">نوع المحضر</label>
                    <div class="flex gap-1">
                        <select id="report_type" name="report_type"
                                class="flex-1 border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('report_type') border-red-500 @enderror">
                            <option value="">— اختر النوع —</option>
                            @foreach($reportTypes as $type)
                                <option value="{{ $type }}" @selected(old('report_type') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="openLookupModal('type')" class="border border-brutal-black bg-brutal-black px-3 text-neon hover:bg-brutal-smoke font-bold" title="إضافة نوع جديد">
                            +
                        </button>
                    </div>
                    @error('report_type')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- حالة المحضر --}}
                <div class="flex flex-col gap-1.5">
                    <label for="current_status" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">حالة المحضر</label>
                    <div class="flex gap-1">
                        <select id="current_status" name="current_status"
                                class="flex-1 border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('current_status') border-red-500 @enderror">
                            <option value="">— اختر الحالة —</option>
                            @foreach($reportStatuses as $status)
                                <option value="{{ $status }}" @selected(old('current_status') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="openLookupModal('status')" class="border border-brutal-black bg-brutal-black px-3 text-neon hover:bg-brutal-smoke font-bold" title="إضافة حالة جديدة">
                            +
                        </button>
                    </div>
                    @error('current_status')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- تاريخ فتح المحضر --}}
                <div class="flex flex-col gap-1.5">
                    <label for="open_date_time" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">تاريخ ووقت فتح المحضر</label>
                    <input id="open_date_time" type="datetime-local" name="open_date_time"
                           value="{{ old('open_date_time') }}"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('open_date_time') border-red-500 @enderror">
                    @error('open_date_time')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- تاريخ الواقعة --}}
                <div class="flex flex-col gap-1.5">
                    <label for="incident_date_time" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">تاريخ ووقت الواقعة</label>
                    <input id="incident_date_time" type="datetime-local" name="incident_date_time"
                           value="{{ old('incident_date_time') }}"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('incident_date_time') border-red-500 @enderror">
                    @error('incident_date_time')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- المحافظة --}}
                <div class="flex flex-col gap-1.5">
                    <label for="location_governorate" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">المحافظة</label>
                    <select id="location_governorate" name="location_governorate"
                            class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('location_governorate') border-red-500 @enderror">
                        <option value="">— اختر المحافظة —</option>
                        @foreach(['القاهرة','الجيزة','الإسكندرية','البحيرة','الغربية','الدقهلية','الشرقية','المنوفية','القليوبية','كفر الشيخ','دمياط','بورسعيد','الإسماعيلية','السويس','شمال سيناء','جنوب سيناء','الفيوم','بني سويف','المنيا','أسيوط','سوهاج','قنا','الأقصر','أسوان','البحر الأحمر','الوادي الجديد','مطروح'] as $gov)
                            <option value="{{ $gov }}" @selected(old('location_governorate') === $gov)>{{ $gov }}</option>
                        @endforeach
                    </select>
                    @error('location_governorate')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- اسم المحرر --}}
                <div class="flex flex-col gap-1.5 md:col-span-2 xl:col-span-1">
                    <label for="officer_name" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">اسم محرر المحضر</label>
                    <input id="officer_name" type="text" name="officer_name"
                           value="{{ old('officer_name') }}"
                           placeholder="اسم الضابط أو المحرر"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('officer_name') border-red-500 @enderror">
                    @error('officer_name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- مكان الواقعة --}}
                <div class="flex flex-col gap-1.5 md:col-span-2">
                    <label for="location_details" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">مكان الواقعة بالتفصيل</label>
                    <textarea id="location_details" name="location_details" rows="2"
                              placeholder="الشارع، الحي، المبنى، وأي تفاصيل إضافية..."
                              class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold resize-none focus:outline-none focus:border-neon @error('location_details') border-red-500 @enderror">{{ old('location_details') }}</textarea>
                    @error('location_details')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- =====================================================
             القسم 2: أطراف المحضر
        ====================================================== --}}
        <div class="brutal-card">
            <div class="flex items-center justify-between border-b border-brutal-black bg-brutal-black px-5 py-3">
                <div class="flex items-center gap-3">
                    <span class="flex h-6 w-6 items-center justify-center bg-neon text-xs font-bold text-brutal-black">٢</span>
                    <h2 class="text-sm font-bold uppercase tracking-widest text-neon">أطراف المحضر</h2>
                </div>
                <button type="button" id="add-party"
                        class="flex items-center gap-1.5 border border-neon/40 px-3 py-1.5 text-xs font-bold text-neon hover:border-neon hover:bg-neon/10 transition-colors">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    إضافة طرف
                </button>
            </div>

            <div id="parties-container" class="divide-y divide-brutal-black/10 p-5">
                {{-- الطرف الأول افتراضي --}}
                <div class="party-row pb-5">
                    @include('reports.partials.party-row', ['index' => 0, 'party' => null])
                </div>
            </div>

            <input type="hidden" name="parties_details" id="parties_json">
        </div>

        {{-- =====================================================
             القسم 3: تفاصيل البلاغ
        ====================================================== --}}
        <div class="brutal-card">
            <div class="flex items-center gap-3 border-b border-brutal-black bg-brutal-black px-5 py-3">
                <span class="flex h-6 w-6 items-center justify-center bg-neon text-xs font-bold text-brutal-black">٣</span>
                <h2 class="text-sm font-bold uppercase tracking-widest text-neon">تفاصيل ومضمون البلاغ</h2>
            </div>
            <div class="space-y-4 p-5">

                {{-- موضوع البلاغ --}}
                <div class="flex flex-col gap-1.5">
                    <label for="report_subject" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">موضوع البلاغ الرئيسي</label>
                    <input id="report_subject" type="text" name="report_subject"
                           value="{{ old('report_subject') }}"
                           placeholder="ملخص الواقعة في جملة قصيرة"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('report_subject') border-red-500 @enderror">
                    @error('report_subject')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- أقوال الأطراف --}}
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">أقوال الأطراف (سؤال وجواب)</label>
                        <button type="button" id="add-statement"
                                class="flex items-center gap-1 text-xs font-bold text-brutal-smoke/50 hover:text-brutal-black transition-colors">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            إضافة إفادة
                        </button>
                    </div>
                    <div id="statements-container" class="space-y-3">
                        <div class="statement-row border border-brutal-black/20 p-3">
                            @include('reports.partials.statement-row', ['index' => 0])
                        </div>
                    </div>
                    <input type="hidden" name="statements_details" id="statements_json">
                </div>

                {{-- الأحراز والمضبوطات --}}
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">الأحراز والمضبوطات</label>
                        <button type="button" id="add-seizure"
                                class="flex items-center gap-1 text-xs font-bold text-brutal-smoke/50 hover:text-brutal-black transition-colors">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            إضافة حرز
                        </button>
                    </div>
                    <div id="seizures-container" class="space-y-2">
                        <div class="seizure-row grid grid-cols-1 gap-2 border border-brutal-black/20 p-3 sm:grid-cols-4">
                            @include('reports.partials.seizure-row', ['index' => 0])
                        </div>
                    </div>
                    <input type="hidden" name="seizures_details" id="seizures_json">
                </div>

            </div>
        </div>

        {{-- =====================================================
             القسم 4: الإجراءات والقرارات
        ====================================================== --}}
        <div class="brutal-card">
            <div class="flex items-center gap-3 border-b border-brutal-black bg-brutal-black px-5 py-3">
                <span class="flex h-6 w-6 items-center justify-center bg-neon text-xs font-bold text-brutal-black">٤</span>
                <h2 class="text-sm font-bold uppercase tracking-widest text-neon">الإجراءات والقرارات</h2>
            </div>
            <div class="space-y-4 p-5">

                {{-- قرار النيابة --}}
                <div class="flex flex-col gap-1.5">
                    <label for="prosecution_decision" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">قرار النيابة</label>
                    <textarea id="prosecution_decision" name="prosecution_decision" rows="3"
                              placeholder="نص قرار النيابة العامة..."
                              class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold resize-y focus:outline-none focus:border-neon @error('prosecution_decision') border-red-500 @enderror">{{ old('prosecution_decision') }}</textarea>
                    @error('prosecution_decision')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- المرفقات --}}
                <div class="flex flex-col gap-1.5">
                    <label for="attachments_paths" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">
                        مسارات المرفقات (JSON)
                    </label>
                    <textarea id="attachments_paths" name="attachments_paths" rows="3"
                              placeholder='[{"name":"اسم الملف","path":"/storage/...","type":"pdf"}]'
                              class="border border-brutal-black bg-brutal-white px-3 py-2.5 font-mono text-xs resize-y focus:outline-none focus:border-neon @error('attachments_paths') border-red-500 @enderror">{{ old('attachments_paths') }}</textarea>
                    <p class="text-xs font-bold text-brutal-smoke/40">أدخل مسارات الملفات بصيغة JSON، أو اتركها فارغة.</p>
                    @error('attachments_paths')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

    </div>
</form>

@include('reports.partials.lookup-modal')
@include('reports.partials.keyboard-nav')

@push('scripts')
<script>
(function () {
    // ─── Parties ────────────────────────────────────────────────
    let partyCount = 1;

    function renderPartyRow(index) {
        return `
        <div class="party-row pb-5 pt-5 first:pt-0" data-party-index="${index}">
            <div class="mb-3 flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">الطرف رقم ${index + 1}</span>
                ${index > 0 ? `<button type="button" onclick="removeRow(this, '.party-row')" class="text-xs font-bold text-red-500 hover:text-red-700">✕ إزالة</button>` : ''}
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                ${partyField(index,'role','الصفة','مشتكي / مشتكى عليه / شاهد')}
                ${partyField(index,'full_name','الاسم الكامل','الاسم الرباعي')}
                ${partyField(index,'national_id','الرقم القومي','14 رقم')}
                ${partyField(index,'nationality','الجنسية','مصري / أجنبي')}
                ${partyField(index,'age','السن','العمر بالسنوات')}
                ${partyField(index,'occupation','المهنة','الوظيفة أو المهنة')}
                ${partyField(index,'address','محل الإقامة','العنوان الكامل')}
                ${partyField(index,'phone','رقم الهاتف','01XXXXXXXXX')}
            </div>
        </div>`;
    }

    function partyField(idx, name, label, placeholder) {
        return `
        <div class="flex flex-col gap-1">
            <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">${label}</label>
            <input type="text" data-party="${idx}" data-field="${name}" placeholder="${placeholder}"
                   class="party-input border border-brutal-black/40 bg-brutal-white px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
        </div>`;
    }

    document.getElementById('add-party').addEventListener('click', function () {
        const container = document.getElementById('parties-container');
        const div = document.createElement('div');
        div.innerHTML = renderPartyRow(partyCount++);
        container.appendChild(div.firstElementChild);
    });

    // ─── Statements ─────────────────────────────────────────────
    let stmtCount = 1;

    function renderStatementRow(index) {
        return `
        <div class="statement-row border border-brutal-black/20 p-3" data-stmt-index="${index}">
            <div class="mb-2 flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">إفادة ${index + 1}</span>
                ${index > 0 ? `<button type="button" onclick="removeRow(this, '.statement-row')" class="text-xs font-bold text-red-500">✕ إزالة</button>` : ''}
            </div>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 mb-2">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">اسم الطرف</label>
                    <input type="text" data-stmt="${index}" data-field="party" placeholder="اسم الشخص"
                           class="stmt-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">الصفة</label>
                    <input type="text" data-stmt="${index}" data-field="role" placeholder="مشتكي / شاهد / ..."
                           class="stmt-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
                </div>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">نص الإفادة (سؤال وجواب)</label>
                <textarea data-stmt="${index}" data-field="text" rows="3" placeholder="س: ... &#10;ج: ..."
                          class="stmt-textarea border border-brutal-black/30 px-2.5 py-2 text-sm font-bold resize-y focus:outline-none focus:border-neon"></textarea>
            </div>
        </div>`;
    }

    document.getElementById('add-statement').addEventListener('click', function () {
        const container = document.getElementById('statements-container');
        const div = document.createElement('div');
        div.innerHTML = renderStatementRow(stmtCount++);
        container.appendChild(div.firstElementChild);
    });

    // ─── Seizures ────────────────────────────────────────────────
    let seizeCount = 1;

    function renderSeizureRow(index) {
        return `
        <div class="seizure-row grid grid-cols-1 gap-2 border border-brutal-black/20 p-3 sm:grid-cols-4" data-seize-index="${index}">
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">اسم الحرز</label>
                <input type="text" data-seize="${index}" data-field="name" placeholder="وصف الحرز"
                       class="seize-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">الكمية</label>
                <input type="text" data-seize="${index}" data-field="quantity" placeholder="1 / طرد / ..."
                       class="seize-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">الحالة</label>
                <input type="text" data-seize="${index}" data-field="condition" placeholder="سليم / تالف / ..."
                       class="seize-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">وصف إضافي</label>
                <div class="flex gap-1">
                    <input type="text" data-seize="${index}" data-field="description" placeholder="تفاصيل..."
                           class="seize-input flex-1 border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
                    ${index > 0 ? `<button type="button" onclick="removeRow(this, '.seizure-row')" class="border border-red-400 px-2 text-xs text-red-500 hover:bg-red-50">✕</button>` : ''}
                </div>
            </div>
        </div>`;
    }

    document.getElementById('add-seizure').addEventListener('click', function () {
        const container = document.getElementById('seizures-container');
        const div = document.createElement('div');
        div.innerHTML = renderSeizureRow(seizeCount++);
        container.appendChild(div.firstElementChild);
    });

    // ─── Remove row helper ──────────────────────────────────────
    window.removeRow = function (btn, selector) {
        btn.closest(selector).remove();
    };

    // ─── Serialize to JSON on submit ────────────────────────────
    document.getElementById('report-form').addEventListener('submit', function () {

        // Parties
        const parties = [];
        document.querySelectorAll('.party-row').forEach(function (row) {
            const obj = {};
            row.querySelectorAll('.party-input').forEach(function (inp) {
                obj[inp.dataset.field] = inp.value.trim();
            });
            if (Object.values(obj).some(v => v)) parties.push(obj);
        });
        document.getElementById('parties_json').value = JSON.stringify(parties);

        // Statements
        const stmts = [];
        document.querySelectorAll('.statement-row').forEach(function (row) {
            const obj = {};
            row.querySelectorAll('.stmt-input, .stmt-textarea').forEach(function (el) {
                obj[el.dataset.field] = el.value.trim();
            });
            if (Object.values(obj).some(v => v)) stmts.push(obj);
        });
        document.getElementById('statements_json').value = JSON.stringify(stmts);

        // Seizures
        const seizures = [];
        document.querySelectorAll('.seizure-row').forEach(function (row) {
            const obj = {};
            row.querySelectorAll('.seize-input').forEach(function (inp) {
                obj[inp.dataset.field] = inp.value.trim();
            });
            if (Object.values(obj).some(v => v)) seizures.push(obj);
        });
        document.getElementById('seizures_json').value = JSON.stringify(seizures);
    });

})();
</script>
@endpush

@endsection
