<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') — {{ config('app.name', 'المسجلين خطر') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-brutal-white text-brutal-black font-sans">

    {{-- Mobile overlay --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 z-40 hidden bg-brutal-black/60 lg:hidden"
         aria-hidden="true"></div>

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside id="sidebar"
               class="fixed inset-y-0 right-0 z-50 flex w-64 translate-x-full flex-col border-l border-brutal-black bg-brutal-white transition-transform duration-200 ease-out lg:translate-x-0">

            {{-- Header --}}
            <div class="flex h-14 items-center justify-between border-b border-brutal-black px-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-7 w-7 items-center justify-center border border-brutal-black bg-neon">
                        <svg class="h-3.5 w-3.5 text-brutal-black" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold tracking-wide text-brutal-black">المسجلين خطر</p>
                        <p class="text-xs font-bold text-brutal-smoke/70">نظام السجلات</p>
                    </div>
                </div>
                <button id="sidebar-close" type="button" class="p-1 lg:hidden" aria-label="إغلاق القائمة">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 overflow-y-auto p-3">
                <a href="{{ route('dashboard') }}"
                   class="mb-0.5 flex items-center gap-2.5 border px-3 py-2.5 text-sm font-bold tracking-wide transition-colors
                   {{ request()->routeIs('dashboard')
                       ? 'border-brutal-black bg-neon text-brutal-black neon-glow'
                       : 'border-transparent text-brutal-smoke hover:border-brutal-black hover:bg-neon/20' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    لوحة التحكم
                </a>

                @foreach([
                    ['label' => 'المسجّلون', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['label' => 'المحاضر', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['label' => 'البحث المتقدم', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                    ['label' => 'الأسلحة', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['label' => 'بانتظار الاعتماد', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'سجل التدقيق', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ] as $item)
                    <span class="mb-0.5 flex cursor-not-allowed items-center gap-2.5 border border-transparent px-3 py-2.5 text-sm font-bold text-brutal-smoke/40">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                        <span class="mr-auto border border-brutal-black/20 px-1.5 py-0.5 text-xs uppercase tracking-widest">قريباً</span>
                    </span>
                @endforeach
            </nav>

            {{-- User --}}
            <div class="border-t border-brutal-black p-3">
                <div class="flex items-center gap-2.5 border border-brutal-black px-3 py-2">
                    <div class="flex h-8 w-8 items-center justify-center border border-brutal-black bg-brutal-black text-xs font-bold text-neon">
                        م
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-brutal-black">مدير النظام</p>
                        <p class="text-xs font-bold text-brutal-smoke/60">Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex flex-1 flex-col lg:mr-64">

            {{-- Top bar --}}
            <header class="sticky top-0 z-30 flex h-14 items-center justify-between border-b border-brutal-black bg-brutal-white px-4 lg:px-6">
                <div class="flex items-center gap-3">
                    <button id="sidebar-open" type="button" class="border border-brutal-black p-1.5 lg:hidden" aria-label="فتح القائمة">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-base font-bold tracking-wide text-brutal-black">@yield('page-title', 'لوحة التحكم')</h1>
                        <p class="text-xs font-bold text-brutal-smoke/60">@yield('page-subtitle', 'نظرة عامة')</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden text-xs font-bold tracking-wide text-brutal-smoke/60 sm:block">{{ now()->translatedFormat('l، d F Y') }}</span>
                    <button type="button" class="relative border border-brutal-black p-1.5 hover:bg-neon/20">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -left-0.5 -top-0.5 h-2 w-2 border border-brutal-black bg-neon"></span>
                    </button>
                </div>
            </header>

            <main class="flex-1 p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
