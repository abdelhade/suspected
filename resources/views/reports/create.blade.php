@extends('layouts.app')

@section('title', 'محضر جديد')
@section('page-title', 'إنشاء محضر جديد')
@section('page-subtitle', 'تعبئة بيانات المحضر الأمني')

@section('content')

<div class="card-brutal p-3 mb-3" id="suggestions-panel" style="display:none;">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="h6 fw-bold mb-0">اقتراحات ذكية</h2>
        <span class="text-muted-brutal" style="font-size:.8rem;">يتم تحديثها أثناء الكتابة</span>
    </div>
    <div id="suggestions-content"></div>
</div>

@if($errors->any())
    <div class="alert mb-4 p-3" style="border:1px solid var(--brutal-black);background:var(--brutal-black);">
        <div class="neon-text fw-bold mb-2" style="font-size:.875rem;">يوجد أخطاء في البيانات:</div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li style="font-size:.75rem;color:var(--neon);opacity:.85;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('reports.store') }}" id="report-form">
    @csrf

    {{-- Toolbar --}}
    <div class="d-flex align-items-center justify-content-between mb-4 p-3 position-sticky top-0 bg-white"
         style="z-index:100;border-bottom:3px solid var(--brutal-black);">
        <h1 class="mb-0 fw-bold" style="font-size:1.1rem;">إنشاء محضر جديد</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.index') }}" class="btn btn-brutal-ghost px-4">← إلغاء</a>
            <button type="submit" id="submit-btn" class="btn btn-brutal-primary px-5">حفظ المحضر</button>
        </div>
    </div>

    <div class="d-flex flex-column gap-4">

        {{-- القسم 1: البيانات العامة --}}
        <div class="brutal-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="section-num">١</span> البيانات العامة للمحضر
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label for="report_number" class="form-label">رقم المحضر</label>
                        <input id="report_number" type="text" name="report_number"
                               value="{{ old('report_number') }}" placeholder="مثال: 2024/1250"
                               class="form-control @error('report_number') is-invalid @enderror">
                        @error('report_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="report_type" class="form-label">نوع المحضر</label>
                        <div class="d-flex gap-1">
                            <select id="report_type" name="report_type"
                                    class="form-select flex-grow-1 @error('report_type') is-invalid @enderror">
                                <option value="">— اختر النوع —</option>
                                @foreach($reportTypes as $type)
                                    <option value="{{ $type }}" @selected(old('report_type') === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="openLookupModal('type')"
                                    class="btn btn-brutal-primary px-3" title="إضافة نوع جديد">+</button>
                        </div>
                        @error('report_type')<div class="text-danger mt-1" style="font-size:.75rem;">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="current_status" class="form-label">حالة المحضر</label>
                        <div class="d-flex gap-1">
                            <select id="current_status" name="current_status"
                                    class="form-select flex-grow-1 @error('current_status') is-invalid @enderror">
                                <option value="">— اختر الحالة —</option>
                                @foreach($reportStatuses as $status)
                                    <option value="{{ $status }}" @selected(old('current_status') === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="openLookupModal('status')"
                                    class="btn btn-brutal-primary px-3" title="إضافة حالة جديدة">+</button>
                        </div>
                        @error('current_status')<div class="text-danger mt-1" style="font-size:.75rem;">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="open_date_time" class="form-label">تاريخ ووقت فتح المحضر</label>
                        <input id="open_date_time" type="datetime-local" name="open_date_time"
                               value="{{ old('open_date_time') }}"
                               class="form-control @error('open_date_time') is-invalid @enderror">
                        @error('open_date_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="incident_date_time" class="form-label">تاريخ ووقت الواقعة</label>
                        <input id="incident_date_time" type="datetime-local" name="incident_date_time"
                               value="{{ old('incident_date_time') }}"
                               class="form-control @error('incident_date_time') is-invalid @enderror">
                        @error('incident_date_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="location_governorate" class="form-label">المحافظة</label>
                        <select id="location_governorate" name="location_governorate"
                                class="form-select @error('location_governorate') is-invalid @enderror">
                            <option value="">— اختر المحافظة —</option>
                            @foreach(['القاهرة','الجيزة','الإسكندرية','البحيرة','الغربية','الدقهلية','الشرقية','المنوفية','القليوبية','كفر الشيخ','دمياط','بورسعيد','الإسماعيلية','السويس','شمال سيناء','جنوب سيناء','الفيوم','بني سويف','المنيا','أسيوط','سوهاج','قنا','الأقصر','أسوان','البحر الأحمر','الوادي الجديد','مطروح'] as $gov)
                                <option value="{{ $gov }}" @selected(old('location_governorate') === $gov)>{{ $gov }}</option>
                            @endforeach
                        </select>
                        @error('location_governorate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="officer_name" class="form-label">اسم محرر المحضر</label>
                        <input id="officer_name" type="text" name="officer_name"
                               value="{{ old('officer_name') }}" placeholder="اسم الضابط أو المحرر"
                               class="form-control @error('officer_name') is-invalid @enderror">
                        @error('officer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-8">
                        <label for="location_details" class="form-label">مكان الواقعة بالتفصيل</label>
                        <textarea id="location_details" name="location_details" rows="2"
                                  placeholder="الشارع، الحي، المبنى..."
                                  class="form-control @error('location_details') is-invalid @enderror">{{ old('location_details') }}</textarea>
                        @error('location_details')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- القسم 2: أطراف المحضر --}}
        <div class="brutal-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="section-num">٢</span> أطراف المحضر
                </div>
                <button type="button" id="add-party"
                        class="btn btn-sm d-flex align-items-center gap-1"
                        style="border:1px solid rgba(228,255,0,.5);color:var(--neon);font-size:.75rem;font-weight:700;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    إضافة طرف
                </button>
            </div>
            <div id="parties-container" class="p-4">
                @php
                    $parties = old('parties_details');
                    if (is_string($parties)) {
                        $parties = json_decode($parties, true);
                    }
                    $parties = is_array($parties) ? $parties : [];
                @endphp
                @if(count($parties) > 0)
                    @foreach($parties as $idx => $party)
                        <div class="party-row" data-party-index="{{ $idx }}">
                            @include('reports.partials.party-row', ['index' => $idx, 'party' => $party])
                        </div>
                    @endforeach
                @else
                    <div class="party-row">
                        @include('reports.partials.party-row', ['index' => 0, 'party' => null])
                    </div>
                @endif
            </div>
        </div>

        {{-- القسم 3: تفاصيل البلاغ --}}
        <div class="brutal-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="section-num">٣</span> تفاصيل ومضمون البلاغ
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <label for="report_subject" class="form-label">موضوع البلاغ الرئيسي</label>
                    <input id="report_subject" type="text" name="report_subject"
                           value="{{ old('report_subject') }}" placeholder="ملخص الواقعة في جملة قصيرة"
                           class="form-control @error('report_subject') is-invalid @enderror">
                    @error('report_subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- أقوال الأطراف --}}
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-label mb-0">أقوال الأطراف (سؤال وجواب)</label>
                        <button type="button" id="add-statement"
                                class="btn btn-sm text-muted-brutal d-flex align-items-center gap-1"
                                style="font-size:.75rem;font-weight:700;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            إضافة إفادة
                        </button>
                    </div>
                            <div id="statements-container">
                        @php $statements = old('statements_details') ?? []; @endphp
                        @if(is_array($statements) && count($statements) > 0)
                            @foreach($statements as $idx => $statement)
                                <div class="statement-row" data-stmt-index="{{ $idx }}">
                                    @include('reports.partials.statement-row', ['index' => $idx, 'statement' => $statement])
                                </div>
                            @endforeach
                        @else
                            <div class="statement-row">
                                @include('reports.partials.statement-row', ['index' => 0, 'statement' => null])
                            </div>
                        @endif
                    </div>
                </div>

                {{-- الأحراز --}}
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-label mb-0">الأحراز والمضبوطات</label>
                        <button type="button" id="add-seizure"
                                class="btn btn-sm text-muted-brutal d-flex align-items-center gap-1"
                                style="font-size:.75rem;font-weight:700;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            إضافة حرز
                        </button>
                    </div>
                    <div id="seizures-container">
                        @php $seizures = old('seizures_details') ?? []; @endphp
                        @if(is_array($seizures) && count($seizures) > 0)
                            @foreach($seizures as $idx => $seize)
                                <div class="seizure-row" data-seize-index="{{ $idx }}">
                                    <div class="row g-2">
                                        @include('reports.partials.seizure-row', ['index' => $idx, 'seizure' => $seize])
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="seizure-row">
                                <div class="row g-2">
                                    @include('reports.partials.seizure-row', ['index' => 0, 'seizure' => null])
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- القسم 4: الإجراءات والقرارات --}}
        <div class="brutal-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="section-num">٤</span> الإجراءات والقرارات
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="prosecution_decision" class="form-label">قرار النيابة</label>
                        <textarea id="prosecution_decision" name="prosecution_decision" rows="3"
                                  placeholder="نص قرار النيابة العامة..."
                                  class="form-control @error('prosecution_decision') is-invalid @enderror">{{ old('prosecution_decision') }}</textarea>
                        @error('prosecution_decision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label for="attachments_paths" class="form-label">مسارات المرفقات (JSON)</label>
                        <textarea id="attachments_paths" name="attachments_paths" rows="3"
                                  placeholder='[{"name":"اسم الملف","path":"/storage/...","type":"pdf"}]'
                                  class="form-control font-monospace @error('attachments_paths') is-invalid @enderror" style="font-size:.75rem;">{{ old('attachments_paths') }}</textarea>
                        <div class="text-muted-brutal mt-1" style="font-size:.7rem;">أدخل مسارات الملفات بصيغة JSON، أو اتركها فارغة.</div>
                        @error('attachments_paths')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

@include('reports.partials.lookup-modal')
@include('reports.partials.keyboard-nav')

<script>
    const suggestionsPanel = document.getElementById('suggestions-panel');
    const suggestionsContent = document.getElementById('suggestions-content');
    const form = document.getElementById('report-form');

    const triggerSuggestions = () => {
        const crimeType = document.getElementById('report_type')?.value || '';
        const crimeMethod = document.getElementById('report_subject')?.value || '';
        const location = document.getElementById('location_details')?.value || '';

        if (!crimeType && !crimeMethod && !location) {
            suggestionsPanel.style.display = 'none';
            return;
        }

        suggestionsPanel.style.display = 'block';
        suggestionsContent.innerHTML = '<div class="text-muted-brutal" style="font-size:.8rem;">جاري البحث...</div>';

        fetch('{{ route('reports.suggestions') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                crime_type: crimeType,
                crime_method: crimeMethod,
                location: location
            })
        })
            .then(response => response.json())
            .then(data => {
                let html = '';
                if (data.suspects?.length) {
                    html += '<div class="mb-3"><div class="fw-bold mb-2">الأشخاص المشتبه بهم</div>';
                    data.suspects.slice(0, 4).forEach(item => {
                        html += `<div class="border p-2 mb-2"><div class="fw-bold">${item.name || '—'}</div><div class="text-muted-brutal" style="font-size:.75rem;">${item.registration_category || ''} · ${item.reason || ''}</div></div>`;
                    });
                    html += '</div>';
                }
                if (data.weapons?.length) {
                    html += '<div><div class="fw-bold mb-2">الأسلحة المحتملة</div>';
                    data.weapons.slice(0, 4).forEach(item => {
                        html += `<div class="border p-2 mb-2"><div class="fw-bold">${item.name || '—'}</div><div class="text-muted-brutal" style="font-size:.75rem;">${item.reason || ''}</div></div>`;
                    });
                    html += '</div>';
                }
                if (!html) {
                    html = '<div class="text-muted-brutal" style="font-size:.8rem;">لا توجد اقتراحات حالياً.</div>';
                }
                suggestionsContent.innerHTML = html;
            })
            .catch(() => {
                suggestionsContent.innerHTML = '<div class="text-danger" style="font-size:.8rem;">تعذر تحميل الاقتراحات.</div>';
            });
    };

    ['report_type', 'report_subject', 'location_details'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', triggerSuggestions);
            el.addEventListener('change', triggerSuggestions);
        }
    });

    document.addEventListener('DOMContentLoaded', triggerSuggestions);
</script>

@push('scripts')
<script>
(function () {
    let partyCount = 1;
    let stmtCount  = 1;
    let seizeCount = 1;

    function partyField(idx, name, label, placeholder) {
        return `<div class="col-6 col-md-3">
            <label class="form-label">${label}</label>
            <input type="text" name="parties_details[${idx}][${name}]" data-party="${idx}" data-field="${name}" placeholder="${placeholder}"
                   class="form-control party-input">
        </div>`;
    }

    function renderPartyRow(index) {
        return `<div class="party-row" data-party-index="${index}">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-xs text-muted-brutal tracking-widest">الطرف رقم ${index + 1}</span>
                <button type="button" onclick="removeRow(this, '.party-row')"
                        class="btn btn-sm" style="color:#dc3545;font-size:.75rem;font-weight:700;">✕ إزالة</button>
            </div>
            <div class="row g-2">
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

    document.getElementById('add-party').addEventListener('click', function () {
        const container = document.getElementById('parties-container');
        const div = document.createElement('div');
        div.innerHTML = renderPartyRow(partyCount++);
        container.appendChild(div.firstElementChild);
    });

    function renderStatementRow(index) {
        return `<div class="statement-row" data-stmt-index="${index}">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-xs text-muted-brutal tracking-widest">إفادة ${index + 1}</span>
                <button type="button" onclick="removeRow(this, '.statement-row')"
                        class="btn btn-sm" style="color:#dc3545;font-size:.75rem;font-weight:700;">✕ إزالة</button>
            </div>
            <div class="row g-2 mb-2">
                <div class="col-12 col-sm-6">
                    <label class="form-label">اسم الطرف</label>
                    <input type="text" name="statements_details[${index}][party]" data-stmt="${index}" data-field="party" placeholder="اسم الشخص"
                           class="form-control stmt-input">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label">الصفة</label>
                    <input type="text" name="statements_details[${index}][role]" data-stmt="${index}" data-field="role" placeholder="مشتكي / شاهد / ..."
                           class="form-control stmt-input">
                </div>
            </div>
            <div>
                <label class="form-label">نص الإفادة (سؤال وجواب)</label>
                <textarea name="statements_details[${index}][text]" data-stmt="${index}" data-field="text" rows="3"
                          class="form-control stmt-textarea"></textarea>
            </div>
        </div>`;
    }

    document.getElementById('add-statement').addEventListener('click', function () {
        const container = document.getElementById('statements-container');
        const div = document.createElement('div');
        div.innerHTML = renderStatementRow(stmtCount++);
        container.appendChild(div.firstElementChild);
    });

    function renderSeizureRow(index) {
        return `<div class="seizure-row" data-seize-index="${index}">
            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <label class="form-label">اسم الحرز</label>
                    <input type="text" name="seizures_details[${index}][name]" data-seize="${index}" data-field="name" class="form-control seize-input" placeholder="وصف الحرز">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">الكمية</label>
                    <input type="text" name="seizures_details[${index}][quantity]" data-seize="${index}" data-field="quantity" class="form-control seize-input" placeholder="1 / طرد / ...">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">الحالة</label>
                    <input type="text" name="seizures_details[${index}][condition]" data-seize="${index}" data-field="condition" class="form-control seize-input" placeholder="سليم / تالف / ...">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">وصف إضافي</label>
                    <div class="d-flex gap-1">
                        <input type="text" name="seizures_details[${index}][description]" data-seize="${index}" data-field="description" class="form-control seize-input flex-grow-1" placeholder="تفاصيل...">
                        <button type="button" onclick="removeRow(this, '.seizure-row')"
                                class="btn btn-sm" style="border:1px solid #f87171;color:#dc3545;font-weight:700;">✕</button>
                    </div>
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

    window.removeRow = function (btn, selector) { btn.closest(selector).remove(); };
})();
</script>
@endpush

@endsection
