@extends('layouts.app')

@section('title', 'Dashboard - BAUST Nexus')

@section('content')
<div class="space-y-8">

    <!-- Hero Banner with Glassmorphism -->
    <div class="relative overflow-hidden rounded-3xl glass-card border border-slate-200 dark:border-slate-700/80 p-8 md:p-10 shadow-xl">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-10 -top-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-300 dark:border-emerald-500/30 text-emerald-800 dark:text-emerald-400 text-xs font-bold">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 dark:bg-emerald-400 animate-pulse"></span>
                    <span>Bangladesh Army University of Science and Technology</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Welcome to <span class="gradient-text">BAUST Nexus</span> Portal
                </h1>
                <p class="text-slate-700 dark:text-slate-300 text-sm md:text-base leading-relaxed font-medium">
                    Your single centralized digital ecosystem for Academic Resources, Smart Notices, Central Library Books, Bus Schedules, Cafeteria Menus, CGPA Calculation, and Q&A Guidance.
                </p>
            </div>

            @auth
                <div class="glass-card p-5 rounded-2xl border border-slate-200 dark:border-slate-700 w-full md:w-auto min-w-[260px] text-right shadow-sm">
                    <div class="flex items-center justify-end space-x-3 mb-2">
                        <div>
                            <div class="text-base font-extrabold text-slate-900 dark:text-white">{{ Auth::user()->name }}</div>
                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-400">
                                {{ Auth::user()->isTeacher() ? (Auth::user()->teacher_id ? 'Teacher ID: '.Auth::user()->teacher_id : (Auth::user()->designation ?? 'Teacher')) : (Auth::user()->isStudent() ? (Auth::user()->student_id ? 'Student ID: '.Auth::user()->student_id : 'Student') : ucfirst(str_replace('_', ' ', Auth::user()->role))) }}
                            </div>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center font-bold text-lg text-white shadow-md">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="flex items-center justify-end space-x-2 mt-3 pt-3 border-t border-slate-200 dark:border-slate-800 text-xs">
                        <span class="px-2.5 py-1 rounded-md font-bold uppercase text-[10px] 
                            {{ match (Auth::user()->role) {
                                'admin' => 'badge-admin',
                                'cafe_admin', 'library_admin' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-500/20 dark:text-indigo-300',
                                'teacher' => 'badge-teacher',
                                default => 'badge-student',
                            } }}">
                            {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                        </span>
                        @if(Auth::user()->isStudent())
                            <span class="text-emerald-700 dark:text-emerald-400 font-bold">CGPA: {{ Auth::user()->cgpa }}</span>
                        @endif
                    </div>
                </div>
            @else
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="px-6 py-3 rounded-xl font-bold bg-emerald-600 hover:bg-emerald-500 text-white transition shadow-lg shadow-emerald-600/30 text-sm">
                        Get Started
                    </a>
                    <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl font-bold bg-slate-900 dark:bg-slate-800 text-white dark:text-slate-200 hover:bg-slate-800 transition text-sm">
                        Sign In
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Live System Overview Stat Counters -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
        <div class="glass-card p-4 rounded-2xl border border-slate-200 dark:border-slate-800 text-center hover-lift">
            <div class="text-amber-600 dark:text-amber-400 text-xl mb-1"><i class="fa-solid fa-folder-open"></i></div>
            <div class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $stats['total_resources'] }}</div>
            <div class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase">Resources</div>
        </div>

        <div class="glass-card p-4 rounded-2xl border border-slate-200 dark:border-slate-800 text-center hover-lift">
            <div class="text-rose-600 dark:text-rose-400 text-xl mb-1"><i class="fa-solid fa-bullhorn"></i></div>
            <div class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $stats['total_notices'] }}</div>
            <div class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase">Notices</div>
        </div>

        <div class="glass-card p-4 rounded-2xl border border-slate-200 dark:border-slate-800 text-center hover-lift">
            <div class="text-cyan-600 dark:text-cyan-400 text-xl mb-1"><i class="fa-solid fa-book"></i></div>
            <div class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $stats['available_books'] }}</div>
            <div class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase">Library Titles</div>
        </div>

        <div class="glass-card p-4 rounded-2xl border border-slate-200 dark:border-slate-800 text-center hover-lift">
            <div class="text-indigo-600 dark:text-indigo-400 text-xl mb-1"><i class="fa-solid fa-bus"></i></div>
            <div class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $stats['active_bus_routes'] }}</div>
            <div class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase">Bus Routes</div>
        </div>

        <div class="glass-card p-4 rounded-2xl border border-slate-200 dark:border-slate-800 text-center hover-lift">
            <div class="text-orange-600 dark:text-orange-400 text-xl mb-1"><i class="fa-solid fa-utensils"></i></div>
            <div class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $stats['cafe_items'] }}</div>
            <div class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase">Cafe Menu</div>
        </div>

        <div class="glass-card p-4 rounded-2xl border border-slate-200 dark:border-slate-800 text-center hover-lift">
            <div class="text-teal-600 dark:text-teal-400 text-xl mb-1"><i class="fa-solid fa-comments"></i></div>
            <div class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $stats['open_queries'] }}</div>
            <div class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase">Active Queries</div>
        </div>

        <div class="glass-card p-4 rounded-2xl border border-slate-200 dark:border-slate-800 text-center hover-lift col-span-2 md:col-span-1">
            <div class="text-emerald-600 dark:text-emerald-400 text-xl mb-1"><i class="fa-solid fa-users"></i></div>
            <div class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $stats['total_students'] }}</div>
            <div class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase">Students</div>
        </div>
    </div>

    <!-- Central Modules Quick Grid -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-cubes text-emerald-600 dark:text-emerald-400"></i> BAUST Nexus Central Modules
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <!-- Academic Resources Module Card -->
            <a href="{{ route('resources.index') }}" class="glass-card p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 hover-lift group">
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-500/20 border border-amber-300 dark:border-amber-500/30 flex items-center justify-center text-amber-700 dark:text-amber-400 text-2xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-folder-tree"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white group-hover:text-amber-600 transition">Academic Resources</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-2 leading-relaxed font-medium">
                    Organized notes, lab manuals, questions & study materials categorized by Dept → Level → Term → Course.
                </p>
                <div class="mt-4 flex items-center text-xs font-extrabold text-amber-700 dark:text-amber-400 group-hover:translate-x-1 transition">
                    <span>Browse Repository</span> <i class="fa-solid fa-arrow-right ml-1"></i>
                </div>
            </a>

            <!-- Smart Notice Board Card -->
            <a href="{{ route('notices.index') }}" class="glass-card p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 hover-lift group">
                <div class="w-12 h-12 rounded-xl bg-rose-100 dark:bg-rose-500/20 border border-rose-300 dark:border-rose-500/30 flex items-center justify-center text-rose-700 dark:text-rose-400 text-2xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white group-hover:text-rose-600 transition">Smart Notice Board</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-2 leading-relaxed font-medium">
                    Centralized academic notices, exam announcements, events, and urgent updates with push notifications.
                </p>
                <div class="mt-4 flex items-center text-xs font-extrabold text-rose-700 dark:text-rose-400 group-hover:translate-x-1 transition">
                    <span>View Announcements</span> <i class="fa-solid fa-arrow-right ml-1"></i>
                </div>
            </a>

            <!-- Library Information Card -->
            <a href="{{ route('library.index') }}" class="glass-card p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 hover-lift group">
                <div class="w-12 h-12 rounded-xl bg-cyan-100 dark:bg-cyan-500/20 border border-cyan-300 dark:border-cyan-500/30 flex items-center justify-center text-cyan-700 dark:text-cyan-400 text-2xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-book-journal-whills"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white group-hover:text-cyan-600 transition">Library Catalog</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-2 leading-relaxed font-medium">
                    Search textbook availability, rack shelf location, and request instant library token borrow codes.
                </p>
                <div class="mt-4 flex items-center text-xs font-extrabold text-cyan-700 dark:text-cyan-400 group-hover:translate-x-1 transition">
                    <span>Search Library</span> <i class="fa-solid fa-arrow-right ml-1"></i>
                </div>
            </a>

            <!-- BAUST Transport Schedule Card -->
            <a href="{{ route('transport.index') }}" class="glass-card p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 hover-lift group">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-500/20 border border-indigo-300 dark:border-indigo-500/30 flex items-center justify-center text-indigo-700 dark:text-indigo-400 text-2xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bus-simple"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white group-hover:text-indigo-600 transition">Transport Module</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-2 leading-relaxed font-medium">
                    Bus routes for Saidpur, Rangpur, Parbatipur & Dinajpur with timing tables & driver contacts.
                </p>
                <div class="mt-4 flex items-center text-xs font-extrabold text-indigo-700 dark:text-indigo-400 group-hover:translate-x-1 transition">
                    <span>Check Schedules</span> <i class="fa-solid fa-arrow-right ml-1"></i>
                </div>
            </a>

            <!-- BAUST Cafe / Cafeteria Card -->
            <a href="{{ route('cafe.index') }}" class="glass-card p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 hover-lift group">
                <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-500/20 border border-orange-300 dark:border-orange-500/30 flex items-center justify-center text-orange-700 dark:text-orange-400 text-2xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white group-hover:text-orange-600 transition">BAUST Cafeteria</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-2 leading-relaxed font-medium">
                    Daily breakfast & lunch menus, food prices, combo deals, opening hours, and special offers.
                </p>
                <div class="mt-4 flex items-center text-xs font-extrabold text-orange-700 dark:text-orange-400 group-hover:translate-x-1 transition">
                    <span>View Today's Menu</span> <i class="fa-solid fa-arrow-right ml-1"></i>
                </div>
            </a>

            <!-- Query Board Forum Card -->
            <a href="{{ route('queries.index') }}" class="glass-card p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 hover-lift group">
                <div class="w-12 h-12 rounded-xl bg-teal-100 dark:bg-teal-500/20 border border-teal-300 dark:border-teal-500/30 flex items-center justify-center text-teal-700 dark:text-teal-400 text-2xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white group-hover:text-teal-600 transition">Query Board</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-2 leading-relaxed font-medium">
                    Student Q&A forum with verified replies from senior students and faculty teachers.
                </p>
                <div class="mt-4 flex items-center text-xs font-extrabold text-teal-700 dark:text-teal-400 group-hover:translate-x-1 transition">
                    <span>Ask a Question</span> <i class="fa-solid fa-arrow-right ml-1"></i>
                </div>
            </a>

            <!-- CGPA Calculator & Tracker Card -->
            <a href="{{ route('cgpa.index') }}" class="glass-card p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 hover-lift group">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 border border-emerald-300 dark:border-emerald-500/30 flex items-center justify-center text-emerald-700 dark:text-emerald-400 text-2xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-calculator"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white group-hover:text-emerald-600 transition">CGPA & Progress</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-2 leading-relaxed font-medium">
                    Semester GPA calculator & credit completion tracker with BAUST official grading scale.
                </p>
                <div class="mt-4 flex items-center text-xs font-extrabold text-emerald-700 dark:text-emerald-400 group-hover:translate-x-1 transition">
                    <span>Calculate GPA</span> <i class="fa-solid fa-arrow-right ml-1"></i>
                </div>
            </a>

        </div>
    </div>

    <!-- Pinned Notices & Query Forum Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Urgent Pinned Notices -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-fire text-rose-600 dark:text-rose-500"></i> Urgent Announcements & Notices
                </h2>
                <a href="{{ route('notices.index') }}" class="text-xs font-extrabold text-blue-700 dark:text-emerald-400 hover:underline">View All Notices</a>
            </div>

            <div class="space-y-3">
                @forelse($urgentNotices as $notice)
                    <div class="glass-card p-5 rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="flex items-center space-x-2 mb-1.5">
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase bg-rose-100 dark:bg-rose-500/20 text-rose-800 dark:text-rose-300 border border-rose-300 dark:border-rose-500/30">
                                        {{ $notice->category }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Target: {{ $notice->target_department }} ({{ $notice->target_level }})</span>
                                </div>
                                <h3 class="text-base font-extrabold text-slate-900 dark:text-white hover:text-emerald-600 transition cursor-pointer">
                                    {{ $notice->title }}
                                </h3>
                                <p class="text-xs text-slate-700 dark:text-slate-300 mt-2 line-clamp-2 leading-relaxed font-medium">
                                    {{ $notice->content }}
                                </p>
                            </div>
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $notice->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="glass-card p-6 rounded-2xl text-center text-slate-600 dark:text-slate-400 text-xs font-bold">
                        No urgent notice broadcast at the moment.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Recent Community Queries -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-comments text-teal-600 dark:text-teal-400"></i> Query Board Pulse
                </h2>
                <a href="{{ route('queries.index') }}" class="text-xs font-extrabold text-teal-700 dark:text-teal-400 hover:underline">Ask Question</a>
            </div>

            <div class="space-y-3">
                @foreach($recentQueries as $post)
                    <div class="glass-card p-4 rounded-2xl border border-slate-200 dark:border-slate-800">
                        <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400 mb-1">
                            <span class="text-teal-700 dark:text-teal-400 font-extrabold">{{ $post->department }}</span>
                            <span class="font-bold">{{ $post->replies_count }} replies</span>
                        </div>
                        <a href="{{ route('queries.show', $post->id) }}" class="text-sm font-extrabold text-slate-900 dark:text-slate-200 hover:text-emerald-600 transition line-clamp-2">
                            {{ $post->title }}
                        </a>
                        <div class="mt-3 pt-2 border-t border-slate-200 dark:border-slate-800/80 flex items-center justify-between text-[11px] text-slate-600 dark:text-slate-400 font-medium">
                            <span>By: {{ $post->is_anonymous ? 'Anonymous Student' : ($post->user->name ?? 'Student') }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $post->status === 'resolved' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300' : 'bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
