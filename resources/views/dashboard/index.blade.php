@extends('layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')
@section('page-subtitle', 'نظرة عامة على قاعدة بيانات المسجلين خطر')

@section('content')

{{-- Quick Actions --}}
<div class="d-flex flex-wrap gap-3 mb-5">
    <a href="{{ route('reports.create') }}" class="btn btn-brutal-primary d-flex align-items-center gap-2">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        محضر جديد
    </a>
    <a href="{{ route('suspects.create') }}" class="btn btn-brutal-secondary d-flex align-items-center gap-2">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        إضافة مسجّل
    </a>
    <a href="{{ route('reports.index') }}" class="btn btn-brutal-secondary d-flex align-items-center gap-2">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        تصفح المحاضر
    </a>
</div>

{{-- Main Stats --}}
<div class="row g-4 mb-5">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card highlighted">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="stat-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="text-start">
                    <div class="stat-value kpi-animate">{{ number_format($stats['total_persons']) }}</div>
                    <div class="stat-label mt-2">إجمالي المسجّلين</div>
                    <div class="stat-trend mt-1">+12 هذا الشهر</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="stat-icon-wrapper danger">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-start">
                    <div class="stat-value kpi-animate">{{ number_format($stats['registered_a']) }}</div>
                    <div class="stat-label mt-2">مسجّل A</div>
                    <div class="stat-trend mt-1">أولوية عالية</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="stat-icon-wrapper secondary">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="text-start">
                    <div class="stat-value kpi-animate">{{ number_format($stats['registered_b']) }}</div>
                    <div class="stat-label mt-2">مسجّل B</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="stat-icon-wrapper accent">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-start">
                    <div class="stat-value kpi-animate">{{ number_format($stats['visitors']) }}</div>
                    <div class="stat-label mt-2">زوار</div>
                    <div class="stat-trend mt-1">30 يوم صلاحية</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Secondary Stats --}}
<div class="row g-3 mb-5">
    @foreach([
        ['label' => 'مطلوبون',       'value' => $stats['wanted'],       'color' => 'danger'],
        ['label' => 'موقوفون',        'value' => $stats['detained'],      'color' => 'warning'],
        ['label' => 'محاضر معلّقة',  'value' => $stats['pending_reports'], 'color' => 'secondary'],
        ['label' => 'بانتظار اعتماد','value' => $stats['pending_approvals'], 'color' => 'accent'],
        ['label' => 'محاضر الشهر',   'value' => $stats['reports_this_month'], 'color' => 'primary'],
        ['label' => 'محاضر اليوم',   'value' => $stats['reports_today'],   'color' => 'success'],
    ] as $item)
        <div class="col-6 col-sm-4 col-lg-2">
            <div class="premium-card p-4 text-center">
                <div class="fw-bold kpi-animate" style="font-size:var(--font-size-2xl);font-variant-numeric:tabular-nums;color:var(--{{ $item['color'] }});">
                    {{ number_format($item['value']) }}
                </div>
                <div class="text-xs text-muted-brutal tracking-widest mt-2">{{ $item['label'] }}</div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">

    {{-- Left 2/3 --}}
    <div class="col-12 col-xl-8">

        {{-- Chart --}}
        <div class="brutal-card p-5 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="fw-bold tracking-widest text-xs" style="color:var(--text-primary);">المحاضر الشهرية</div>
                <div class="d-flex gap-2">
                    <span class="badge-brutal badge-success">+15%</span>
                </div>
            </div>
            @php $maxCount = max(array_column($monthlyReports, 'count')) ?: 1; @endphp
            <div class="d-flex align-items-end justify-content-between gap-2" style="height:180px;">
                @foreach($monthlyReports as $index => $month)
                    <div class="d-flex flex-column align-items-center gap-2 flex-fill">
                        <span class="fw-bold" style="font-size:var(--font-size-xs);color:var(--text-secondary);">{{ $month['count'] }}</span>
                        <div class="w-100 chart-bar" style="height:{{ ($month['count'] / $maxCount) * 100 }}%;min-height:4px;"></div>
                        <span class="text-muted-brutal" style="font-size:var(--font-size-xs);">{{ $month['month'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent reports --}}
        <div class="brutal-card mb-4">
            <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-bottom:1px solid var(--border-color);">
                <div class="fw-bold tracking-widest text-xs" style="color:var(--text-primary);">آخر المحاضر</div>
                <a href="{{ route('reports.index') }}" class="btn btn-brutal-ghost" style="font-size:var(--font-size-xs);">عرض الكل</a>
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
                                    'draft'          => ['label' => 'مسودة',              'class' => 'badge-light'],
                                    'pending_review' => ['label' => 'بانتظار المراجعة',   'class' => 'badge-warning'],
                                    'approved'       => ['label' => 'معتمد',               'class' => 'badge-success'],
                                ];
                                $status = $statusMap[$report['status']] ?? ['label' => $report['status'], 'class' => 'badge-light'];
                            @endphp
                            <tr>
                                <td style="font-size:var(--font-size-sm);">{{ $report['number'] }}</td>
                                <td class="fw-semibold">{{ $report['crime_type'] }}</td>
                                <td class="d-none d-md-table-cell text-muted-brutal">{{ $report['method'] }}</td>
                                <td class="d-none d-lg-table-cell text-muted-brutal">{{ $report['location'] }}</td>
                                <td>
                                    <span class="badge-brutal {{ $status['class'] }}">
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
        <div class="brutal-card p-5 mb-4">
            <div class="fw-bold tracking-widest text-xs mb-4" style="color:var(--text-primary);">توزيع الأنواع</div>
            @php $totalTypes = array_sum(array_column($personTypes, 'value')); @endphp
            <div class="d-flex flex-column gap-4">
                @foreach($personTypes as $i => $type)
                    @php $pct = $totalTypes > 0 ? round(($type['value'] / $totalTypes) * 100) : 0; @endphp
                    <div>
                        <div class="d-flex justify-content-between text-xs fw-semibold mb-2">
                            <span style="color:var(--text-primary);">{{ $type['label'] }}</span>
                            <span class="text-muted-brutal">{{ number_format($type['value']) }} · {{ $pct }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar {{ $i === 0 ? 'neon' : ($i === 1 ? 'secondary' : '') }}" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Risk levels --}}
        <div class="brutal-card p-5 mb-4">
            <div class="fw-bold tracking-widest text-xs mb-4" style="color:var(--text-primary);">مستويات الخطورة</div>
            <div class="row g-3">
                @foreach($riskLevels as $i => $risk)
                    @php
                        $riskClass = $i === 0 ? 'high' : ($i === 1 ? 'medium' : 'low');
                    @endphp
                    <div class="col-6">
                        <div class="risk-card {{ $riskClass }}">
                            <div class="fw-bold kpi-animate" style="font-size:var(--font-size-2xl);font-variant-numeric:tabular-nums;color:var(--text-primary);">
                                {{ number_format($risk['value']) }}
                            </div>
                            <div class="text-xs text-muted-brutal tracking-widest mt-2">{{ $risk['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Top governorates --}}
        <div class="brutal-card p-5">
            <div class="fw-bold tracking-widest text-xs mb-4" style="color:var(--text-primary);">أعلى المحافظات</div>
            <div>
                @foreach($topGovernorates as $i => $gov)
                    <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}"
                         style="border-color:var(--border-light)!important;">
                        <span class="d-flex align-items-center justify-content-center fw-bold"
                              style="width:32px;height:32px;border-radius:var(--radius-md);border:1px solid {{ $i === 0 ? 'var(--primary)' : 'var(--border-color)' }};background:{{ $i === 0 ? 'var(--primary)' : 'transparent' }};font-size:var(--font-size-xs);color:{{ $i === 0 ? 'white' : 'var(--text-secondary)' }};">
                            {{ $i + 1 }}
                        </span>
                        <span class="flex-grow-1 fw-semibold" style="font-size:var(--font-size-sm);color:var(--text-primary);">{{ $gov['governorate'] }}</span>
                        <span class="text-muted-brutal fw-semibold" style="font-size:var(--font-size-sm);">{{ number_format($gov['count']) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- Pending Approvals --}}
<div class="brutal-card mt-5">
    <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom:1px solid var(--border-color);">
        <div class="fw-bold tracking-widest text-xs" style="color:var(--text-primary);">بانتظار الاعتماد</div>
        <span class="badge-brutal badge-warning">{{ count($pendingApprovals) }}</span>
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
                        <td style="font-size:var(--font-size-sm);">{{ $approval['file_number'] }}</td>
                        <td class="fw-semibold">{{ $approval['full_name'] }}</td>
                        <td>
                            <span class="text-muted-brutal">{{ $typeLabels[$approval['person_type']] ?? $approval['person_type'] }}</span>
                            <span class="mx-2 text-muted-brutal">←</span>
                            <span class="fw-semibold" style="border-bottom:2px solid var(--accent);">
                                {{ $typeLabels[$approval['target_type']] ?? $approval['target_type'] }}
                            </span>
                        </td>
                        <td class="d-none d-sm-table-cell text-muted-brutal">{{ $approval['submitted_by'] }}</td>
                        <td class="d-none d-md-table-cell text-muted-brutal" style="font-size:var(--font-size-sm);">{{ $approval['submitted_at'] }}</td>
                        <td>
                            <button type="button" class="btn btn-brutal-secondary" style="font-size:var(--font-size-xs);padding:var(--space-1) var(--space-3);">مراجعة</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
