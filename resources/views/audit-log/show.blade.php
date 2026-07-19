@extends('layouts.app')

@section('title', 'تفاصيل حدث التدقيق')
@section('page-title', 'تفاصيل حدث التدقيق')
@section('page-subtitle', 'عرض التغييرات والمعلومات الكاملة للحدث')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('audit-log.index') }}"
       class="btn btn-brutal-secondary btn-sm d-flex align-items-center gap-1">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        العودة
    </a>
</div>

<div class="row g-4">
    {{-- معلومات الحدث --}}
    <div class="col-12 col-lg-5">
        <div class="brutal-card p-4 mb-4">
            <div class="fw-bold tracking-widest text-xs mb-4">معلومات الحدث</div>
            <dl class="row g-0 mb-0">
                <dt class="col-5 text-muted-brutal text-xs py-2" style="border-bottom:1px solid rgba(10,10,10,.07);">
                    نوع الحدث
                </dt>
                <dd class="col-7 py-2 fw-bold" style="border-bottom:1px solid rgba(10,10,10,.07);">
                    <span class="badge-brutal {{ $auditLog->event_badge }}">
                        {{ $auditLog->event_label }}
                    </span>
                </dd>

                <dt class="col-5 text-muted-brutal text-xs py-2" style="border-bottom:1px solid rgba(10,10,10,.07);">
                    التوقيت
                </dt>
                <dd class="col-7 py-2" style="border-bottom:1px solid rgba(10,10,10,.07);font-size:.8rem;font-family:monospace;">
                    {{ $auditLog->created_at?->format('Y/m/d H:i:s') ?? '—' }}
                </dd>

                <dt class="col-5 text-muted-brutal text-xs py-2" style="border-bottom:1px solid rgba(10,10,10,.07);">
                    المستخدم
                </dt>
                <dd class="col-7 py-2 fw-bold" style="border-bottom:1px solid rgba(10,10,10,.07);">
                    {{ $auditLog->user_name ?? '<نظام>' }}
                    @if($auditLog->user_id)
                        <span class="text-muted-brutal fw-normal" style="font-size:.75rem;">#{{ $auditLog->user_id }}</span>
                    @endif
                </dd>

                <dt class="col-5 text-muted-brutal text-xs py-2" style="border-bottom:1px solid rgba(10,10,10,.07);">
                    عنوان IP
                </dt>
                <dd class="col-7 py-2" style="border-bottom:1px solid rgba(10,10,10,.07);font-family:monospace;font-size:.8rem;">
                    {{ $auditLog->ip_address ?? '—' }}
                </dd>

                @if($auditLog->auditable_type)
                <dt class="col-5 text-muted-brutal text-xs py-2" style="border-bottom:1px solid rgba(10,10,10,.07);">
                    الكيان المتأثر
                </dt>
                <dd class="col-7 py-2 fw-bold" style="border-bottom:1px solid rgba(10,10,10,.07);">
                    {{ $auditLog->auditable_label }}
                    @if($auditLog->auditable_id)
                        <span class="text-muted-brutal fw-normal" style="font-size:.75rem;">#{{ $auditLog->auditable_id }}</span>
                    @endif
                </dd>
                @endif

                @if($auditLog->description)
                <dt class="col-5 text-muted-brutal text-xs py-2">الوصف</dt>
                <dd class="col-7 py-2">{{ $auditLog->description }}</dd>
                @endif
            </dl>
        </div>

        @if($auditLog->user_agent)
        <div class="brutal-card p-4">
            <div class="fw-bold tracking-widest text-xs mb-3">معلومات المتصفح</div>
            <p class="text-muted-brutal mb-0" style="font-size:.75rem;word-break:break-all;">
                {{ $auditLog->user_agent }}
            </p>
        </div>
        @endif
    </div>

    {{-- التغييرات --}}
    <div class="col-12 col-lg-7">
        @if($auditLog->old_values || $auditLog->new_values)
        <div class="brutal-card p-4">
            <div class="fw-bold tracking-widets text-xs mb-4">التغييرات</div>

            @php
                $allKeys = collect(array_merge(
                    array_keys($auditLog->old_values ?? []),
                    array_keys($auditLog->new_values ?? [])
                ))->unique()->values();
            @endphp

            @if($allKeys->isEmpty())
                <p class="text-muted-brutal text-xs">لا توجد تغييرات مفصّلة.</p>
            @else
                <div class="table-responsive">
                    <table class="table mb-0" style="font-size:.85rem;">
                        <thead>
                            <tr>
                                <th style="width:35%;">الحقل</th>
                                <th style="width:32.5%;">
                                    <span class="text-muted-brutal">قبل</span>
                                </th>
                                <th style="width:32.5%;">
                                    <span style="border-bottom:2px solid var(--neon);">بعد</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allKeys as $key)
                                @php
                                    $oldVal = $auditLog->old_values[$key] ?? null;
                                    $newVal = $auditLog->new_values[$key] ?? null;
                                    $changed = $oldVal !== $newVal;
                                @endphp
                                <tr class="{{ $changed ? '' : '' }}">
                                    <td class="fw-bold text-muted-brutal">{{ $key }}</td>
                                    <td style="{{ $changed ? 'text-decoration:line-through;opacity:.55;' : '' }}">
                                        @if($oldVal === null)
                                            <span class="text-muted-brutal">—</span>
                                        @elseif(is_array($oldVal))
                                            <code style="font-size:.75rem;">{{ json_encode($oldVal, JSON_UNESCAPED_UNICODE) }}</code>
                                        @else
                                            {{ $oldVal }}
                                        @endif
                                    </td>
                                    <td class="{{ $changed ? 'fw-bold' : '' }}">
                                        @if($newVal === null)
                                            <span class="text-muted-brutal">—</span>
                                        @elseif(is_array($newVal))
                                            <code style="font-size:.75rem;">{{ json_encode($newVal, JSON_UNESCAPED_UNICODE) }}</code>
                                        @else
                                            {{ $newVal }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        @else
            <div class="brutal-card p-4 text-center text-muted-brutal">
                <p class="mb-0">لا توجد تفاصيل تغييرات لهذا الحدث.</p>
            </div>
        @endif
    </div>
</div>

@endsection
