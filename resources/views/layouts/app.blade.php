<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') — {{ config('app.name', 'المسجلين خطر') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    {{-- Mobile overlay --}}
    <div id="sidebar-overlay" aria-hidden="true"></div>

    {{-- Sidebar --}}
    <aside id="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center gap-2">
                <div class="neon-bg d-flex align-items-center justify-content-center"
                     style="width:28px;height:28px;border:1px solid var(--brutal-black);">
                    <svg width="14" height="14" fill="none" stroke="var(--brutal-black)" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:.875rem;line-height:1.2;">المسجلين خطر</div>
                    <div style="font-size:.7rem;color:rgba(26,26,26,.6);">نظام السجلات</div>
                </div>
            </div>
            <button id="sidebar-close" type="button" class="btn p-1 d-lg-none" aria-label="إغلاق القائمة">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="flex-grow-1 overflow-auto p-2">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                لوحة التحكم
            </a>

            @foreach([
                ['label' => 'المسجّلون', 'route' => 'suspects.index', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label' => 'المحاضر', 'route' => 'reports.index', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['label' => 'البحث المتقدم', 'route' => null, 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                ['label' => 'الأسلحة', 'route' => null, 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                ['label' => 'بانتظار الاعتماد', 'route' => null, 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'سجل التدقيق', 'route' => null, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ] as $item)
                @if($item['route'])
                    <a href="{{ route($item['route']) }}"
                       class="nav-link {{ request()->routeIs(explode('.', $item['route'])[0] . '.*') ? 'active' : '' }}">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="nav-link disabled-link">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                        <span class="ms-auto badge-brutal badge-light" style="font-size:.6rem;">قريباً</span>
                    </span>
                @endif
            @endforeach
        </nav>

        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-2 p-2" style="border:1px solid var(--brutal-black);">
                <div class="d-flex align-items-center justify-content-center fw-bold neon-text"
                     style="width:32px;height:32px;background:var(--brutal-black);border:1px solid var(--brutal-black);font-size:.75rem;">
                    م
                </div>
                <div>
                    <div class="fw-bold" style="font-size:.875rem;">مدير النظام</div>
                    <div style="font-size:.7rem;color:rgba(26,26,26,.55);">Admin</div>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <div id="main-wrap">

        {{-- Top bar --}}
        <header id="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebar-open" type="button" class="btn p-1 d-lg-none"
                        style="border:1px solid var(--brutal-black);" aria-label="فتح القائمة">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <div class="fw-bold" style="font-size:.95rem;">@yield('page-title', 'لوحة التحكم')</div>
                    <div style="font-size:.7rem;color:rgba(26,26,26,.55);">@yield('page-subtitle', 'نظرة عامة')</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="d-none d-sm-block" style="font-size:.75rem;color:rgba(26,26,26,.55);">
                    {{ now()->translatedFormat('l، d F Y') }}
                </span>
                <button type="button" class="btn p-1 position-relative"
                        style="border:1px solid var(--brutal-black);">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="position-absolute top-0 start-0 neon-bg"
                          style="width:8px;height:8px;border:1px solid var(--brutal-black);"></span>
                </button>
            </div>
        </header>

        <main class="p-3 p-lg-4 flex-grow-1">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
