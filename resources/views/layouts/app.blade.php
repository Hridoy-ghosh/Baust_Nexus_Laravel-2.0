<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BAUST Nexus') - Smart Academic Resource & Campus Communication Platform</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        baust: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            navy: '#1e3a8a',
                            royal: '#1d4ed8',
                            accent: '#f59e0b',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Theme Script -->
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Custom Modern UI/UX Styling (Bright, Vibrant, Glassmorphism, NO Ash Grey!) -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 50%, #f0fdf4 100%);
            color: #0f172a;
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Premium Bright Cards (Replacing Black & Ash Grey) */
        .glass-card {
            background: #ffffff;
            backdrop-filter: blur(20px);
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.04);
            border-radius: 1.25rem;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid #e2e8f0;
        }

        /* Dark Mode Override (Clean Royal Blue & Vibrant Glow) */
        html.dark body {
            background: linear-gradient(135deg, #0b1329 0%, #0f172a 50%, #064e3b 100%);
            color: #f8fafc;
        }
        html.dark .glass-card {
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 12px 35px 0 rgba(0, 0, 0, 0.4);
        }
        html.dark .glass-nav {
            background: rgba(11, 19, 41, 0.95);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gradient-text {
            background: linear-gradient(135deg, #1d4ed8 0%, #059669 50%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hover-lift {
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s ease;
        }
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 35px -10px rgba(29, 78, 216, 0.15);
        }

        /* Vibrant Pastel Badges (No dull grey) */
        .pill-blue { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .pill-emerald { background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .pill-amber { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .pill-rose { background-color: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
        .pill-purple { background-color: #faf5ff; color: #6b21a8; border: 1px solid #e9d5ff; }

        html.dark .pill-blue { background-color: rgba(29, 78, 216, 0.2); color: #60a5fa; border-color: rgba(29, 78, 216, 0.4); }
        html.dark .pill-emerald { background-color: rgba(16, 185, 129, 0.2); color: #34d399; border-color: rgba(16, 185, 129, 0.4); }
        html.dark .pill-amber { background-color: rgba(245, 158, 11, 0.2); color: #fbbf24; border-color: rgba(245, 158, 11, 0.4); }
        html.dark .pill-rose { background-color: rgba(244, 63, 94, 0.2); color: #fb7185; border-color: rgba(244, 63, 94, 0.4); }
        html.dark .pill-purple { background-color: rgba(168, 85, 247, 0.2); color: #c084fc; border-color: rgba(168, 85, 247, 0.4); }
    </style>
    @yield('styles')
</head>
<body class="antialiased flex flex-col min-h-screen">

    <!-- Navigation Bar -->
    <nav class="glass-nav sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                
                <!-- Brand Logo & Name -->
                @php
                    $portalBackUrl = route('home');
                    if (Auth::check()) {
                        $portalBackUrl = match (Auth::user()->role) {
                            'cafe_admin' => route('cafe.index'),
                            'library_admin' => route('library.index'),
                            default => request()->routeIs('dashboard') ? route('resources.index') : route('dashboard'),
                        };
                    }
                @endphp
                <div class="flex min-w-0 flex-shrink-0 items-center gap-2">
                    <button type="button" onclick="goBackToPreviousPage(this.dataset.fallback)" data-fallback="{{ $portalBackUrl }}"
                            aria-label="Go back" title="Go back"
                            class="inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:-translate-x-0.5 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-blue-500 dark:hover:bg-slate-700">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    @php
                        $portalHomeUrl = route('home');
                        if (Auth::check()) {
                            $portalHomeUrl = match (Auth::user()->role) {
                                'cafe_admin' => route('cafe.index'),
                                'library_admin' => route('library.index'),
                                default => route('dashboard'),
                            };
                        }
                    @endphp
                    <a href="{{ $portalHomeUrl }}" class="flex items-center space-x-3 group pr-2">
                        <img src="/images/baust_logo.png" alt="BAUST Logo" class="w-10 h-10 object-contain group-hover:scale-105 transition-transform">
                        <div class="flex flex-col">
                            <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-none">
                                BAUST <span class="gradient-text">NEXUS</span>
                            </span>
                            <span class="text-[10px] font-bold text-indigo-700 dark:text-emerald-400 tracking-wider uppercase mt-1">Smart Academic Hub</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                @auth
                <div class="hidden min-w-0 flex-1 lg:flex items-center space-x-1 font-semibold text-xs overflow-x-auto py-1">
                    @if(Auth::user()->role === 'cafe_admin')
                        <a href="{{ route('cafe.index', ['tab' => 'orders']) }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('cafe.*') && in_array(request()->query('tab'), [null, 'orders'], true) ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                            <i class="fa-solid fa-utensils mr-1 text-blue-600 dark:text-blue-400"></i> Cafe Management
                        </a>
                        <a href="{{ route('cafe.index', ['tab' => 'inventory']) }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->query('tab') === 'inventory' ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                            <i class="fa-solid fa-boxes-stacked mr-1 text-amber-600 dark:text-amber-400"></i> Inventory
                        </a>
                        <a href="{{ route('cafe.index', ['tab' => 'staff']) }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->query('tab') === 'staff' ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                            <i class="fa-solid fa-user-group mr-1 text-violet-600 dark:text-violet-400"></i> Staff
                        </a>
                        <a href="{{ route('queries.index') }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('queries.*') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                            <i class="fa-solid fa-comments mr-1 text-teal-600 dark:text-teal-400"></i> Queries
                        </a>
                    @elseif(Auth::user()->role === 'library_admin')
                        <a href="{{ route('library.index') }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('library.*') && request()->query('tab') !== 'manage' ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                            <i class="fa-solid fa-book-bookmark mr-1 text-cyan-600 dark:text-cyan-400"></i> Library Management
                        </a>
                        <a href="{{ route('library.index', ['tab' => 'manage']) }}#library-staff" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->query('tab') === 'manage' && request()->is('library') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                            <i class="fa-solid fa-user-group mr-1 text-indigo-600 dark:text-indigo-400"></i> Library Staff
                        </a>
                        <a href="{{ route('queries.index') }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('queries.*') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                            <i class="fa-solid fa-comments mr-1 text-teal-600 dark:text-teal-400"></i> Queries
                        </a>
                    @else
                    <a href="{{ route('dashboard') }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                        <i class="fa-solid fa-gauge mr-1"></i> Dashboard
                    </a>
                    
                    <a href="{{ route('resources.index') }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('resources.*') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                        <i class="fa-solid fa-folder-open mr-1 text-amber-600 dark:text-amber-400"></i> Resources
                    </a>

                    <a href="{{ route('notices.index') }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('notices.*') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                        <i class="fa-solid fa-bullhorn mr-1 text-rose-600 dark:text-rose-400"></i> Notices
                    </a>

                    <a href="{{ route('library.index') }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('library.*') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                        <i class="fa-solid fa-book-bookmark mr-1 text-cyan-600 dark:text-cyan-400"></i> Library
                    </a>

                    <a href="{{ route('transport.index') }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('transport.*') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                        <i class="fa-solid fa-bus mr-1 text-indigo-600 dark:text-indigo-400"></i> Transport
                    </a>

                    <a href="{{ route('cafe.index') }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('cafe.*') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                        <i class="fa-solid fa-utensils mr-1 text-blue-600 dark:text-blue-400"></i> Cafe
                    </a>

                    <a href="{{ route('campus.dress_code') }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('campus.dress_code') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                        <i class="fa-solid fa-user-tie mr-1 text-emerald-600 dark:text-emerald-400"></i> Dress Code
                    </a>

                    <a href="{{ route('campus.calendar') }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('campus.calendar') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                        <i class="fa-solid fa-calendar mr-1 text-sky-600 dark:text-sky-400"></i> Calendar
                    </a>

                    <a href="{{ route('queries.index') }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('queries.*') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                        <i class="fa-solid fa-comments mr-1 text-teal-600 dark:text-teal-400"></i> Queries
                    </a>

                    <a href="{{ route('cgpa.index') }}" class="whitespace-nowrap px-3 py-2 rounded-xl transition {{ request()->routeIs('cgpa.*') ? 'bg-blue-700 text-white shadow-md shadow-blue-700/30' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-800' }}">
                        <i class="fa-solid fa-calculator mr-1 text-emerald-600 dark:text-emerald-400"></i> CGPA
                    </a>
                    @endif
                </div>
                @endauth

                <!-- Right Action, Dark/Light Toggle & Profile Menu -->
                <div class="flex flex-shrink-0 items-center space-x-2">
                    
                    <!-- Dark / Light Mode Toggle Button -->
                    <button onclick="toggleTheme()" title="Toggle Dark/Light Mode" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-amber-500 border border-slate-200 dark:border-slate-700 transition shadow-sm">
                        <i id="theme-icon" class="fa-solid fa-sun text-sm"></i>
                    </button>

                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.index') }}" class="hidden sm:inline-flex items-center px-3.5 py-2 rounded-xl text-xs font-bold bg-blue-700 text-white shadow-md shadow-blue-700/20 hover:bg-blue-800 transition dark:bg-blue-500 dark:text-slate-950 dark:hover:bg-blue-400">
                                <i class="fa-solid fa-user-shield mr-1.5"></i> Admin
                            </a>
                        @endif

                        <div class="relative">
                            <a href="{{ route('profile.show') }}" title="{{ Auth::user()->name }} — {{ str_replace('_', ' ', Auth::user()->role) }}" class="flex flex-shrink-0 items-center space-x-2.5 p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition">
                                @if(Auth::user()->avatar)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(Auth::user()->avatar) }}" alt="" class="h-8 w-8 rounded-lg object-cover shadow">
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center font-bold text-sm text-white shadow">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="hidden sm:block text-left pr-2">
                                    <div class="max-w-[100px] truncate text-xs font-bold text-slate-900 dark:text-white xl:max-w-[140px] 2xl:max-w-[180px]">{{ Auth::user()->name }}</div>
                                    <div class="text-[10px] text-blue-600 dark:text-emerald-400 font-bold capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</div>
                                </div>
                            </a>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" title="Logout" class="p-2.5 rounded-xl bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-500/20 border border-rose-200 dark:border-rose-500/20 transition">
                                <i class="fa-solid fa-right-from-bracket text-sm"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('guest') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-indigo-700 dark:text-emerald-300 hover:bg-indigo-50 dark:hover:bg-slate-800 transition">
                            <i class="fa-solid fa-compass mr-1"></i> Guest Mode
                        </a>
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-md shadow-blue-600/30 transition">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Global Toast Alert Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/90 border border-emerald-300 dark:border-emerald-500/40 text-emerald-900 dark:text-emerald-200 flex items-center justify-between shadow-lg">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-xl"></i>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/90 border border-rose-300 dark:border-rose-500/40 text-rose-900 dark:text-rose-200 flex items-center justify-between shadow-lg">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-circle-xmark text-rose-600 dark:text-rose-400 text-xl"></i>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Main Dynamic Content Container -->
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-950/90 text-slate-600 dark:text-slate-400 py-8 shadow-inner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs font-semibold">
            <div class="flex items-center space-x-2">
                <img src="/images/baust_logo.png" alt="BAUST Logo" class="w-6 h-6 object-contain">
                <span class="font-extrabold text-slate-900 dark:text-white">BAUST Nexus Ecosystem</span>
                <span>&copy; {{ date('Y') }} Bangladesh Army University of Science and Technology</span>
            </div>

            @auth
            <div class="flex items-center space-x-4 text-slate-600 dark:text-slate-400">
                @if(Auth::user()->role === 'cafe_admin')
                    <a href="{{ route('cafe.index') }}" class="hover:text-blue-600">Cafe Management</a>
                    <a href="{{ route('cafe.index', ['tab' => 'inventory']) }}" class="hover:text-blue-600">Inventory</a>
                    <a href="{{ route('cafe.index', ['tab' => 'staff']) }}" class="hover:text-blue-600">Staff</a>
                    <a href="{{ route('queries.index') }}" class="hover:text-blue-600">Queries</a>
                @elseif(Auth::user()->role === 'library_admin')
                    <a href="{{ route('library.index') }}" class="hover:text-blue-600">Library Management</a>
                    <a href="{{ route('library.index', ['tab' => 'manage']) }}#library-staff" class="hover:text-blue-600">Library Staff</a>
                    <a href="{{ route('queries.index') }}" class="hover:text-blue-600">Queries</a>
                @else
                    <a href="{{ route('campus.dress_code') }}" class="hover:text-blue-600">Dress Code</a>
                    <a href="{{ route('campus.calendar') }}" class="hover:text-blue-600">Academic Calendar</a>
                    <a href="{{ route('transport.index') }}" class="hover:text-blue-600">Transport</a>
                    <a href="{{ route('cafe.index') }}" class="hover:text-blue-600">Cafeteria</a>
                @endif
            </div>
            @endauth
        </div>
    </footer>

    <!-- Dark/Light Toggle Scripts -->
    <script>
        const portalBackStorageKey = 'baustNexusPreviousInternalPage';
        const authenticationPaths = ['/login', '/register', '/admin/login', '/cafe/admin/login', '/library/admin/login'];
        const currentPath = window.location.pathname.replace(/\/$/, '') || '/';

        if (authenticationPaths.includes(currentPath)) {
            sessionStorage.removeItem(portalBackStorageKey);
        } else {
            window.addEventListener('pagehide', function () {
                sessionStorage.setItem(portalBackStorageKey, window.location.href);
            });
        }

        function goBackToPreviousPage(fallbackUrl) {
            const previousUrl = document.referrer ? new URL(document.referrer) : null;
            const storedPreviousUrl = !previousUrl && sessionStorage.getItem(portalBackStorageKey)
                ? new URL(sessionStorage.getItem(portalBackStorageKey))
                : null;
            const candidateUrl = previousUrl || storedPreviousUrl;
            const previousPath = candidateUrl?.pathname.replace(/\/$/, '') || '';
            if (candidateUrl
                && candidateUrl.origin === window.location.origin
                && candidateUrl.href !== window.location.href
                && !authenticationPaths.includes(previousPath)
                && window.history.length > 1) {
                window.history.back();
                return;
            }

            window.location.assign(fallbackUrl);
        }

        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('theme-icon');
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                if(icon) icon.className = 'fa-solid fa-sun text-sm text-amber-500';
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                if(icon) icon.className = 'fa-solid fa-moon text-sm text-amber-400';
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            const html = document.documentElement;
            const icon = document.getElementById('theme-icon');
            if (html.classList.contains('dark')) {
                if(icon) icon.className = 'fa-solid fa-moon text-sm text-amber-400';
            } else {
                if(icon) icon.className = 'fa-solid fa-sun text-sm text-amber-500';
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
