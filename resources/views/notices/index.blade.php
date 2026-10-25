@extends('layouts.app')

@section('title', 'Smart Notice Board - BAUST Nexus')

@section('content')
<div class="space-y-6">

    <!-- Header & Publish Action -->
    <div class="glass-card p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-700/80 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-sm">
        <div>
            <span class="px-3 py-1 rounded-full bg-rose-100 dark:bg-rose-500/10 text-rose-800 dark:text-rose-400 border border-rose-300 dark:border-rose-500/30 text-xs font-bold uppercase tracking-wider">
                Official Campus Feed
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2">BAUST Smart Notice Board</h1>
            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mt-1">Real-time academic notices, exam announcements, campus events & urgent updates</p>
        </div>

        @auth
            @if(Auth::user()->isAdmin() || Auth::user()->isTeacher())
                <button onclick="document.getElementById('noticeModal').classList.remove('hidden')" class="px-5 py-3 rounded-xl font-bold bg-rose-600 hover:bg-rose-500 text-white transition shadow-lg shadow-rose-600/20 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Publish Official Notice
                </button>
            @endif
        @endauth
    </div>

    <!-- Category Tabs & Filter -->
    <div class="glass-card p-4 rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap gap-2 text-xs">
            <a href="{{ route('notices.index') }}" class="px-3.5 py-2 rounded-xl font-bold transition {{ !request('category') ? 'bg-rose-600 text-white' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                All Notices
            </a>
            <a href="{{ route('notices.index', ['category' => 'academic']) }}" class="px-3.5 py-2 rounded-xl font-bold transition {{ request('category') == 'academic' ? 'bg-rose-600 text-white' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                Academic
            </a>
            <a href="{{ route('notices.index', ['category' => 'exam']) }}" class="px-3.5 py-2 rounded-xl font-bold transition {{ request('category') == 'exam' ? 'bg-rose-600 text-white' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                Exams
            </a>
            <a href="{{ route('notices.index', ['category' => 'event']) }}" class="px-3.5 py-2 rounded-xl font-bold transition {{ request('category') == 'event' ? 'bg-rose-600 text-white' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                Events
            </a>
            <a href="{{ route('notices.index', ['category' => 'urgent']) }}" class="px-3.5 py-2 rounded-xl font-bold transition {{ request('category') == 'urgent' ? 'bg-rose-600 text-white' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                Urgent
            </a>
        </div>

        <form action="{{ route('notices.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search notice title..." class="px-3.5 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-500">
            <button type="submit" class="px-3 py-2 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-xl text-xs">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <!-- Notice Stream -->
    <div class="space-y-4">
        @forelse($notices as $notice)
            <div class="glass-card p-6 rounded-2xl border {{ $notice->is_urgent ? 'border-rose-400 dark:border-rose-500/50 bg-rose-50/50 dark:bg-rose-950/20' : 'border-slate-200 dark:border-slate-800' }} hover-lift">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-3">
                    <div class="flex items-center space-x-2">
                        @if($notice->is_pinned)
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-500/30">
                                <i class="fa-solid fa-thumbtack mr-1"></i> Pinned
                            </span>
                        @endif

                        @if($notice->is_urgent)
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-rose-100 dark:bg-rose-500/20 text-rose-800 dark:text-rose-300 border border-rose-300 dark:border-rose-500/30 animate-pulse">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Urgent Notice
                            </span>
                        @endif

                        <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700">
                            {{ $notice->category }}
                        </span>
                    </div>

                    <div class="text-xs font-bold text-slate-600 dark:text-slate-400">
                        <i class="fa-solid fa-clock mr-1 text-slate-500"></i> {{ $notice->created_at->format('M d, Y - h:i A') }}
                    </div>
                </div>

                <h2 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2 leading-snug">
                    {{ $notice->title }}
                </h2>

                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line mb-4 font-medium">
                    {{ $notice->content }}
                </p>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-800/80 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-600 dark:text-slate-400 font-bold">
                    <div class="flex items-center space-x-4">
                        <span><i class="fa-solid fa-user-pen text-rose-600 dark:text-rose-400 mr-1"></i> {{ $notice->publisher->name ?? 'BAUST Authority' }}</span>
                        <span><i class="fa-solid fa-users text-emerald-600 dark:text-emerald-400 mr-1"></i> Dept: <strong>{{ $notice->target_department }}</strong></span>
                    </div>

                    <span class="text-[11px] text-slate-500 font-mono">Notice ID: #NOT-{{ $notice->id }}</span>
                </div>
            </div>
        @empty
            <div class="glass-card p-12 rounded-3xl text-center text-slate-400">
                <i class="fa-solid fa-bullhorn text-4xl text-slate-600 mb-3 block"></i>
                <p class="text-base font-semibold">No notices published yet under this category.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $notices->links() }}
    </div>

</div>

<!-- Publish Notice Modal -->
<div id="noticeModal" class="hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="glass-card max-w-lg w-full rounded-3xl p-6 border border-slate-700 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-bullhorn text-rose-400"></i> Publish Smart Notice
            </h3>
            <button onclick="document.getElementById('noticeModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('notices.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Notice Title</label>
                <input type="text" name="title" required placeholder="Announcement Title..." class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs">
            </div>

            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Category</label>
                    <select name="category" class="w-full px-2 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs">
                        <option value="academic">Academic</option>
                        <option value="exam">Examination</option>
                        <option value="event">Event</option>
                        <option value="urgent">Urgent</option>
                        <option value="announcement">Announcement</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Department</label>
                    <select name="target_department" class="w-full px-2 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs">
                        @foreach($departments as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Level</label>
                    <select name="target_level" class="w-full px-2 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs">
                        <option value="All">All Levels</option>
                        <option value="Level 1">Level 1</option>
                        <option value="Level 2">Level 2</option>
                        <option value="Level 3">Level 3</option>
                        <option value="Level 4">Level 4</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center space-x-4 text-xs">
                <label class="flex items-center space-x-2 text-slate-300 cursor-pointer">
                    <input type="checkbox" name="is_pinned" value="1" class="rounded bg-slate-900 border-slate-700 text-rose-500">
                    <span>Pin to Top</span>
                </label>
                <label class="flex items-center space-x-2 text-slate-300 cursor-pointer">
                    <input type="checkbox" name="is_urgent" value="1" class="rounded bg-slate-900 border-slate-700 text-rose-500">
                    <span>Urgent Alert</span>
                </label>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Notice Description</label>
                <textarea name="content" rows="4" required placeholder="Write official announcement details..." class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs"></textarea>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl font-bold bg-rose-600 text-white hover:bg-rose-500 text-xs uppercase">
                Publish Official Announcement
            </button>
        </form>
    </div>
</div>
@endsection
