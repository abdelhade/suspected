@extends('layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')
@section('page-subtitle', 'نظرة عامة على قاعدة بيانات المسجلين خطر')

@section('content')

    {{-- Quick actions --}}
    <div class="mb-6 flex flex-wrap gap-2">
        <button type="button" class="brutal-btn-primary">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            محضر جديد
        </button>
        <button type="button" class="brutal-btn-secondary">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            إضافة مسجّل
        </button>
        <button type="button" class="brutal-btn-secondary">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            بحث سريع
        </button>
    </div>

    {{-- Main stats --}}
    <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="إجمالي المسجّلين" :value="$stats['total_persons']" trend="+12 هذا الشهر" :highlight="true">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </x-stat-card>

        <x-stat-card label="مسجّل A" :value="$stats['registered_a']" trend="أولوية عالية">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </x-stat-card>

        <x-stat-card label="مسجّل B" :value="$stats['registered_b']">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </x-stat-card>

        <x-stat-card label="زوار" :value="$stats['visitors']" trend="30 يوم صلاحية">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </x-stat-card>
    </div>

    {{-- Secondary stats --}}
    <div class="mb-6 grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-6">
        @foreach([
            ['label' => 'مطلوبون', 'value' => $stats['wanted']],
            ['label' => 'موقوفون', 'value' => $stats['detained']],
            ['label' => 'محاضر معلّقة', 'value' => $stats['pending_reports']],
            ['label' => 'بانتظار اعتماد', 'value' => $stats['pending_approvals']],
            ['label' => 'محاضر الشهر', 'value' => $stats['reports_this_month']],
            ['label' => 'محاضر اليوم', 'value' => $stats['reports_today']],
        ] as $item)
            <div class="brutal-card px-3 py-2.5 text-center">
                <p class="text-2xl font-bold tabular-nums text-brutal-black">{{ number_format($item['value']) }}</p>
                <p class="mt-0.5 text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">{{ $item['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">

        {{-- Left 2/3 --}}
        <div class="space-y-4 xl:col-span-2">

            {{-- Chart --}}
            <div class="brutal-card p-4">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-widest text-brutal-black">المحاضر الشهرية</h2>
                @php $maxCount = max(array_column($monthlyReports, 'count')); @endphp
                <div class="flex items-end justify-between gap-1.5" style="height: 140px">
                    @foreach($monthlyReports as $month)
                        <div class="flex flex-1 flex-col items-center gap-1.5">
                            <span class="text-xs font-bold tabular-nums text-brutal-smoke/60">{{ $month['count'] }}</span>
                            <div class="w-full border border-brutal-black bg-neon transition-all hover:neon-glow"
                                 style="height: {{ ($month['count'] / $maxCount) * 100 }}px"></div>
                            <span class="text-xs font-bold text-brutal-smoke/40">{{ $month['month'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent reports --}}
            <div class="brutal-card">
                <div class="flex items-center justify-between border-b border-brutal-black px-4 py-3">
                    <h2 class="text-sm font-bold uppercase tracking-widest">آخر المحاضر</h2>
                    <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/40">قريباً</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm font-bold">
                        <thead>
                            <tr class="border-b border-brutal-black/20 text-xs uppercase tracking-widest text-brutal-smoke/50">
                                <th class="px-4 py-2.5 text-right font-bold">رقم المحضر</th>
                                <th class="px-4 py-2.5 text-right font-bold">نوع الجريمة</th>
                                <th class="hidden px-4 py-2.5 text-right font-bold md:table-cell">الأسلوب</th>
                                <th class="hidden px-4 py-2.5 text-right font-bold lg:table-cell">المكان</th>
                                <th class="px-4 py-2.5 text-right font-bold">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-brutal-black/10">
                            @foreach($recentReports as $report)
                                @php
                                    $statusMap = [
                                        'draft' => ['label' => 'مسودة', 'active' => false],
                                        'pending_review' => ['label' => 'بانتظار المراجعة', 'active' => true],
                                        'approved' => ['label' => 'معتمد', 'active' => false],
                                    ];
                                    $status = $statusMap[$report['status']] ?? ['label' => $report['status'], 'active' => false];
                                @endphp
                                <tr class="hover:bg-neon/10">
                                    <td class="px-4 py-2.5 font-sans text-xs text-brutal-black">{{ $report['number'] }}</td>
                                    <td class="px-4 py-2.5">{{ $report['crime_type'] }}</td>
                                    <td class="hidden px-4 py-2.5 text-brutal-smoke/60 md:table-cell">{{ $report['method'] }}</td>
                                    <td class="hidden px-4 py-2.5 text-brutal-smoke/60 lg:table-cell">{{ $report['location'] }}</td>
                                    <td class="px-4 py-2.5">
                                        <span @class([
                                            'inline-flex border px-2 py-0.5 text-xs uppercase tracking-widest',
                                            'border-brutal-black bg-neon text-brutal-black' => $status['active'],
                                            'border-brutal-black/30 text-brutal-smoke/60' => ! $status['active'],
                                        ])>{{ $status['label'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right 1/3 --}}
        <div class="space-y-4">

            {{-- Person types --}}
            <div class="brutal-card p-4">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-widest">توزيع الأنواع</h2>
                @php $totalTypes = array_sum(array_column($personTypes, 'value')); @endphp
                <div class="space-y-3">
                    @foreach($personTypes as $i => $type)
                        @php $pct = $totalTypes > 0 ? round(($type['value'] / $totalTypes) * 100) : 0; @endphp
                        <div>
                            <div class="mb-1 flex justify-between text-xs font-bold">
                                <span>{{ $type['label'] }}</span>
                                <span class="text-brutal-smoke/50">{{ number_format($type['value']) }} · {{ $pct }}%</span>
                            </div>
                            <div class="h-1.5 border border-brutal-black bg-brutal-white">
                                <div @class([
                                    'h-full',
                                    'bg-neon neon-glow' => $i === 0,
                                    'bg-brutal-black' => $i !== 0,
                                ]) style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Risk levels --}}
            <div class="brutal-card p-4">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-widest">مستويات الخطورة</h2>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($riskLevels as $i => $risk)
                        <div @class([
                            'border border-brutal-black p-3',
                            'bg-neon neon-glow' => $i === 0,
                            'bg-brutal-white' => $i !== 0,
                        ])>
                            <p @class([
                                'text-3xl font-bold tabular-nums',
                                'text-brutal-black' => $i === 0,
                                'text-brutal-black' => $i !== 0,
                            ])>{{ number_format($risk['value']) }}</p>
                            <p class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">{{ $risk['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Governorates --}}
            <div class="brutal-card p-4">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-widest">أعلى المحافظات</h2>
                <div class="space-y-2">
                    @foreach($topGovernorates as $i => $gov)
                        <div class="flex items-center gap-2 border-b border-brutal-black/10 pb-2 last:border-0 last:pb-0">
                            <span @class([
                                'flex h-6 w-6 items-center justify-center border text-xs font-bold',
                                'border-brutal-black bg-neon text-brutal-black' => $i === 0,
                                'border-brutal-black/30 text-brutal-smoke/50' => $i !== 0,
                            ])>{{ $i + 1 }}</span>
                            <span class="flex-1 text-sm font-bold">{{ $gov['governorate'] }}</span>
                            <span class="text-sm font-bold tabular-nums text-brutal-smoke/60">{{ number_format($gov['count']) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Pending approvals --}}
    <div class="mt-4 brutal-card">
        <div class="flex items-center gap-2 border-b border-brutal-black px-4 py-3">
            <h2 class="text-sm font-bold uppercase tracking-widest">بانتظار الاعتماد</h2>
            <span class="border border-brutal-black bg-neon px-2 py-0.5 text-xs font-bold tabular-nums">{{ count($pendingApprovals) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm font-bold">
                <thead>
                    <tr class="border-b border-brutal-black/20 text-xs uppercase tracking-widest text-brutal-smoke/50">
                        <th class="px-4 py-2.5 text-right font-bold">رقم الملف</th>
                        <th class="px-4 py-2.5 text-right font-bold">الاسم</th>
                        <th class="px-4 py-2.5 text-right font-bold">من → إلى</th>
                        <th class="hidden px-4 py-2.5 text-right font-bold sm:table-cell">مقدّم بواسطة</th>
                        <th class="hidden px-4 py-2.5 text-right font-bold md:table-cell">التاريخ</th>
                        <th class="px-4 py-2.5 text-right font-bold">إجراء</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brutal-black/10">
                    @foreach($pendingApprovals as $approval)
                        @php
                            $typeLabels = [
                                'visitor' => 'زائر',
                                'registered_a' => 'مسجّل A',
                                'registered_b' => 'مسجّل B',
                            ];
                        @endphp
                        <tr class="hover:bg-neon/10">
                            <td class="px-4 py-2.5 font-sans text-xs">{{ $approval['file_number'] }}</td>
                            <td class="px-4 py-2.5 font-bold">{{ $approval['full_name'] }}</td>
                            <td class="px-4 py-2.5">
                                <span class="text-brutal-smoke/60">{{ $typeLabels[$approval['person_type']] ?? $approval['person_type'] }}</span>
                                <span class="mx-1 text-brutal-smoke/30">←</span>
                                <span class="border-b border-neon">{{ $typeLabels[$approval['target_type']] ?? $approval['target_type'] }}</span>
                            </td>
                            <td class="hidden px-4 py-2.5 text-brutal-smoke/60 sm:table-cell">{{ $approval['submitted_by'] }}</td>
                            <td class="hidden px-4 py-2.5 text-brutal-smoke/40 md:table-cell">{{ $approval['submitted_at'] }}</td>
                            <td class="px-4 py-2.5">
                                <button type="button" class="brutal-btn-secondary px-3 py-1.5 text-xs">مراجعة</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
