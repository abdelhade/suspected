@extends('layouts.app')

@section('title', 'سجل المسجلين')
@section('page-title', 'سجل المسجلين والمطلوبين')
@section('page-subtitle', 'إدارة قاعدة بيانات المسجلين خطر والمطلوبين أمنياً')

@section('content')

{{-- Filters --}}
<div class="brutal-card mb-4 p-3">
    <form method="GET" action="{{ route('suspects.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md">
                <label class="form-label">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث بالاسم، الرقم القومي..."
                       class="form-control">
            </div>
            <div class="col-6 col-md-auto">
                <label class="form-label">الفئة</label>
                <select name="registration_category" class="form-select">
                    <option value="">كل الفئات</option>
                    @foreach(['مسجل شقي خطر', 'معلومات', 'مطلوب', 'مشتبه به'] as $cat)
                        <option value="{{ $cat }}" @selected(request('registration_category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-auto">
                <label class="form-label">الخطورة</label>
                <select name="danger_level" class="form-select">
                    <option value="">درجة الخطورة</option>
                    @foreach(['عالية جداً', 'عالية', 'متوسطة', 'منخفضة'] as $danger)
                        <option value="{{ $danger }}" @selected(request('danger_level') === $danger)>{{ $danger }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-auto">
                <label class="form-label">الحالة</label>
                <select name="current_status" class="form-select">
                    <option value="">الحالة الجنائية</option>
                    @foreach(['هارب', 'محبوس', 'مفرج عنه', 'تحت المراقبة'] as $status)
                        <option value="{{ $status }}" @selected(request('current_status') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-auto d-flex gap-2">
                <button type="submit" class="btn btn-brutal-primary">بحث</button>
                @if(request()->anyFilled(['search', 'registration_category', 'danger_level', 'current_status']))
                    <a href="{{ route('suspects.index') }}" class="btn btn-brutal-ghost">✕</a>
                @endif
            </div>
            <div class="col-12 col-md-auto ms-md-auto">
                <a href="{{ route('suspects.create') }}" class="btn btn-brutal-primary w-100">+ إضافة مسجل</a>
            </div>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="brutal-card overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>صورة</th>
                    <th>الاسم الرباعي</th>
                    <th>الفئة</th>
                    <th>النشاط الإجرامي</th>
                    <th>الخطورة</th>
                    <th>الحالة</th>
                    <th class="text-center">إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suspects as $suspect)
                    <tr>
                        <td>
                            @if($suspect->profile_image_path)
                                <img src="{{ asset('storage/' . $suspect->profile_image_path) }}" alt="صورة"
                                     style="width:40px;height:40px;object-fit:cover;border:1px solid var(--brutal-black);">
                            @else
                                <div class="d-flex align-items-center justify-content-center"
                                     style="width:40px;height:40px;border:1px solid var(--brutal-black);background:rgba(10,10,10,.05);">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $suspect->full_name ?? '-' }}</div>
                            @if($suspect->alias_name)
                                <div class="text-xs text-muted-brutal">شهرته: {{ $suspect->alias_name }}</div>
                            @endif
                        </td>
                        <td>{{ $suspect->registration_category ?? '-' }}</td>
                        <td>{{ $suspect->criminal_activity ?? '-' }}</td>
                        <td>
                            <span class="badge-brutal {{ $suspect->danger_level === 'عالية جداً' ? 'badge-dark' : 'badge-light' }}">
                                {{ $suspect->danger_level ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-brutal badge-light">{{ $suspect->current_status ?? '-' }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <a href="{{ route('suspects.show', $suspect) }}" class="btn-icon" title="عرض">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('suspects.edit', $suspect) }}" class="btn-icon btn-icon-dark" title="تعديل">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('suspects.destroy', $suspect) }}"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل بشكل نهائي؟');" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-danger" title="حذف">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <svg class="mb-2" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity:.3;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                            <div class="text-muted-brutal text-xs tracking-widest">لا توجد بيانات مسجلة.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($suspects->hasPages())
    <div class="mt-3">{{ $suspects->links('pagination::bootstrap-5') }}</div>
@endif

@endsection
