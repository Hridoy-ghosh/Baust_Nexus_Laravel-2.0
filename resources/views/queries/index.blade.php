@extends('layouts.app')

@section('title', 'Query Board - BAUST Nexus')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="glass-card p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-700/80 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-sm">
        <div>
            <span class="px-3 py-1 rounded-full bg-teal-100 dark:bg-teal-500/10 text-teal-800 dark:text-teal-400 border border-teal-300 dark:border-teal-500/30 text-xs font-bold uppercase tracking-wider">
                Student & Senior Communication Platform
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2">BAUST Query & Discussion Board</h1>
            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mt-1">Post academic questions, seek guidance from seniors, get official teacher answers</p>
        </div>

        @auth
            <button onclick="document.getElementById('queryModal').classList.remove('hidden')" class="px-5 py-3 rounded-xl font-bold bg-teal-600 hover:bg-teal-500 text-white transition shadow-lg shadow-teal-600/20 text-sm flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i> Post Question
            </button>
        @endauth
    </div>

    <!-- Filters -->
    <div class="glass-card p-5 rounded-2xl border border-slate-200 dark:border-slate-800">
        <form action="{{ route('queries.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-400 uppercase mb-1">Category</label>
                <select name="category" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-xs text-slate-900 dark:text-white font-semibold">
                    <option value="">All Categories</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-400 uppercase mb-1">Department</label>
                <select name="department" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-xs text-slate-900 dark:text-white font-semibold">
                    <option value="">All Departments</option>
                    @foreach($departments as $d)
                        <option value="{{ $d }}" {{ request('department') == $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-400 uppercase mb-1">Status</label>
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-xs text-slate-900 dark:text-white font-semibold">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Search Keywords</label>
                <div class="flex gap-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search queries..." class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white placeholder-slate-500 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    <button type="submit" class="px-3 py-2 bg-teal-600 hover:bg-teal-500 text-white rounded-lg text-xs font-bold">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Questions Stream -->
    <div class="space-y-4">
        @forelse($posts as $post)
            <div class="glass-card p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 hover-lift">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-teal-50 text-teal-800 border border-teal-200 dark:bg-teal-500/20 dark:text-teal-300 dark:border-teal-500/30">
                            {{ $post->department }}
                        </span>
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700">
                            {{ $post->level }}
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $post->status === 'resolved' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30' : 'bg-amber-50 text-amber-800 border border-amber-200 dark:bg-amber-500/20 dark:text-amber-300 dark:border-amber-500/30' }}">
                            {{ $post->status }}
                        </span>
                    </div>

                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">{{ $post->created_at->diffForHumans() }}</span>
                </div>

                <a href="{{ route('queries.show', $post->id) }}" class="text-lg font-bold text-slate-900 dark:text-white hover:text-teal-700 dark:hover:text-teal-300 transition block mt-3 leading-snug">
                    {{ $post->title }}
                </a>

                <p class="text-sm text-slate-700 dark:text-slate-300 mt-2 line-clamp-3 leading-relaxed">
                    {{ $post->content }}
                </p>

                <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600 dark:text-slate-400">
                    <div class="flex items-center space-x-3">
                        <span class="font-medium text-slate-700 dark:text-slate-300">
                            <i class="fa-solid fa-user-circle mr-1 text-teal-700 dark:text-teal-400"></i>
                            {{ $post->is_anonymous ? 'Anonymous Student' : ($post->user->name ?? 'Student') }}
                        </span>
                    </div>

                    <a href="{{ route('queries.show', $post->id) }}" class="self-start sm:self-auto px-3 py-2 rounded-lg bg-teal-50 hover:bg-teal-100 border border-teal-200 text-teal-800 text-xs font-bold transition flex items-center gap-1.5 dark:bg-slate-800 dark:hover:bg-slate-700 dark:border-slate-700 dark:text-teal-300">
                        <i class="fa-solid fa-reply"></i> {{ $post->replies_count }} Replies & Answers
                    </a>
                </div>
            </div>
        @empty
            <div class="glass-card p-12 rounded-3xl text-center text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                <i class="fa-solid fa-comments text-4xl text-teal-600 dark:text-teal-400 mb-3 block"></i>
                <p class="text-base font-semibold">No questions matching your search filters.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>

</div>

<!-- Query Modal -->
<div id="queryModal" class="hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="glass-card max-w-lg w-full max-h-[90vh] overflow-y-auto rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-teal-700 dark:text-teal-400"></i> Post New Academic Query
            </h3>
            <button type="button" aria-label="Close question form" onclick="document.getElementById('queryModal').classList.add('hidden')" class="h-9 w-9 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('queries.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Question Title</label>
                <input type="text" name="title" required placeholder="What is your question or problem?" class="w-full px-3 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>

            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Category</label>
                    <select name="category" required class="w-full px-2 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs">
                        @foreach($categories as $k => $l)
                            <option value="{{ $k }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Department</label>
                    <select name="department" required class="w-full px-2 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs">
                        @foreach($departments as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Target Level</label>
                    <select name="level" required class="w-full px-2 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs">
                        <option value="All">All Levels</option>
                        <option value="Level 1">Level 1</option>
                        <option value="Level 2">Level 2</option>
                        <option value="Level 3">Level 3</option>
                        <option value="Level 4">Level 4</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Question Details</label>
                <textarea name="content" rows="4" required placeholder="Describe your issue or request in detail..." class="w-full px-3 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"></textarea>
            </div>

            <div class="flex items-center space-x-2 text-xs">
                <input type="checkbox" name="is_anonymous" value="1" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500 dark:bg-slate-900 dark:border-slate-700">
                <span class="text-slate-700 dark:text-slate-300 font-semibold">Post Anonymously (Hide Name & ID)</span>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl font-bold bg-teal-700 text-white hover:bg-teal-800 text-xs uppercase shadow-md shadow-teal-700/20 dark:bg-teal-500 dark:text-slate-950 dark:hover:bg-teal-400">
                Post Question to Board
            </button>
        </form>
    </div>
</div>
@endsection
