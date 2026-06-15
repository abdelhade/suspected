@extends('layouts.app')

@section('title', 'عرض المحضر')
@section('page-title', 'تفاصيل المحضر')
@section('page-subtitle', 'رقم المحضر: ' . ($report->report_number ?? '—'))

@section('content')

@if(session('success'))
    <div class="alert p-3 mb-4 d-flex align-items-center gap-3"
         style="border:1px solid var(--brutal-black);background:var(--neon);">
        <svg width="16" height="16" fill="none" stroke="var(--brutal-black)" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="fw-bold" style="font-size:.875rem;color:var(--brutal-black);">{{ session('success') }}</span>
    </div>
@endif

{{-- Toolbar --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <a href="{{ route('reports.index') }}" class="btn btn-brutal-ghost">← عودة للقائمة</a>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.edit', $report) }}" class="btn btn-brutal-secondary d-flex align-items-center gap-2">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 112.97 2.97L7.5 19.788l-4 1 1-4 12.362-12.301z"/>
            </svg>
            تعديل المحضر
        </a>
        <form method="POST" action="{{ route('reports.destroy', $report) }}"
              onsubmit="return confirm('هل أنت متأكد من حذف هذا المحضر بشكل نهائي؟')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-brutal-ghost text-danger d-flex align-items-center gap-2">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                حذف
            </button>
        </form>
    </div>
</div>

<div class="row g-4">

    {{-- العمود الأيمن --}}
    <div class="col-12 col-xl-4">

        {{-- الحالة --}}
        <div class="brutal-card p-4 mb-4">
            <div class="text-xs text-muted-brutal tracking-widest mb-3">الحالة الحالية</div>
            @php
                $statusBadge = match($report->current_status) {
                    'مفتوح'        => 'badge-neon',
                    'محول للنيابة' => 'badge-dark',
                    default        => 'badge-light',
                };
            @endphp
            <span class="badge-brutal {{ $statusBadge }}" style="font-size:.875rem;">
                {{ $report->current_status ?? 'غير محدد' }}
            </span>
        </div>

        {{-- البيانات العامة --}}
        <div class="brutal-card mb-4">
            <div class="card-header">البيانات العامة</div>
            <div class="p-4">
                @foreach([
                    'رقم المحضر'          => $report->report_number ?? '—',
                    'النوع'               => $report->report_type ?? '—',
                    'تاريخ الفتح'         => $report->open_date_time?->format('Y-m-d H:i') ?? '—',
                    'تاريخ الواقعة'       => $report->incident_date_time?->format('Y-m-d H:i') ?? '—',
                    'المحافظة'            => $report->location_governorate ?? '—',
                    'محرر المحضر'         => $report->officer_name ?? '—',
                    'مكان الواقعة'        => $report->location_details ?? '—',
                ] as $label => $value)
                    <div class="pb-2 mb-2" style="border-bottom:1px solid rgba(10,10,10,.08);">
                        <div class="text-xs text-muted-brutal tracking-widest mb-1">{{ $label }}</div>
                        <div class="fw-bold" style="font-size:.875rem;">{{ $value }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- قرار النيابة --}}
        <div class="brutal-card mb-4">
            <div class="card-header">قرار النيابة</div>
            <div class="p-4">
                @if($report->prosecution_decision)
                    <p class="fw-bold mb-0" style="font-size:.875rem;white-space:pre-line;">{{ $report->prosecution_decision }}</p>
                @else
                    <p class="text-muted-brutal fst-italic mb-0" style="font-size:.75rem;">لم يتم تدوين قرار النيابة بعد.</p>
                @endif
            </div>
        </div>

        {{-- المرفقات --}}
        <div class="brutal-card">
            <div class="card-header">المرفقات الرقمية</div>
            <div class="p-4">
                @if(is_array($report->attachments_paths) && count($report->attachments_paths) > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach($report->attachments_paths as $attachment)
                            <li class="d-flex align-items-center gap-2 mb-2">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="opacity:.5;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                <span class="fw-bold" style="font-size:.875rem;">{{ $attachment['name'] ?? 'ملف' }}</span>
                                <span class="text-muted-brutal text-xs">{{ $attachment['type'] ?? '' }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted-brutal fst-italic mb-0" style="font-size:.75rem;">لا توجد مرفقات مسجلة.</p>
                @endif
            </div>
        </div>

    </div>

    {{-- العمود الأيسر --}}
    <div class="col-12 col-xl-8">

        {{-- موضوع البلاغ --}}
        <div class="brutal-card mb-4">
            <div class="card-header">موضوع البلاغ</div>
            <div class="p-4">
                <p class="fw-bold mb-0" style="font-size:1.05rem;">{{ $report->report_subject ?? '—' }}</p>
            </div>
        </div>

        {{-- أطراف المحضر --}}
        <div class="brutal-card mb-4">
            <div class="card-header">
                أطراف المحضر ({{ is_array($report->parties_details) ? count($report->parties_details) : 0 }})
            </div>
            <div class="table-responsive">
                @if(is_array($report->parties_details) && count($report->parties_details) > 0)
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>الصفة</th>
                                <th>الاسم الكامل</th>
                                <th>الرقم القومي / الجنسية</th>
                                <th>السن / المهنة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report->parties_details as $party)
                                <tr>
                                    <td><span class="badge-brutal badge-light">{{ $party['role'] ?? '—' }}</span></td>
                                    <td class="fw-bold">{{ $party['full_name'] ?? '—' }}</td>
                                    <td>
                                        <div>{{ $party['national_id'] ?? '—' }}</div>
                                        <div class="text-muted-brutal text-xs">{{ $party['nationality'] ?? '—' }}</div>
                                    </td>
                                    <td>
                                        <div>{{ isset($party['age']) ? $party['age'] . ' سنة' : '—' }}</div>
                                        <div class="text-muted-brutal text-xs">{{ $party['occupation'] ?? '—' }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-4 text-center text-muted-brutal fst-italic" style="font-size:.875rem;">لا توجد بيانات لأطراف المحضر.</div>
                @endif
            </div>
        </div>

        {{-- أقوال الأطراف --}}
        <div class="brutal-card mb-4">
            <div class="card-header">أقوال الأطراف (سؤال وجواب)</div>
            @if(is_array($report->statements_details) && count($report->statements_details) > 0)
                @foreach($report->statements_details as $stmt)
                    <div class="p-4" style="border-bottom:1px solid rgba(10,10,10,.08);">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="fw-bold" style="font-size:.875rem;">{{ $stmt['party'] ?? 'مجهول' }}</span>
                            <span class="badge-brutal badge-light" style="font-size:.65rem;">{{ $stmt['role'] ?? '—' }}</span>
                        </div>
                        <div class="ps-3" style="border-right:3px solid var(--neon);">
                            <p class="fw-bold mb-0 text-muted-brutal" style="font-size:.875rem;white-space:pre-line;">{{ $stmt['text'] ?? '—' }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-4 text-center text-muted-brutal fst-italic" style="font-size:.875rem;">لا توجد إفادات مسجلة.</div>
            @endif
        </div>

        {{-- الأحراز والمضبوطات --}}
        <div class="brutal-card">
            <div class="card-header">الأحراز والمضبوطات</div>
            <div class="table-responsive">
                @if(is_array($report->seizures_details) && count($report->seizures_details) > 0)
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>الحرز</th>
                                <th>الكمية</th>
                                <th>الحالة</th>
                                <th>وصف إضافي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report->seizures_details as $seizure)
                                <tr>
                                    <td class="fw-bold">{{ $seizure['name'] ?? '—' }}</td>
                                    <td class="text-muted-brutal">{{ $seizure['quantity'] ?? '—' }}</td>
                                    <td class="text-muted-brutal">{{ $seizure['condition'] ?? '—' }}</td>
                                    <td class="text-muted-brutal">{{ $seizure['description'] ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-4 text-center text-muted-brutal fst-italic" style="font-size:.875rem;">لا توجد أحراز مضبوطة.</div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection
