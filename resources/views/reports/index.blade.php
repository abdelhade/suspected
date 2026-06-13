@extends('layouts.app')

@section('title', 'المحاضر')
@section('page-title', 'المحاضر')
@section('page-subtitle', 'قائمة جميع المحاضر المسجّلة في النظام')

@section('content')

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 border border-brutal-black bg-neon px-4 py-3 neon-glow">
            <svg class="h-4 w-4 shrink-0 text-brutal-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-sm font-bold text-brutal-black">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('reports.create') }}" class="brutal-btn-primary">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            محضر جديد
        </a>

        {{-- Search & Filters --}}
        <form method="GET" action="{{ route('reports.index') }}"
              class="flex flex-wrap items-center gap-2">

            {{-- Search box --}}
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="بحث برقم أو موضوع أو محرر..."
                       class="border border-brutal-black bg-brutal-white py-2 pr-9 pl-3 text-sm font-bold placeholder:text-brutal-smoke/40 focus:outline-none focus:border-neon focus:bg-neon/5 w-64">
                <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 h-4 w-4 text-brutal-smoke/40 pointer-events-none"
                     fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- Type filter --}}
            <select name="report_type"
                    class="border border-brutal-black bg-brutal-white px-3 py-2 text-sm font-bold focus:outline-none focus:border-neon">
                <option value="">كل الأنواع</option>
                @foreach($reportTypes as $type)
                    <option value="{{ $type }}" @selected(request('report_type') === $type)>{{ $type }}</option>
                @endforeach
            </select>

            {{-- Status filter --}}
            <select name="current_status"
                    class="border border-brutal-black bg-brutal-white px-3 py-2 text-sm font-bold focus:outline-none focus:border-neon">
                <option value="">كل الحالات</option>
                @foreach($reportStatuses as $status)
                    <option value="{{ $status }}" @selected(request('current_status') === $status)>{{ $status }}</option>
                @endforeach
            </select>

            <button type="submit" class="brutal-btn-secondary px-3 py-2">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                تصفية
            </button>

            @if(request()->hasAny(['search','report_type','current_status','location_governorate']))
                <a href="{{ route('reports.index') }}"
                   class="brutal-btn-ghost px-3 py-2 text-brutal-smoke/60 hover:text-brutal-black">
                    مسح الفلاتر
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="brutal-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm font-bold">
                <thead>
                    <tr class="border-b border-brutal-black bg-brutal-black text-xs uppercase tracking-widest text-neon">
                        <th class="px-4 py-3 text-right font-bold">#</th>
                        <th class="px-4 py-3 text-right font-bold">رقم المحضر</th>
                        <th class="px-4 py-3 text-right font-bold">النوع</th>
                        <th class="hidden px-4 py-3 text-right font-bold md:table-cell">الموضوع</th>
                        <th class="hidden px-4 py-3 text-right font-bold lg:table-cell">المحافظة</th>
                        <th class="hidden px-4 py-3 text-right font-bold xl:table-cell">المحرر</th>
                        <th class="hidden px-4 py-3 text-right font-bold lg:table-cell">تاريخ الفتح</th>
                        <th class="px-4 py-3 text-right font-bold">الحالة</th>
                        <th class="px-4 py-3 text-center font-bold">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brutal-black/10">
                    @forelse($reports as $report)
                        @php
                            $statusColors = [
                                'مفتوح'          => 'border-brutal-black bg-neon text-brutal-black neon-glow',
                                'محول للنيابة'   => 'border-brutal-black bg-brutal-black text-neon',
                                'محفوظ'          => 'border-brutal-black/40 text-brutal-smoke/60',
                                'مغلق'           => 'border-brutal-black/30 text-brutal-smoke/40',
                            ];
                            $statusClass = $statusColors[$report->current_status] ?? 'border-brutal-black/30 text-brutal-smoke/60';
                        @endphp
                        <tr class="hover:bg-neon/8 transition-colors">
                            <td class="px-4 py-3 text-brutal-smoke/40 text-xs tabular-nums">{{ $report->id }}</td>
                            <td class="px-4 py-3">
                                <span class="font-bold tracking-wide text-brutal-black">
                                    {{ $report->report_number ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex border border-brutal-black/30 px-2 py-0.5 text-xs uppercase tracking-widest text-brutal-smoke/70">
                                    {{ $report->report_type ?? '—' }}
                                </span>
                            </td>
                            <td class="hidden px-4 py-3 text-brutal-smoke/70 md:table-cell max-w-xs truncate">
                                {{ Str::limit($report->report_subject, 40) ?? '—' }}
                            </td>
                            <td class="hidden px-4 py-3 text-brutal-smoke/70 lg:table-cell">
                                {{ $report->location_governorate ?? '—' }}
                            </td>
                            <td class="hidden px-4 py-3 text-brutal-smoke/70 xl:table-cell">
                                {{ $report->officer_name ?? '—' }}
                            </td>
                            <td class="hidden px-4 py-3 text-brutal-smoke/50 text-xs tabular-nums lg:table-cell">
                                {{ $report->open_date_time?->format('Y-m-d') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex border px-2 py-0.5 text-xs font-bold uppercase tracking-widest {{ $statusClass }}">
                                    {{ $report->current_status ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- عرض --}}
                                    <a href="{{ route('reports.show', $report) }}"
                                       title="عرض"
                                       class="flex h-7 w-7 items-center justify-center border border-brutal-black/30 text-brutal-smoke/60 hover:border-brutal-black hover:bg-neon hover:text-brutal-black transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    {{-- تعديل --}}
                                    <a href="{{ route('reports.edit', $report) }}"
                                       title="تعديل"
                                       class="flex h-7 w-7 items-center justify-center border border-brutal-black/30 text-brutal-smoke/60 hover:border-brutal-black hover:bg-brutal-black hover:text-neon transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 112.97 2.97L7.5 19.788l-4 1 1-4 12.362-12.301z"/>
                                        </svg>
                                    </a>
                                    {{-- حذف --}}
                                    <form method="POST" action="{{ route('reports.destroy', $report) }}"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا المحضر؟')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="حذف"
                                                class="flex h-7 w-7 items-center justify-center border border-brutal-black/30 text-brutal-smoke/40 hover:border-red-600 hover:bg-red-600 hover:text-white transition-colors">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center border border-brutal-black/20">
                                        <svg class="h-6 w-6 text-brutal-smoke/30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-brutal-smoke/40">لا توجد محاضر مطابقة</p>
                                    <a href="{{ route('reports.create') }}" class="brutal-btn-primary px-3 py-2 text-xs">
                                        إضافة أول محضر
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($reports->hasPages())
            <div class="border-t border-brutal-black px-4 py-3">
                <div class="flex items-center justify-between gap-4">
                    <span class="text-xs font-bold text-brutal-smoke/50">
                        عرض {{ $reports->firstItem() }}–{{ $reports->lastItem() }} من {{ $reports->total() }} محضر
                    </span>
                    <div class="flex items-center gap-1">
                        {{-- Previous --}}
                        @if($reports->onFirstPage())
                            <span class="flex h-8 w-8 items-center justify-center border border-brutal-black/20 text-brutal-smoke/30 cursor-not-allowed">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        @else
                            <a href="{{ $reports->previousPageUrl() }}"
                               class="flex h-8 w-8 items-center justify-center border border-brutal-black hover:bg-neon transition-colors">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endif

                        <span class="px-3 text-sm font-bold tabular-nums">
                            {{ $reports->currentPage() }} / {{ $reports->lastPage() }}
                        </span>

                        {{-- Next --}}
                        @if($reports->hasMorePages())
                            <a href="{{ $reports->nextPageUrl() }}"
                               class="flex h-8 w-8 items-center justify-center border border-brutal-black hover:bg-neon transition-colors">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        @else
                            <span class="flex h-8 w-8 items-center justify-center border border-brutal-black/20 text-brutal-smoke/30 cursor-not-allowed">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
