@extends('layouts.app')

@section('title', ($roleLabel ?? 'Sign In') . ' - BAUST Nexus')

@section('content')
<div class="relative mx-auto -mt-3 max-w-md pt-0 pb-5 sm:pt-2 sm:pb-8">
    <div aria-hidden="true" class="pointer-events-none absolute -left-16 top-16 h-40 w-40 rounded-full bg-blue-300/20 blur-3xl dark:bg-blue-500/10"></div>
    <div aria-hidden="true" class="pointer-events-none absolute -right-12 bottom-12 h-40 w-40 rounded-full bg-sky-300/20 blur-3xl dark:bg-sky-500/10"></div>
    <div class="glass-card relative overflow-hidden rounded-3xl border border-blue-200/80 bg-white/95 p-6 shadow-2xl shadow-blue-950/10 backdrop-blur-xl dark:border-blue-500/20 dark:bg-slate-900/95 sm:p-8">
        <div aria-hidden="true" class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-blue-700 via-blue-500 to-sky-400"></div>
        <div class="relative text-center mb-7">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-700 via-blue-600 to-sky-500 text-white shadow-lg shadow-blue-600/25 ring-4 ring-blue-100 dark:ring-blue-500/10">
                <i class="fa-solid {{ $role === 'cafe_admin' ? 'fa-utensils' : ($role === 'library_admin' ? 'fa-book' : ($role === 'admin' ? 'fa-shield-halved' : 'fa-lock')) }} text-2xl text-white"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $roleLabel ? $roleLabel . ' Sign In' : 'Welcome Back' }}</h2>
            <p class="mt-1 text-xs font-semibold text-slate-600 dark:text-slate-400">Sign in to your BAUST Nexus Portal account</p>
        </div>

        <form action="{{ route($loginRoute) }}" method="POST" class="space-y-5" x-data="{ showPassword: false }">
            @csrf
            <div>
                <label for="login-email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Institutional Email</label>
                <div class="relative">
                    <span aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input id="login-email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" aria-label="Institutional email"
                        class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm font-semibold text-slate-900 transition placeholder:text-slate-400 hover:border-blue-300 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/15 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                </div>
                @error('email')
                    <span class="text-xs font-bold text-rose-600 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="login-password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <span aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-key"></i>
                    </span>
                    <input id="login-password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password" aria-label="Password"
                        class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-12 text-sm font-semibold text-slate-900 transition placeholder:text-slate-400 hover:border-blue-300 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/15 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <button type="button" @click="showPassword = !showPassword" :aria-label="showPassword ? 'Hide password' : 'Show password'" :aria-pressed="showPassword.toString()"
                        class="absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-500 transition hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-300">
                        <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center space-x-2 text-slate-700 dark:text-slate-300 font-semibold cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/30 dark:border-slate-700 dark:bg-slate-900">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 py-3.5 text-sm font-extrabold uppercase tracking-wide text-white shadow-lg shadow-blue-700/25 transition hover:-translate-y-0.5 hover:from-blue-800 hover:via-blue-700 hover:to-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/25 active:translate-y-0">
                {{ $roleLabel ? 'Continue to ' . $roleLabel : 'Sign In to BAUST Nexus' }}
            </button>
        </form>

        @if(!$role)
            <div class="mt-6 text-center text-xs font-semibold text-slate-600 dark:text-slate-400">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-extrabold text-blue-700 hover:text-blue-800 hover:underline dark:text-blue-300 dark:hover:text-blue-200">Register Now</a>
            </div>
        @endif

        <div class="mt-6 border-t border-blue-100 pt-5 dark:border-slate-700">
            <p class="text-center text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Staff sign-in portals</p>
            <div class="mt-3 grid grid-cols-1 gap-2 text-center text-[11px] font-extrabold sm:grid-cols-3">
                <a href="{{ route('login.admin') }}" class="group flex items-center justify-center gap-2 rounded-xl border border-blue-100 bg-blue-50 px-2.5 py-2.5 text-blue-800 transition hover:-translate-y-0.5 hover:border-blue-300 hover:bg-blue-100 hover:shadow-md hover:shadow-blue-900/5 focus:outline-none focus:ring-4 focus:ring-blue-500/15 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-200 dark:hover:bg-blue-500/20">
                    <i class="fa-solid fa-shield-halved text-blue-600 dark:text-blue-300"></i><span>Main Admin</span>
                </a>
                <a href="{{ route('login.cafe') }}" class="group flex items-center justify-center gap-2 rounded-xl border border-sky-100 bg-sky-50 px-2.5 py-2.5 text-sky-800 transition hover:-translate-y-0.5 hover:border-sky-300 hover:bg-sky-100 hover:shadow-md hover:shadow-sky-900/5 focus:outline-none focus:ring-4 focus:ring-sky-500/15 dark:border-sky-500/20 dark:bg-sky-500/10 dark:text-sky-200 dark:hover:bg-sky-500/20">
                    <i class="fa-solid fa-utensils text-sky-600 dark:text-sky-300"></i><span>Cafe Admin</span>
                </a>
                <a href="{{ route('login.library') }}" class="group flex items-center justify-center gap-2 rounded-xl border border-indigo-100 bg-indigo-50 px-2.5 py-2.5 text-indigo-800 transition hover:-translate-y-0.5 hover:border-indigo-300 hover:bg-indigo-100 hover:shadow-md hover:shadow-indigo-900/5 focus:outline-none focus:ring-4 focus:ring-indigo-500/15 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-200 dark:hover:bg-indigo-500/20">
                    <i class="fa-solid fa-book-open text-indigo-600 dark:text-indigo-300"></i><span>Library Admin</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
