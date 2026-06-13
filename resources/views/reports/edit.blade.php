@extends('layouts.app')

@section('title', 'تعديل المحضر')
@section('page-title', 'تعديل المحضر')
@section('page-subtitle', 'تحديث بيانات المحضر رقم: ' . ($report->report_number ?? '—'))

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

<form method="POST" action="{{ route('reports.update', $report) }}" id="report-form">
    @csrf
    @method('PUT')

    {{-- Toolbar --}}
    <div class="mb-5 flex items-center justify-between sticky top-0 z-10 bg-brutal-white/90 backdrop-blur-sm p-3 border-b-4 border-brutal-black">
        <h1 class="text-xl font-black uppercase text-brutal-black">تعديل محضر: {{ $report->report_number }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('reports.index') }}" class="brutal-btn-ghost px-5 py-2">
                ← إلغاء
            </a>
            <button type="submit" id="submit-btn" class="brutal-btn-primary px-8 py-2">
                حفظ التعديلات
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
                           value="{{ old('report_number', $report->report_number) }}"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('report_number') border-red-500 @enderror">
                </div>

                {{-- نوع المحضر --}}
                <div class="flex flex-col gap-1.5">
                    <label for="report_type" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">نوع المحضر</label>
                    <div class="flex gap-1">
                        <select id="report_type" name="report_type"
                                class="flex-1 border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('report_type') border-red-500 @enderror">
                            <option value="">— اختر النوع —</option>
                            @foreach($reportTypes as $type)
                                <option value="{{ $type }}" @selected(old('report_type', $report->report_type) === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="openLookupModal('type')" class="border border-brutal-black bg-brutal-black px-3 text-neon hover:bg-brutal-smoke font-bold" title="إضافة نوع جديد">
                            +
                        </button>
                    </div>
                </div>

                {{-- حالة المحضر --}}
                <div class="flex flex-col gap-1.5">
                    <label for="current_status" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">حالة المحضر</label>
                    <div class="flex gap-1">
                        <select id="current_status" name="current_status"
                                class="flex-1 border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('current_status') border-red-500 @enderror">
                            <option value="">— اختر الحالة —</option>
                            @foreach($reportStatuses as $status)
                                <option value="{{ $status }}" @selected(old('current_status', $report->current_status) === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="openLookupModal('status')" class="border border-brutal-black bg-brutal-black px-3 text-neon hover:bg-brutal-smoke font-bold" title="إضافة حالة جديدة">
                            +
                        </button>
                    </div>
                </div>

                {{-- تاريخ الفتح --}}
                <div class="flex flex-col gap-1.5">
                    <label for="open_date_time" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">تاريخ ووقت الفتح</label>
                    <input id="open_date_time" type="datetime-local" name="open_date_time"
                           value="{{ old('open_date_time', $report->open_date_time?->format('Y-m-d\TH:i')) }}"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('open_date_time') border-red-500 @enderror">
                </div>

                {{-- تاريخ الواقعة --}}
                <div class="flex flex-col gap-1.5">
                    <label for="incident_date_time" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">تاريخ ووقت الواقعة</label>
                    <input id="incident_date_time" type="datetime-local" name="incident_date_time"
                           value="{{ old('incident_date_time', $report->incident_date_time?->format('Y-m-d\TH:i')) }}"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('incident_date_time') border-red-500 @enderror">
                </div>

                {{-- المحافظة --}}
                <div class="flex flex-col gap-1.5">
                    <label for="location_governorate" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">المحافظة</label>
                    <select id="location_governorate" name="location_governorate"
                            class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('location_governorate') border-red-500 @enderror">
                        <option value="">— اختر المحافظة —</option>
                        @foreach(['القاهرة','الجيزة','الإسكندرية','البحيرة','الغربية','الدقهلية','الشرقية','المنوفية','القليوبية','كفر الشيخ','دمياط','بورسعيد','الإسماعيلية','السويس','شمال سيناء','جنوب سيناء','الفيوم','بني سويف','المنيا','أسيوط','سوهاج','قنا','الأقصر','أسوان','البحر الأحمر','الوادي الجديد','مطروح'] as $gov)
                            <option value="{{ $gov }}" @selected(old('location_governorate', $report->location_governorate) === $gov)>{{ $gov }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- المحرر --}}
                <div class="flex flex-col gap-1.5 md:col-span-2 xl:col-span-1">
                    <label for="officer_name" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">اسم المحرر</label>
                    <input id="officer_name" type="text" name="officer_name"
                           value="{{ old('officer_name', $report->officer_name) }}"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('officer_name') border-red-500 @enderror">
                </div>

                {{-- مكان الواقعة --}}
                <div class="flex flex-col gap-1.5 md:col-span-2">
                    <label for="location_details" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">مكان الواقعة بالتفصيل</label>
                    <textarea id="location_details" name="location_details" rows="2"
                              class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold resize-none focus:outline-none focus:border-neon @error('location_details') border-red-500 @enderror">{{ old('location_details', $report->location_details) }}</textarea>
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
                <button type="button" id="add-party" class="flex items-center gap-1.5 border border-neon/40 px-3 py-1.5 text-xs font-bold text-neon hover:border-neon hover:bg-neon/10">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> إضافة طرف
                </button>
            </div>
            <div id="parties-container" class="divide-y divide-brutal-black/10 p-5">
                @php $parties = old('parties_details') ? json_decode(old('parties_details'), true) : $report->parties_details; @endphp
                @if(is_array($parties) && count($parties) > 0)
                    @foreach($parties as $idx => $party)
                        <div class="party-row pb-5 pt-5 first:pt-0" data-party-index="{{ $idx }}">
                            <div class="mb-3 flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">الطرف رقم {{ $idx + 1 }}</span>
                                <button type="button" onclick="removeRow(this, '.party-row')" class="text-xs font-bold text-red-500 hover:text-red-700">✕ إزالة</button>
                            </div>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                @foreach([
                                    'role'=>'الصفة','full_name'=>'الاسم الكامل','national_id'=>'الرقم القومي',
                                    'nationality'=>'الجنسية','age'=>'السن','occupation'=>'المهنة',
                                    'address'=>'محل الإقامة','phone'=>'رقم الهاتف'
                                ] as $field => $label)
                                    <div class="flex flex-col gap-1">
                                        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">{{ $label }}</label>
                                        <input type="text" data-party="{{ $idx }}" data-field="{{ $field }}" value="{{ $party[$field] ?? '' }}"
                                               class="party-input border border-brutal-black/40 bg-brutal-white px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <input type="hidden" name="parties_details" id="parties_json">
        </div>

        {{-- =====================================================
             القسم 3: تفاصيل البلاغ
        ====================================================== --}}
        <div class="brutal-card">
            <div class="flex items-center gap-3 border-b border-brutal-black bg-brutal-black px-5 py-3">
                <span class="flex h-6 w-6 items-center justify-center bg-neon text-xs font-bold text-brutal-black">٣</span>
                <h2 class="text-sm font-bold uppercase tracking-widest text-neon">تفاصيل البلاغ</h2>
            </div>
            <div class="space-y-4 p-5">
                <div class="flex flex-col gap-1.5">
                    <label for="report_subject" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">موضوع البلاغ</label>
                    <input id="report_subject" type="text" name="report_subject" value="{{ old('report_subject', $report->report_subject) }}"
                           class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-neon @error('report_subject') border-red-500 @enderror">
                </div>

                {{-- الأقوال --}}
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">الأقوال (س/ج)</label>
                        <button type="button" id="add-statement" class="flex items-center gap-1 text-xs font-bold text-brutal-smoke/50 hover:text-brutal-black">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> إضافة إفادة
                        </button>
                    </div>
                    <div id="statements-container" class="space-y-3">
                        @php $stmts = old('statements_details') ? json_decode(old('statements_details'), true) : $report->statements_details; @endphp
                        @if(is_array($stmts))
                            @foreach($stmts as $idx => $stmt)
                                <div class="statement-row border border-brutal-black/20 p-3" data-stmt-index="{{ $idx }}">
                                    <div class="mb-2 flex items-center justify-between">
                                        <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">إفادة {{ $idx + 1 }}</span>
                                        <button type="button" onclick="removeRow(this, '.statement-row')" class="text-xs font-bold text-red-500">✕ إزالة</button>
                                    </div>
                                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 mb-2">
                                        <div class="flex flex-col gap-1">
                                            <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">الطرف</label>
                                            <input type="text" data-stmt="{{ $idx }}" data-field="party" value="{{ $stmt['party'] ?? '' }}"
                                                   class="stmt-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">الصفة</label>
                                            <input type="text" data-stmt="{{ $idx }}" data-field="role" value="{{ $stmt['role'] ?? '' }}"
                                                   class="stmt-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">النص</label>
                                        <textarea data-stmt="{{ $idx }}" data-field="text" rows="3"
                                                  class="stmt-textarea border border-brutal-black/30 px-2.5 py-2 text-sm font-bold resize-y focus:outline-none focus:border-neon">{{ $stmt['text'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <input type="hidden" name="statements_details" id="statements_json">
                </div>

                {{-- الأحراز --}}
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">الأحراز والمضبوطات</label>
                        <button type="button" id="add-seizure" class="flex items-center gap-1 text-xs font-bold text-brutal-smoke/50 hover:text-brutal-black">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> إضافة حرز
                        </button>
                    </div>
                    <div id="seizures-container" class="space-y-2">
                        @php $seizures = old('seizures_details') ? json_decode(old('seizures_details'), true) : $report->seizures_details; @endphp
                        @if(is_array($seizures))
                            @foreach($seizures as $idx => $seize)
                                <div class="seizure-row grid grid-cols-1 gap-2 border border-brutal-black/20 p-3 sm:grid-cols-4" data-seize-index="{{ $idx }}">
                                    @foreach(['name'=>'اسم الحرز','quantity'=>'الكمية','condition'=>'الحالة','description'=>'وصف إضافي'] as $field => $label)
                                        <div class="flex flex-col gap-1">
                                            <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">{{ $label }}</label>
                                            <div class="flex gap-1">
                                                <input type="text" data-seize="{{ $idx }}" data-field="{{ $field }}" value="{{ $seize[$field] ?? '' }}"
                                                       class="seize-input flex-1 border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
                                                @if($field === 'description')
                                                    <button type="button" onclick="removeRow(this, '.seizure-row')" class="border border-red-400 px-2 text-xs text-red-500 hover:bg-red-50">✕</button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <input type="hidden" name="seizures_details" id="seizures_json">
                </div>
            </div>
        </div>

        {{-- =====================================================
             القسم 4: القرارات
        ====================================================== --}}
        <div class="brutal-card">
            <div class="flex items-center gap-3 border-b border-brutal-black bg-brutal-black px-5 py-3">
                <span class="flex h-6 w-6 items-center justify-center bg-neon text-xs font-bold text-brutal-black">٤</span>
                <h2 class="text-sm font-bold uppercase tracking-widest text-neon">الإجراءات والقرارات</h2>
            </div>
            <div class="space-y-4 p-5">
                <div class="flex flex-col gap-1.5">
                    <label for="prosecution_decision" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">قرار النيابة</label>
                    <textarea id="prosecution_decision" name="prosecution_decision" rows="3"
                              class="border border-brutal-black bg-brutal-white px-3 py-2.5 text-sm font-bold resize-y focus:outline-none focus:border-neon @error('prosecution_decision') border-red-500 @enderror">{{ old('prosecution_decision', $report->prosecution_decision) }}</textarea>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label for="attachments_paths" class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">مسارات المرفقات (JSON)</label>
                    <textarea id="attachments_paths" name="attachments_paths" rows="3"
                              class="border border-brutal-black bg-brutal-white px-3 py-2.5 font-mono text-xs resize-y focus:outline-none focus:border-neon @error('attachments_paths') border-red-500 @enderror">{{ old('attachments_paths', is_array($report->attachments_paths) ? json_encode($report->attachments_paths, JSON_UNESCAPED_UNICODE) : $report->attachments_paths) }}</textarea>
                </div>
    </div>
</form>

@include('reports.partials.lookup-modal')
@include('reports.partials.keyboard-nav')

@push('scripts')
<script>
(function () {
    let partyCount = document.querySelectorAll('.party-row').length;
    let stmtCount = document.querySelectorAll('.statement-row').length;
    let seizeCount = document.querySelectorAll('.seizure-row').length;

    function renderPartyRow(index) {
        return `
        <div class="party-row pb-5 pt-5 first:pt-0" data-party-index="${index}">
            <div class="mb-3 flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">الطرف رقم ${index + 1}</span>
                <button type="button" onclick="removeRow(this, '.party-row')" class="text-xs font-bold text-red-500 hover:text-red-700">✕ إزالة</button>
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                ${partyField(index,'role','الصفة')}
                ${partyField(index,'full_name','الاسم الكامل')}
                ${partyField(index,'national_id','الرقم القومي')}
                ${partyField(index,'nationality','الجنسية')}
                ${partyField(index,'age','السن')}
                ${partyField(index,'occupation','المهنة')}
                ${partyField(index,'address','محل الإقامة')}
                ${partyField(index,'phone','رقم الهاتف')}
            </div>
        </div>`;
    }

    function partyField(idx, name, label) {
        return `
        <div class="flex flex-col gap-1">
            <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">${label}</label>
            <input type="text" data-party="${idx}" data-field="${name}" class="party-input border border-brutal-black/40 bg-brutal-white px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
        </div>`;
    }

    document.getElementById('add-party').addEventListener('click', function () {
        const container = document.getElementById('parties-container');
        const div = document.createElement('div');
        div.innerHTML = renderPartyRow(partyCount++);
        container.appendChild(div.firstElementChild);
    });

    function renderStatementRow(index) {
        return `
        <div class="statement-row border border-brutal-black/20 p-3" data-stmt-index="${index}">
            <div class="mb-2 flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">إفادة ${index + 1}</span>
                <button type="button" onclick="removeRow(this, '.statement-row')" class="text-xs font-bold text-red-500">✕ إزالة</button>
            </div>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 mb-2">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">اسم الطرف</label>
                    <input type="text" data-stmt="${index}" data-field="party" class="stmt-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">الصفة</label>
                    <input type="text" data-stmt="${index}" data-field="role" class="stmt-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold focus:outline-none focus:border-neon">
                </div>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">نص الإفادة</label>
                <textarea data-stmt="${index}" data-field="text" rows="3" class="stmt-textarea border border-brutal-black/30 px-2.5 py-2 text-sm font-bold resize-y focus:outline-none focus:border-neon"></textarea>
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
        return `
        <div class="seizure-row grid grid-cols-1 gap-2 border border-brutal-black/20 p-3 sm:grid-cols-4" data-seize-index="${index}">
            <div class="flex flex-col gap-1"><label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">الاسم</label><input type="text" data-seize="${index}" data-field="name" class="seize-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold"></div>
            <div class="flex flex-col gap-1"><label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">الكمية</label><input type="text" data-seize="${index}" data-field="quantity" class="seize-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold"></div>
            <div class="flex flex-col gap-1"><label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">الحالة</label><input type="text" data-seize="${index}" data-field="condition" class="seize-input border border-brutal-black/30 px-2.5 py-2 text-sm font-bold"></div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">الوصف</label>
                <div class="flex gap-1">
                    <input type="text" data-seize="${index}" data-field="description" class="seize-input flex-1 border border-brutal-black/30 px-2.5 py-2 text-sm font-bold">
                    <button type="button" onclick="removeRow(this, '.seizure-row')" class="border border-red-400 px-2 text-xs text-red-500 hover:bg-red-50">✕</button>
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

    document.getElementById('report-form').addEventListener('submit', function () {
        const p=[]; document.querySelectorAll('.party-row').forEach(r=>{let o={}; r.querySelectorAll('.party-input').forEach(i=>o[i.dataset.field]=i.value.trim()); if(Object.values(o).some(v=>v)) p.push(o);}); document.getElementById('parties_json').value = JSON.stringify(p);
        const s=[]; document.querySelectorAll('.statement-row').forEach(r=>{let o={}; r.querySelectorAll('.stmt-input, .stmt-textarea').forEach(i=>o[i.dataset.field]=i.value.trim()); if(Object.values(o).some(v=>v)) s.push(o);}); document.getElementById('statements_json').value = JSON.stringify(s);
        const z=[]; document.querySelectorAll('.seizure-row').forEach(r=>{let o={}; r.querySelectorAll('.seize-input').forEach(i=>o[i.dataset.field]=i.value.trim()); if(Object.values(o).some(v=>v)) z.push(o);}); document.getElementById('seizures_json').value = JSON.stringify(z);
    });
})();
</script>
@endpush
@endsection
