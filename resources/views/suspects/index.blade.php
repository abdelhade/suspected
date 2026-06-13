@extends('layouts.app')

@section('title', 'سجل المسجلين')
@section('page-title', 'سجل المسجلين والمطلوبين')
@section('page-subtitle', 'إدارة قاعدة بيانات المسجلين خطر والمطلوبين أمنياً')

@section('content')

{{-- Filters & Search --}}
<div class="mb-6 flex flex-col gap-4 border border-brutal-black bg-brutal-white p-4 shadow-[4px_4px_0px_rgba(0,0,0,1)] lg:flex-row lg:items-center lg:justify-between">
    <form method="GET" action="{{ route('suspects.index') }}" class="flex w-full flex-col gap-3 lg:flex-row lg:items-center">
        <div class="flex flex-1 gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="ابحث بالاسم، الرقم القومي..."
                   class="w-full border border-brutal-black bg-white px-3 py-2 text-sm font-bold focus:border-neon focus:outline-none">
            <button type="submit" class="brutal-btn-primary px-4 py-2">بحث</button>
            @if(request()->anyFilled(['search', 'registration_category', 'danger_level', 'current_status']))
                <a href="{{ route('suspects.index') }}" class="brutal-btn-ghost px-4 py-2" title="إلغاء الفلترة">✕</a>
            @endif
        </div>
        
        <div class="flex flex-wrap gap-2 lg:flex-nowrap">
            {{-- Category filter --}}
            <select name="registration_category"
                    class="border border-brutal-black bg-brutal-white px-3 py-2 text-sm font-bold focus:border-neon focus:outline-none">
                <option value="">كل الفئات</option>
                @foreach(['مسجل شقي خطر', 'معلومات', 'مطلوب', 'مشتبه به'] as $cat)
                    <option value="{{ $cat }}" @selected(request('registration_category') === $cat)>{{ $cat }}</option>
                @endforeach
            </select>

            {{-- Danger filter --}}
            <select name="danger_level"
                    class="border border-brutal-black bg-brutal-white px-3 py-2 text-sm font-bold focus:border-neon focus:outline-none">
                <option value="">درجة الخطورة</option>
                @foreach(['عالية جداً', 'عالية', 'متوسطة', 'منخفضة'] as $danger)
                    <option value="{{ $danger }}" @selected(request('danger_level') === $danger)>{{ $danger }}</option>
                @endforeach
            </select>

            {{-- Status filter --}}
            <select name="current_status"
                    class="border border-brutal-black bg-brutal-white px-3 py-2 text-sm font-bold focus:border-neon focus:outline-none">
                <option value="">الحالة الجنائية</option>
                @foreach(['هارب', 'محبوس', 'مفرج عنه', 'تحت المراقبة'] as $status)
                    <option value="{{ $status }}" @selected(request('current_status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
    </form>
    
    <div class="flex-shrink-0 border-t border-brutal-black pt-4 lg:border-l lg:border-t-0 lg:pl-4 lg:pt-0">
        <a href="{{ route('suspects.create') }}" class="brutal-btn-primary w-full px-6 py-2">
            + إضافة مسجل
        </a>
    </div>
</div>

{{-- Suspects Table --}}
<div class="overflow-x-auto border border-brutal-black bg-brutal-white shadow-[4px_4px_0px_rgba(0,0,0,1)]">
    <table class="w-full text-right text-sm">
        <thead class="border-b border-brutal-black bg-brutal-black text-neon">
            <tr>
                <th scope="col" class="px-4 py-3 font-bold uppercase tracking-widest">صورة</th>
                <th scope="col" class="px-4 py-3 font-bold uppercase tracking-widest">الاسم الرباعي</th>
                <th scope="col" class="px-4 py-3 font-bold uppercase tracking-widest">الفئة</th>
                <th scope="col" class="px-4 py-3 font-bold uppercase tracking-widest">النشاط الإجرامي</th>
                <th scope="col" class="px-4 py-3 font-bold uppercase tracking-widest">الخطورة</th>
                <th scope="col" class="px-4 py-3 font-bold uppercase tracking-widest">الحالة</th>
                <th scope="col" class="px-4 py-3 text-center font-bold uppercase tracking-widest">إجراء</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-brutal-black/20">
            @forelse($suspects as $suspect)
                <tr class="hover:bg-neon/10 transition-colors">
                    <td class="px-4 py-3">
                        @if($suspect->profile_image_path)
                            <img src="{{ asset('storage/' . $suspect->profile_image_path) }}" alt="صورة" class="h-10 w-10 border border-brutal-black object-cover">
                        @else
                            <div class="flex h-10 w-10 items-center justify-center border border-brutal-black bg-brutal-black/5">
                                <svg class="h-5 w-5 text-brutal-smoke/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-black text-brutal-black">{{ $suspect->full_name ?? '-' }}</div>
                        <div class="text-xs font-bold text-brutal-smoke/60">{{ $suspect->alias_name ? 'شهرته: ' . $suspect->alias_name : '' }}</div>
                    </td>
                    <td class="px-4 py-3 font-bold text-brutal-smoke">{{ $suspect->registration_category ?? '-' }}</td>
                    <td class="px-4 py-3 font-bold text-brutal-smoke">{{ $suspect->criminal_activity ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center border border-brutal-black px-2 py-0.5 text-xs font-bold uppercase {{ $suspect->danger_level === 'عالية جداً' ? 'bg-red-500 text-white' : 'bg-neon/30 text-brutal-black' }}">
                            {{ $suspect->danger_level ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center border border-brutal-black px-2 py-0.5 text-xs font-bold uppercase bg-brutal-black/5 text-brutal-black">
                            {{ $suspect->current_status ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            {{-- عرض --}}
                            <a href="{{ route('suspects.show', $suspect) }}" title="عرض التفاصيل" class="border border-brutal-black bg-brutal-white p-1.5 text-brutal-black hover:bg-neon hover:text-brutal-black transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </a>
                            {{-- تعديل --}}
                            <a href="{{ route('suspects.edit', $suspect) }}" title="تعديل البيانات" class="border border-brutal-black bg-brutal-white p-1.5 text-brutal-black hover:bg-neon hover:text-brutal-black transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                            </a>
                            {{-- حذف --}}
                            <form method="POST" action="{{ route('suspects.destroy', $suspect) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل بشكل نهائي؟');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="حذف السجل" class="border border-brutal-black bg-brutal-white p-1.5 text-brutal-black hover:bg-red-500 hover:text-white transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center">
                        <svg class="mx-auto mb-3 h-12 w-12 text-brutal-smoke/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                        </svg>
                        <p class="text-sm font-bold uppercase tracking-widest text-brutal-smoke">لا توجد بيانات مسجلة.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-8">
    {{ $suspects->links('pagination::tailwind') }}
</div>

@endsection
