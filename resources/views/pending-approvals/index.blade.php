@extends('layouts.app')

@section('title', 'بانتظار الاعتماد')
@section('page-title', 'بانتظار الاعتماد')
@section('page-subtitle', 'السجلات التي تحتاج مراجعة واعتماد')

@section('content')

{{-- Flash messages --}}
@if(session('success'))
    <div class="brutal-card p-3 mb-3 d-flex align-items-center gap-2 neon-bg">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="fw-bold">{{ session('success') }}</span>
    </div>
@endif
@if(session('error'))
    <div class="brutal-card p-3 mb-3 d-flex align-items-center gap-2" style="background:#fee2e2;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="fw-bold">{{ session('error') }}</span>
    </div>
@endif

{{-- إحصائيات --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="brutal-card p-4 text-center neon-bg neon-glow">
            <div class="fw-bold" style="font-size:2rem;font-variant-numeric:tabular-nums;">
                {{ number_format($total) }}
            </div>
            <div class="text-xs tracking-widest mt-1">إجمالي بانتظار الاعتماد</div>
        </div>
    </div>

    @foreach([
        'registered_a' => ['label' => 'مسجّل A', 'desc' => 'أولوية عالية'],
        'registered_b' => ['label' => 'مسجّل B', 'desc' => 'أولوية متوسطة'],
        'visitor'      => ['label' => 'زائر',    'desc' => 'أولوية منخفضة'],
    ] as $cat => $info)
        <div class="col-6 col-lg-3">
            <div class="brutal-card p-4 text-center">
                <div class="fw-bold" style="font-size:2rem;font-variant-numeric:tabular-nums;">
                    {{ number_format($byCategory[$cat] ?? 0) }}
                </div>
                <div class="text-xs tracking-widest mt-1">{{ $info['label'] }}</div>
                <div class="text-muted-brutal" style="font-size:.65rem;">{{ $info['desc'] }}</div>
            </div>
        </div>
    @endforeach
</div>

{{-- فلاتر البحث --}}
<form method="GET" action="{{ route('pending-approvals.index') }}" class="brutal-card p-4 mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-12 col-sm-5">
            <label class="text-xs fw-bold tracking-widest mb-1 d-block">بحث</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control"
                   placeholder="الاسم أو الرقم القومي...">
        </div>
        <div class="col-12 col-sm-4">
            <label class="text-xs fw-bold tracking-widest mb-1 d-block">الفئة</label>
            <select name="registration_category" class="form-select">
                <option value="">— الكل —</option>
                <option value="registered_a" @selected(request('registration_category') === 'registered_a')>مسجّل A</option>
                <option value="registered_b" @selected(request('registration_category') === 'registered_b')>مسجّل B</option>
                <option value="visitor"      @selected(request('registration_category') === 'visitor')>زائر</option>
            </select>
        </div>
        <div class="col-12 col-sm-3">
            <button type="submit" class="btn btn-brutal-primary w-100">بحث</button>
        </div>
    </div>
</form>

{{-- الجدول --}}
<div class="brutal-card">
    <div class="d-flex align-items-center justify-content-between px-4 py-3"
         style="border-bottom:1px solid var(--brutal-black);">
        <div class="d-flex align-items-center gap-2">
            <div class="fw-bold tracking-widest text-xs">قائمة الانتظار</div>
            <span class="badge-brutal badge-neon">{{ $pending->total() }}</span>
        </div>
        <div class="text-muted-brutal text-xs">
            صفحة {{ $pending->currentPage() }} من {{ $pending->lastPage() }}
        </div>
    </div>

    @if($pending->isEmpty())
        <div class="text-center py-5 text-muted-brutal">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" class="mx-auto mb-3 d-block" style="opacity:.3;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="fw-bold">لا توجد سجلات بانتظار الاعتماد</p>
            <p class="text-xs">كل شيء تم مراجعته</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>الاسم الكامل</th>
                        <th class="d-none d-md-table-cell">الرقم القومي</th>
                        <th>الفئة</th>
                        <th class="d-none d-sm-table-cell">مستوى الخطورة</th>
                        <th class="d-none d-lg-table-cell">النشاط الإجرامي</th>
                        <th class="d-none d-md-table-cell">تاريخ الإضافة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending as $suspect)
                        @php
                            $catLabels = [
                                'registered_a' => ['label' => 'مسجّل A', 'badge' => 'badge-danger'],
                                'registered_b' => ['label' => 'مسجّل B', 'badge' => 'badge-warning'],
                                'visitor'      => ['label' => 'زائر',    'badge' => 'badge-light'],
                            ];
                            $dangerLabels = [
                                'critical' => ['label' => 'حرج',   'badge' => 'badge-danger'],
                                'high'     => ['label' => 'عالي',  'badge' => 'badge-warning'],
                                'medium'   => ['label' => 'متوسط', 'badge' => 'badge-light'],
                                'low'      => ['label' => 'منخفض', 'badge' => 'badge-light'],
                            ];
                            $catInfo    = $catLabels[$suspect->registration_category]    ?? ['label' => $suspect->registration_category ?? '—',  'badge' => 'badge-light'];
                            $dangerInfo = $dangerLabels[$suspect->danger_level] ?? ['label' => $suspect->danger_level ?? '—', 'badge' => 'badge-light'];
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('suspects.show', $suspect) }}"
                                   class="fw-bold text-decoration-none"
                                   style="color:var(--brutal-black);">
                                    {{ $suspect->full_name ?? '—' }}
                                </a>
                                @if($suspect->alias_name)
                                    <div class="text-muted-brutal" style="font-size:.7rem;">
                                        "{{ $suspect->alias_name }}"
                                    </div>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell" style="font-family:monospace;font-size:.8rem;">
                                {{ $suspect->national_id ?? '—' }}
                            </td>
                            <td>
                                <span class="badge-brutal {{ $catInfo['badge'] }}">
                                    {{ $catInfo['label'] }}
                                </span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span class="badge-brutal {{ $dangerInfo['badge'] }}">
                                    {{ $dangerInfo['label'] }}
                                </span>
                            </td>
                            <td class="d-none d-lg-table-cell text-muted-brutal">
                                {{ $suspect->criminal_activity ?? '—' }}
                            </td>
                            <td class="d-none d-md-table-cell text-muted-brutal" style="font-size:.75rem;">
                                {{ $suspect->created_at?->format('Y/m/d') ?? '—' }}
                            </td>
                            <td>
                                <div class="d-flex gap-1 align-items-center">
                                    {{-- اعتماد --}}
                                    <form method="POST"
                                          action="{{ route('pending-approvals.approve', $suspect) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('تأكيد اعتماد: {{ addslashes($suspect->full_name) }}؟')">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-brutal-primary btn-sm px-3"
                                                title="اعتماد">
                                            اعتماد
                                        </button>
                                    </form>

                                    {{-- رفض --}}
                                    <button type="button"
                                            class="btn btn-brutal-secondary btn-sm px-2"
                                            title="رفض"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $suspect->id }}">
                                        رفض
                                    </button>

                                    {{-- عرض --}}
                                    <a href="{{ route('suspects.show', $suspect) }}"
                                       class="btn btn-brutal-secondary btn-sm px-2"
                                       title="عرض الملف">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {{-- Reject Modal --}}
                        <div class="modal fade" id="rejectModal{{ $suspect->id }}" tabindex="-1"
                             aria-labelledby="rejectModalLabel{{ $suspect->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="border:2px solid var(--brutal-black);border-radius:0;box-shadow:4px 4px 0 var(--brutal-black);">
                                    <div class="modal-header" style="border-bottom:1px solid var(--brutal-black);">
                                        <h5 class="modal-title fw-bold" id="rejectModalLabel{{ $suspect->id }}">
                                            رفض السجل: {{ $suspect->full_name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('pending-approvals.reject', $suspect) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <label class="text-xs fw-bold tracking-widest mb-2 d-block">سبب الرفض (اختياري)</label>
                                            <textarea name="rejection_reason" rows="3"
                                                      class="form-control"
                                                      placeholder="أدخل سبب الرفض..."></textarea>
                                        </div>
                                        <div class="modal-footer" style="border-top:1px solid var(--brutal-black);">
                                            <button type="button" class="btn btn-brutal-secondary" data-bs-dismiss="modal">إلغاء</button>
                                            <button type="submit" class="btn btn-brutal-primary">تأكيد الرفض</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pending->hasPages())
            <div class="d-flex justify-content-center p-4" style="border-top:1px solid var(--brutal-black);">
                {{ $pending->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
