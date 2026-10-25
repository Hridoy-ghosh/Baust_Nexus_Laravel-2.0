@extends('layouts.app')

@section('title', 'Guest Mode - BAUST Nexus')

@section('content')
<div class="max-w-xl mx-auto py-6 space-y-6 text-center">

    <!-- Guest Mode Header (Matching Screenshot 1) -->
    <div class="flex flex-col items-center justify-center space-y-3">
        <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-blue-600 to-indigo-600 shadow-xl shadow-blue-600/30 flex items-center justify-center border border-white/20">
            <i class="fa-solid fa-graduation-cap text-4xl text-white"></i>
        </div>

        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">BAUST Nexus</h1>
        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">Guest Mode - Limited Access</span>
    </div>

    <!-- Info Notice Box -->
    <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-700 dark:text-amber-300 text-xs font-semibold flex items-center space-x-3 text-left shadow-sm">
        <i class="fa-solid fa-circle-info text-amber-500 text-lg"></i>
        <span>You can view public info & order food. Login for full access.</span>
    </div>

    <!-- 4 Big Square Grid Cards (Matching Screenshot 1) -->
    <div class="grid grid-cols-2 gap-4 pt-2">
        
        <!-- Public Notices -->
        <a href="{{ route('notices.index') }}" class="glass-card p-6 rounded-3xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center space-y-3 hover-lift group shadow-lg">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-500 text-2xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <span class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-amber-500 transition">Public Notices</span>
        </a>

        <!-- Library Info -->
        <a href="{{ route('library.index') }}" class="glass-card p-6 rounded-3xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center space-y-3 hover-lift group shadow-lg">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-500 text-2xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
            <span class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-emerald-500 transition">Library Info</span>
        </a>

        <!-- Bus Schedule -->
        <a href="{{ route('transport.index') }}" class="glass-card p-6 rounded-3xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center space-y-3 hover-lift group shadow-lg">
            <div class="w-14 h-14 rounded-2xl bg-sky-500/10 border border-sky-500/30 flex items-center justify-center text-sky-500 text-2xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-bus"></i>
            </div>
            <span class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-sky-500 transition">Bus Schedule</span>
        </a>

        <!-- Cafeteria Menu -->
        <a href="{{ route('cafe.index') }}" class="glass-card p-6 rounded-3xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center space-y-3 hover-lift group shadow-lg">
            <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-center text-rose-500 text-2xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <span class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-rose-500 transition">Cafeteria Menu</span>
        </a>

    </div>

    <!-- Login for Full Access Button -->
    <div class="pt-4">
        <a href="{{ route('login') }}" class="w-full py-4 rounded-2xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-600 hover:from-blue-500 hover:to-emerald-500 text-white transition-all shadow-lg shadow-blue-600/30 text-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-right-to-bracket"></i> Login for Full Access
        </a>
    </div>

</div>
@endsection
