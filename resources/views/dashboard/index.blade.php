@extends('layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')
@section('page-subtitle', 'نظرة عامة على قاعدة بيانات المسجلين خطر')

@section('content')

{{-- Quick Actions --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('reports.create') }}" class="btn btn-brutal-primary d-flex align-items-center gap-2">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        محضر جديد
    </a>
    <a href="{{ route('suspects.create') }}" class="btn btn-brutal-secondary d-flex align-items-center gap-2">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        إضافة مسجّل
    </a>
    <a href="{{ route('reports.index') }}" class="btn btn-brutal-secondary d-flex align-items-center gap-2">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        تصفح المحاضر
    </a>
</div>

{{-- Main Stats --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <x-stat-card label="إجمالي المسجّلين" :value="$stats['total_persons']" trend="+12 هذا الشهر" :highlight="true">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </x-stat-card>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-stat-card label="مسجّل A" :value="$stats['registered_a']" trend="أولوية عالية">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-stat-card>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-stat-card label="مسجّل B" :value="$stats['registered_b']">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </x-stat-card>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-stat-card label="زوار" :value="$stats['visitors']" trend="30 يوم صلاحية">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-stat-card>
    </div>
</div>

{{-- Secondary Stats --}}
<div class="row g-2 mb-4">
    @foreach([
        ['label' => 'مطلوبون',       'value' => $stats['wanted']],
        ['label' => 'موقوفون',        'value' => $stats['detained']],
        ['label' => 'محاضر معلّقة',  'value' => $stats['pending_reports']],
        ['label' => 'بانتظار اعتماد','value' => $stats['pending_approvals']],
        ['label' => 'محاضر الشهر',   'value' => $stats['reports_this_month']],
        ['label' => 'محاضر اليوم',   'value' => $stats['reports_today']],
    ] as $item)
        <div class="col-6 col-sm-4 col-lg-2">
            <div class="brutal-card p-3 text-center">
                <div class="fw-bold" style="font-size:1.5rem;font-variant-numeric:tabular-nums;">
                    {{ number_format($item['value']) }}
                </div>
                <div class="text-xs text-muted-brutal tracking-widest mt-1">{{ $item['label'] }}</div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">

    {{-- Left 2/3 --}}
    <div class="col-12 col-xl-8">

        {{-- Chart --}}
        <div class="brutal-card p-4 mb-4">
            <div class="fw-bold tracking-widest text-xs mb-4">المحاضر الشهرية</div>
            @php $maxCount = max(array_column($monthlyReports, 'count')); @endphp
            <div class="d-flex align-items-end justify-content-between gap-1" style="height:140px;">
                @foreach($monthlyReports as $month)
                    <div class="d-flex flex-column align-items-center gap-1 flex-fill">
                        <span class="text-muted-brutal" style="font-size:.65rem;">{{ $month['count'] }}</span>
                        <div class="w-100 neon-bg" style="height:{{ ($month['count'] / $maxCount) * 100 }}px;border:1px solid var(--brutal-black);"></div>
                        <span class="text-muted-brutal" style="font-size:.65rem;">{{ $month['month'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent reports --}}
        <div class="brutal-card mb-4">
            <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-bottom:1px solid var(--brutal-black);">
                <div class="fw-bold tracking-widest text-xs">آخر المحاضر</div>
                <span class="text-muted-brutal text-xs">قريباً</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>رقم المحضر</th>
                            <th>نوع الجريمة</th>
                            <th class="d-none d-md-table-cell">الأسلوب</th>
                            <th class="d-none d-lg-table-cell">المكان</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentReports as $report)
                            @php
                                $statusMap = [
                                    'draft'          => ['label' => 'مسودة',              'active' => false],
                                    'pending_review' => ['label' => 'بانتظار المراجعة',   'active' => true],
                                    'approved'       => ['label' => 'معتمد',               'active' => false],
                                ];
                                $status = $statusMap[$report['status']] ?? ['label' => $report['status'], 'active' => false];
                            @endphp
                            <tr>
                                <td style="font-size:.75rem;">{{ $report['number'] }}</td>
                                <td class="fw-bold">{{ $report['crime_type'] }}</td>
                                <td class="d-none d-md-table-cell text-muted-brutal">{{ $report['method'] }}</td>
                                <td class="d-none d-lg-table-cell text-muted-brutal">{{ $report['location'] }}</td>
                                <td>
                                    <span class="badge-brutal {{ $status['active'] ? 'badge-neon' : 'badge-light' }}">
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Right 1/3 --}}
    <div class="col-12 col-xl-4">

        {{-- Person types --}}
        <div class="brutal-card p-4 mb-4">
            <div class="fw-bold tracking-widest text-xs mb-4">توزيع الأنواع</div>
            @php $totalTypes = array_sum(array_column($personTypes, 'value')); @endphp
            <div class="d-flex flex-column gap-3">
                @foreach($personTypes as $i => $type)
                    @php $pct = $totalTypes > 0 ? round(($type['value'] / $totalTypes) * 100) : 0; @endphp
                    <div>
                        <div class="d-flex justify-content-between text-xs fw-bold mb-1">
                            <span>{{ $type['label'] }}</span>
                            <span class="text-muted-brutal">{{ number_format($type['value']) }} · {{ $pct }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar {{ $i === 0 ? 'neon' : '' }}" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Risk levels --}}
        <div class="brutal-card p-4 mb-4">
            <div class="fw-bold tracking-widest text-xs mb-4">مستويات الخطورة</div>
            <div class="row g-2">
                @foreach($riskLevels as $i => $risk)
                    <div class="col-6">
                        <div class="p-3 {{ $i === 0 ? 'neon-bg neon-glow' : '' }}"
                             style="border:1px solid var(--brutal-black);">
                            <div class="fw-bold" style="font-size:1.6rem;font-variant-numeric:tabular-nums;">
                                {{ number_format($risk['value']) }}
                            </div>
                            <div class="text-xs text-muted-brutal tracking-widest">{{ $risk['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Top governorates --}}
        <div class="brutal-card p-4">
            <div class="fw-bold tracking-widest text-xs mb-4">أعلى المحافظات</div>
            <div>
                @foreach($topGovernorates as $i => $gov)
                    <div class="d-flex align-items-center gap-2 py-2 {{ !$loop->last ? 'border-bottom' : '' }}"
                         style="border-color:rgba(10,10,10,.08)!important;">
                        <span class="d-flex align-items-center justify-content-center fw-bold"
                              style="width:24px;height:24px;border:1px solid {{ $i === 0 ? 'var(--brutal-black)' : 'rgba(10,10,10,.25)' }};background:{{ $i === 0 ? 'var(--neon)' : 'transparent' }};font-size:.7rem;color:{{ $i === 0 ? 'var(--brutal-black)' : 'rgba(26,26,26,.45)' }};">
                            {{ $i + 1 }}
                        </span>
                        <span class="flex-grow-1 fw-bold" style="font-size:.875rem;">{{ $gov['governorate'] }}</span>
                        <span class="text-muted-brutal fw-bold" style="font-size:.875rem;">{{ number_format($gov['count']) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- Pending Approvals --}}
<div class="brutal-card mt-4">
    <div class="d-flex align-items-center gap-2 px-4 py-3" style="border-bottom:1px solid var(--brutal-black);">
        <div class="fw-bold tracking-widest text-xs">بانتظار الاعتماد</div>
        <span class="badge-brutal badge-neon">{{ count($pendingApprovals) }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>رقم الملف</th>
                    <th>الاسم</th>
                    <th>من → إلى</th>
                    <th class="d-none d-sm-table-cell">مقدّم بواسطة</th>
                    <th class="d-none d-md-table-cell">التاريخ</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingApprovals as $approval)
                    @php
                        $typeLabels = ['visitor' => 'زائر', 'registered_a' => 'مسجّل A', 'registered_b' => 'مسجّل B'];
                    @endphp
                    <tr>
                        <td style="font-size:.75rem;">{{ $approval['file_number'] }}</td>
                        <td class="fw-bold">{{ $approval['full_name'] }}</td>
                        <td>
                            <span class="text-muted-brutal">{{ $typeLabels[$approval['person_type']] ?? $approval['person_type'] }}</span>
                            <span class="mx-1 text-muted-brutal">←</span>
                            <span class="fw-bold" style="border-bottom:2px solid var(--neon);">
                                {{ $typeLabels[$approval['target_type']] ?? $approval['target_type'] }}
                            </span>
                        </td>
                        <td class="d-none d-sm-table-cell text-muted-brutal">{{ $approval['submitted_by'] }}</td>
                        <td class="d-none d-md-table-cell text-muted-brutal" style="font-size:.75rem;">{{ $approval['submitted_at'] }}</td>
                        <td>
                            <button type="button" class="btn btn-brutal-secondary btn-sm px-3">مراجعة</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
