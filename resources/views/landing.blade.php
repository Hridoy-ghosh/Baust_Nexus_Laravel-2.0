@extends('layouts.app')

@section('title', 'Welcome to BAUST Nexus')

@section('content')
<div class="space-y-14 pb-10">
    <section class="relative isolate overflow-hidden rounded-[2rem] border border-blue-200 bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950 px-6 py-10 text-white shadow-2xl shadow-blue-950/20 sm:px-10 sm:py-14 lg:px-14">
            <div class="pointer-events-none absolute -right-20 -top-24 h-80 w-80 rounded-full bg-sky-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-36 left-1/4 h-80 w-80 rounded-full bg-indigo-500/25 blur-3xl"></div>
            <div class="relative grid items-center gap-9 lg:grid-cols-[1.05fr_.95fr]">
                <div class="relative z-10">
                    <span class="inline-flex items-center gap-2 rounded-full border border-blue-200/25 bg-white/10 px-4 py-2 text-xs font-bold tracking-wide text-blue-100 backdrop-blur">
                        <img src="/images/baust_logo.png" alt="" class="h-5 w-5 object-contain">
                        BANGLADESH ARMY UNIVERSITY OF SCIENCE AND TECHNOLOGY
                    </span>
                    <h1 class="mt-7 max-w-3xl text-4xl font-extrabold leading-[1.08] tracking-tight sm:text-5xl lg:text-6xl">
                        One campus.
                        <span class="block bg-gradient-to-r from-sky-300 via-blue-200 to-indigo-300 bg-clip-text text-transparent">Everything in reach.</span>
                    </h1>
                    <p class="mt-6 max-w-xl text-base leading-8 text-blue-100/85 sm:text-lg">
                        Welcome to BAUST Nexus — a simpler way to find learning resources, campus updates and the services that support university life.
                    </p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-400 px-6 py-3.5 text-sm font-extrabold text-blue-950 shadow-lg shadow-blue-400/20 transition hover:-translate-y-0.5 hover:bg-blue-300">
                            <i class="fa-solid fa-right-to-bracket"></i> Sign in to your account
                        </a>
                        <a href="{{ route('guest') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-6 py-3.5 text-sm font-bold text-white transition hover:border-white/35 hover:bg-white/15">
                            <i class="fa-solid fa-compass"></i> Explore guest mode
                        </a>
                    </div>
                    <p class="mt-5 flex items-center gap-2 text-xs font-medium text-blue-100/70">
                        <i class="fa-solid fa-lock"></i> Sign in for your private campus tools and account.
                    </p>
                </div>

                <div class="relative">
                    <div class="absolute -inset-3 rounded-[2rem] bg-gradient-to-br from-sky-400/30 to-indigo-400/20 blur-xl"></div>
                    <div class="group relative overflow-hidden rounded-[1.6rem] border border-white/20 bg-white/10 p-2 shadow-2xl backdrop-blur">
                        <img src="/images/academic_building.jpeg" alt="BAUST academic building" class="h-64 w-full rounded-[1.2rem] object-cover transition duration-700 group-hover:scale-[1.03] sm:h-80">
                        <div class="absolute inset-x-2 bottom-2 rounded-b-[1.2rem] bg-gradient-to-t from-blue-950/95 via-blue-950/60 to-transparent p-5 pt-16 sm:p-6 sm:pt-20">
                            <p class="text-xs font-bold uppercase tracking-[.18em] text-sky-200">The campus, connected</p>
                            <h2 class="mt-1 text-lg font-extrabold text-white sm:text-xl">Made for BAUST campus life</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-8 lg:grid-cols-[.85fr_1.15fr] lg:items-center">
        <div>
            <span class="text-xs font-extrabold uppercase tracking-[.2em] text-blue-700 dark:text-blue-300">About BAUST Nexus</span>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">Your campus, a little easier to navigate.</h2>
        </div>
        <div class="space-y-4 text-sm leading-7 text-slate-600 dark:text-slate-300 sm:text-base">
            <p>BAUST Nexus brings useful academic and campus services into a single, easy-to-navigate experience for students, teachers and university administrators.</p>
            <p>Visitors can preview a small set of public campus information in guest mode. Student records, learning materials and management tools remain available only after the appropriate account signs in.</p>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-lg shadow-slate-900/5 dark:border-slate-700 dark:bg-slate-900 sm:p-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[.18em] text-slate-500 dark:text-slate-400">Dedicated staff access</p>
                <h2 class="mt-2 text-2xl font-extrabold text-slate-900 dark:text-white">Sign in to the right workspace</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Each administrator uses their own BAUST account and is taken to their assigned area.</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-3">
                <a href="{{ route('login.admin') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-xs font-extrabold text-blue-900 transition hover:bg-blue-100 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-200 dark:hover:bg-blue-500/20">
                    <i class="fa-solid fa-shield-halved"></i> Main Admin
                </a>
                <a href="{{ route('login.cafe') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-xs font-extrabold text-sky-900 transition hover:bg-sky-100 dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-200 dark:hover:bg-sky-500/20">
                    <i class="fa-solid fa-utensils"></i> Cafe Admin
                </a>
                <a href="{{ route('login.library') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3 text-xs font-extrabold text-indigo-900 transition hover:bg-indigo-100 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-200 dark:hover:bg-indigo-500/20">
                    <i class="fa-solid fa-book"></i> Library Admin
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
