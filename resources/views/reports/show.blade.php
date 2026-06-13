@extends('layouts.app')

@section('title', 'عرض المحضر')
@section('page-title', 'تفاصيل المحضر')
@section('page-subtitle', 'رقم المحضر: ' . ($report->report_number ?? '—'))

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
        <a href="{{ route('reports.index') }}" class="brutal-btn-ghost px-4 py-2 text-brutal-smoke/60">
            ← عودة للقائمة
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('reports.edit', $report) }}" class="brutal-btn-secondary px-4 py-2">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 112.97 2.97L7.5 19.788l-4 1 1-4 12.362-12.301z"/>
                </svg>
                تعديل المحضر
            </a>
            <form method="POST" action="{{ route('reports.destroy', $report) }}"
                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المحضر بشكل نهائي؟')">
                @csrf @method('DELETE')
                <button type="submit" class="brutal-btn-ghost text-red-600 hover:bg-red-600 hover:text-white px-4 py-2">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    حذف
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">

        {{-- العمود الأيمن (البيانات الأساسية) --}}
        <div class="space-y-5 xl:col-span-1">

            {{-- بطاقة الحالة --}}
            <div class="brutal-card p-5">
                <h2 class="mb-4 text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">الحالة الحالية</h2>
                @php
                    $statusColors = [
                        'مفتوح'          => 'bg-neon text-brutal-black border-brutal-black neon-glow',
                        'محول للنيابة'   => 'bg-brutal-black text-neon border-brutal-black',
                        'محفوظ'          => 'bg-brutal-white text-brutal-smoke/60 border-brutal-black/30',
                        'مغلق'           => 'bg-brutal-white text-brutal-smoke/40 border-brutal-black/20',
                    ];
                    $statusClass = $statusColors[$report->current_status] ?? 'bg-brutal-white text-brutal-smoke/60 border-brutal-black/30';
                @endphp
                <div class="inline-flex border px-4 py-2 text-sm font-bold uppercase tracking-widest {{ $statusClass }}">
                    {{ $report->current_status ?? 'غير محدد' }}
                </div>
            </div>

            {{-- البيانات العامة --}}
            <div class="brutal-card">
                <div class="border-b border-brutal-black bg-brutal-black px-4 py-2.5">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-neon">البيانات العامة</h2>
                </div>
                <div class="divide-y divide-brutal-black/10 p-4">
                    <div class="py-2.5 flex flex-col gap-1 first:pt-0 last:pb-0">
                        <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">رقم المحضر</span>
                        <span class="text-sm font-bold">{{ $report->report_number ?? '—' }}</span>
                    </div>
                    <div class="py-2.5 flex flex-col gap-1 first:pt-0 last:pb-0">
                        <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">النوع</span>
                        <span class="text-sm font-bold">{{ $report->report_type ?? '—' }}</span>
                    </div>
                    <div class="py-2.5 flex flex-col gap-1 first:pt-0 last:pb-0">
                        <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">تاريخ الفتح</span>
                        <span class="text-sm font-bold tabular-nums" dir="ltr">{{ $report->open_date_time?->format('Y-m-d H:i') ?? '—' }}</span>
                    </div>
                    <div class="py-2.5 flex flex-col gap-1 first:pt-0 last:pb-0">
                        <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">تاريخ الواقعة</span>
                        <span class="text-sm font-bold tabular-nums" dir="ltr">{{ $report->incident_date_time?->format('Y-m-d H:i') ?? '—' }}</span>
                    </div>
                    <div class="py-2.5 flex flex-col gap-1 first:pt-0 last:pb-0">
                        <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">المحافظة</span>
                        <span class="text-sm font-bold">{{ $report->location_governorate ?? '—' }}</span>
                    </div>
                    <div class="py-2.5 flex flex-col gap-1 first:pt-0 last:pb-0">
                        <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">محرر المحضر</span>
                        <span class="text-sm font-bold">{{ $report->officer_name ?? '—' }}</span>
                    </div>
                    <div class="py-2.5 flex flex-col gap-1 first:pt-0 last:pb-0">
                        <span class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/50">مكان الواقعة بالتفصيل</span>
                        <span class="text-sm font-bold leading-relaxed">{{ $report->location_details ?? '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- قرار النيابة --}}
            <div class="brutal-card">
                <div class="border-b border-brutal-black bg-brutal-black px-4 py-2.5">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-neon">قرار النيابة</h2>
                </div>
                <div class="p-4">
                    @if($report->prosecution_decision)
                        <p class="text-sm font-bold leading-relaxed whitespace-pre-line">{{ $report->prosecution_decision }}</p>
                    @else
                        <p class="text-xs font-bold italic text-brutal-smoke/40">لم يتم تدوين قرار النيابة بعد.</p>
                    @endif
                </div>
            </div>

            {{-- المرفقات --}}
            <div class="brutal-card">
                <div class="border-b border-brutal-black bg-brutal-black px-4 py-2.5">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-neon">المرفقات الرقمية</h2>
                </div>
                <div class="p-4">
                    @if(is_array($report->attachments_paths) && count($report->attachments_paths) > 0)
                        <ul class="space-y-2">
                            @foreach($report->attachments_paths as $attachment)
                                <li class="flex items-center gap-2">
                                    <svg class="h-4 w-4 text-brutal-smoke/50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span class="text-sm font-bold">{{ $attachment['name'] ?? 'ملف' }}</span>
                                    <span class="text-xs text-brutal-smoke/40 uppercase">{{ $attachment['type'] ?? '' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-xs font-bold italic text-brutal-smoke/40">لا توجد مرفقات مسجلة.</p>
                    @endif
                </div>
            </div>

        </div>

        {{-- العمود الأيسر (التفاصيل المعمقة) --}}
        <div class="space-y-5 xl:col-span-2">

            {{-- موضوع البلاغ --}}
            <div class="brutal-card">
                <div class="border-b border-brutal-black bg-brutal-black px-4 py-2.5">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-neon">موضوع البلاغ</h2>
                </div>
                <div class="p-5">
                    <p class="text-lg font-bold leading-relaxed">{{ $report->report_subject ?? '—' }}</p>
                </div>
            </div>

            {{-- أطراف المحضر --}}
            <div class="brutal-card">
                <div class="border-b border-brutal-black bg-brutal-black px-4 py-2.5">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-neon">أطراف المحضر ({{ is_array($report->parties_details) ? count($report->parties_details) : 0 }})</h2>
                </div>
                <div class="p-0">
                    @if(is_array($report->parties_details) && count($report->parties_details) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm font-bold">
                                <thead>
                                    <tr class="border-b border-brutal-black/20 bg-brutal-black/5 text-xs uppercase tracking-widest text-brutal-smoke/60">
                                        <th class="px-4 py-3 text-right">الصفة</th>
                                        <th class="px-4 py-3 text-right">الاسم الكامل</th>
                                        <th class="px-4 py-3 text-right">الرقم القومي / الجنسية</th>
                                        <th class="px-4 py-3 text-right">السن / المهنة</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-brutal-black/10">
                                    @foreach($report->parties_details as $party)
                                        <tr class="hover:bg-neon/10">
                                            <td class="px-4 py-3">
                                                <span class="inline-flex border border-brutal-black/30 bg-brutal-white px-2 py-0.5 text-xs text-brutal-black">{{ $party['role'] ?? '—' }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-brutal-black">{{ $party['full_name'] ?? '—' }}</td>
                                            <td class="px-4 py-3 text-brutal-smoke/70">
                                                <div class="flex flex-col">
                                                    <span class="tabular-nums">{{ $party['national_id'] ?? '—' }}</span>
                                                    <span class="text-xs">{{ $party['nationality'] ?? '—' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-brutal-smoke/70">
                                                <div class="flex flex-col">
                                                    <span>{{ isset($party['age']) ? $party['age'] . ' سنة' : '—' }}</span>
                                                    <span class="text-xs">{{ $party['occupation'] ?? '—' }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-5 text-center text-sm font-bold italic text-brutal-smoke/40">لا توجد بيانات لأطراف المحضر.</div>
                    @endif
                </div>
            </div>

            {{-- أقوال الأطراف --}}
            <div class="brutal-card">
                <div class="border-b border-brutal-black bg-brutal-black px-4 py-2.5">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-neon">أقوال الأطراف (سؤال وجواب)</h2>
                </div>
                <div class="divide-y divide-brutal-black/10">
                    @if(is_array($report->statements_details) && count($report->statements_details) > 0)
                        @foreach($report->statements_details as $stmt)
                            <div class="p-5">
                                <div class="mb-3 flex items-center gap-2">
                                    <span class="text-sm font-bold text-brutal-black">{{ $stmt['party'] ?? 'مجهول' }}</span>
                                    <span class="inline-flex border border-brutal-black/20 bg-brutal-white px-1.5 py-0.5 text-xs text-brutal-smoke/60">{{ $stmt['role'] ?? '—' }}</span>
                                </div>
                                <div class="border-r-2 border-neon pr-4">
                                    <p class="text-sm font-bold leading-relaxed whitespace-pre-line text-brutal-smoke">{{ $stmt['text'] ?? '—' }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-5 text-center text-sm font-bold italic text-brutal-smoke/40">لا توجد إفادات مسجلة.</div>
                    @endif
                </div>
            </div>

            {{-- الأحراز والمضبوطات --}}
            <div class="brutal-card">
                <div class="border-b border-brutal-black bg-brutal-black px-4 py-2.5">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-neon">الأحراز والمضبوطات</h2>
                </div>
                <div class="p-0">
                    @if(is_array($report->seizures_details) && count($report->seizures_details) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm font-bold">
                                <thead>
                                    <tr class="border-b border-brutal-black/20 bg-brutal-black/5 text-xs uppercase tracking-widest text-brutal-smoke/60">
                                        <th class="px-4 py-3 text-right">الحرز</th>
                                        <th class="px-4 py-3 text-right">الكمية</th>
                                        <th class="px-4 py-3 text-right">الحالة</th>
                                        <th class="px-4 py-3 text-right">وصف إضافي</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-brutal-black/10">
                                    @foreach($report->seizures_details as $seizure)
                                        <tr class="hover:bg-neon/10">
                                            <td class="px-4 py-3 text-brutal-black">{{ $seizure['name'] ?? '—' }}</td>
                                            <td class="px-4 py-3 text-brutal-smoke/70">{{ $seizure['quantity'] ?? '—' }}</td>
                                            <td class="px-4 py-3 text-brutal-smoke/70">{{ $seizure['condition'] ?? '—' }}</td>
                                            <td class="px-4 py-3 text-brutal-smoke/70 max-w-xs">{{ $seizure['description'] ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-5 text-center text-sm font-bold italic text-brutal-smoke/40">لا توجد أحراز مضبوطة.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>

@endsection
