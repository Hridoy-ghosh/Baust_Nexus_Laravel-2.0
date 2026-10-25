@extends('layouts.app')

@section('title', $post->title . ' - BAUST Nexus')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <a href="{{ route('queries.index') }}" class="inline-flex items-center text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-teal-700 dark:hover:text-teal-300 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Back to Query Board
    </a>

    <!-- Main Question Post Card -->
    <div class="glass-card p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-700/80 shadow-xl space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-teal-50 text-teal-800 border border-teal-200 dark:bg-teal-500/20 dark:text-teal-300 dark:border-teal-500/30">
                    {{ $post->department }}
                </span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $post->status === 'resolved' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30' : 'bg-amber-50 text-amber-800 border border-amber-200 dark:bg-amber-500/20 dark:text-amber-300 dark:border-amber-500/30' }}">
                    {{ $post->status }}
                </span>
            </div>

            <div class="flex items-center space-x-3 text-xs">
                @auth
                    @if(Auth::id() === $post->user_id || Auth::user()->isAdmin())
                        <form action="{{ route('queries.resolve', $post->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30 dark:hover:bg-emerald-500/30">
                                {{ $post->status === 'resolved' ? 'Reopen Query' : 'Mark Resolved' }}
                            </button>
                        </form>

                        <form action="{{ route('queries.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 dark:bg-rose-500/20 dark:text-rose-300 dark:border-rose-500/30 dark:hover:bg-rose-500/30">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>

        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white leading-snug">{{ $post->title }}</h1>

        <div class="p-4 bg-slate-50 dark:bg-slate-900/80 rounded-2xl border border-slate-200 dark:border-slate-800 text-sm text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line">
            {{ $post->content }}
        </div>

        <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600 dark:text-slate-400">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-teal-100 dark:bg-teal-600 flex items-center justify-center font-bold text-teal-800 dark:text-slate-950">
                    {{ $post->is_anonymous ? 'A' : strtoupper(substr($post->user->name ?? 'S', 0, 1)) }}
                </div>
                <div>
                    <div class="font-bold text-slate-900 dark:text-white">{{ $post->is_anonymous ? 'Anonymous Student' : ($post->user->name ?? 'Student') }}</div>
                    <div class="text-[10px] text-slate-600 dark:text-slate-400">{{ $post->is_anonymous ? 'Identity Hidden' : ($post->user->student_id ?? $post->user->role) }}</div>
                </div>
            </div>

            <span>Posted {{ $post->created_at->format('M d, Y - h:i A') }}</span>
        </div>
    </div>

    <!-- Reply Stream -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-comments text-teal-700 dark:text-teal-400"></i> Answers & Senior Guidance ({{ $post->replies->count() }})
        </h3>

        @foreach($post->replies as $reply)
            <div class="glass-card p-5 rounded-2xl border {{ $reply->is_accepted_answer ? 'border-emerald-300 bg-emerald-50 dark:border-emerald-500/50 dark:bg-emerald-950/20' : 'border-slate-200 dark:border-slate-700' }}">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-teal-700 dark:text-teal-400 text-xs border border-slate-200 dark:border-slate-700">
                            {{ $reply->is_anonymous ? 'A' : strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span>{{ $reply->is_anonymous ? 'Anonymous Member' : ($reply->user->name ?? 'User') }}</span>
                                @if($reply->role_badge)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ str_contains($reply->role_badge, 'Teacher') ? 'badge-teacher' : (str_contains($reply->role_badge, 'Senior') ? 'badge-student' : 'badge-admin') }}">
                                        {{ $reply->role_badge }}
                                    </span>
                                @endif

                                @if($reply->is_accepted_answer)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30">
                                        <i class="fa-solid fa-check-double mr-1"></i> Verified Answer
                                    </span>
                                @endif
                            </div>
                            <div class="text-[10px] text-slate-600 dark:text-slate-400">{{ $reply->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line pl-11">
                    {{ $reply->content }}
                </p>
            </div>
        @endforeach
    </div>

    <!-- Post Reply Box -->
    @auth
        <div class="glass-card p-6 rounded-3xl border border-slate-200 dark:border-slate-700/80">
            <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-3">Post Your Answer or Suggestion</h4>
            <form action="{{ route('queries.reply', $post->id) }}" method="POST" class="space-y-3">
                @csrf
                <textarea name="content" rows="3" required placeholder="Write clear guidance or response..." class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"></textarea>
                
                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="is_anonymous" value="1" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500 dark:bg-slate-900 dark:border-slate-700">
                        <span>Reply Anonymously</span>
                    </label>

                    <button type="submit" class="px-5 py-2.5 rounded-xl font-bold bg-teal-700 text-white hover:bg-teal-800 text-xs dark:bg-teal-500 dark:text-slate-950 dark:hover:bg-teal-400">
                        Submit Answer
                    </button>
                </div>
            </form>
        </div>
    @endauth

</div>
@endsection
