@extends('layouts.app')

@section('title', 'Academic Calendar 2026 - BAUST Nexus')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-sky-950 via-indigo-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-2xl space-y-4 border border-sky-500/30">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <img src="/images/baust_logo.png" alt="BAUST Emblem" class="w-12 h-12 object-contain">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight">OFFICIAL ACADEMIC CALENDAR 2026</h1>
                    <p class="text-xs text-sky-200">Bangladesh Army University of Science and Technology (BAUST), Saidpur</p>
                </div>
            </div>

            <button onclick="document.getElementById('calendarModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold bg-sky-500 hover:bg-sky-400 text-slate-950 transition shadow flex items-center gap-2">
                <i class="fa-solid fa-expand"></i> Enlarge Full Poster
            </button>
        </div>
    </div>

    <!-- Official Calendar Poster Image Card -->
    <div class="glass-card p-4 rounded-3xl border border-slate-200 dark:border-slate-800 text-center overflow-hidden cursor-pointer group shadow-xl" onclick="document.getElementById('calendarModal').classList.remove('hidden')">
        <img src="/images/academic_calendar.png" alt="BAUST Academic Calendar 2026" class="w-full h-auto rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl object-contain max-h-[750px] group-hover:scale-[1.01] transition-transform duration-300">
        <div class="mt-3 text-xs text-blue-600 dark:text-sky-400 font-bold flex items-center justify-center gap-1.5">
            <i class="fa-solid fa-magnifying-glass-plus"></i> Click image to open high-resolution fullscreen calendar
        </div>
    </div>

    <!-- List of Holidays 2026 & Departmental Contacts Grid (Matching Poster Footer) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- 1. Government & Optional Holidays -->
        <div class="glass-card p-6 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-4 hover-lift">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-3">
                <i class="fa-solid fa-calendar-days text-rose-500"></i> List of Holidays 2026
            </h3>

            <div class="space-y-3 text-xs">
                <div>
                    <strong class="text-rose-600 dark:text-rose-400 block uppercase mb-2 font-black">Government Holidays:</strong>
                    <ul class="space-y-2 text-slate-700 dark:text-slate-300">
                        <li class="flex items-center gap-2"><span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 font-bold font-mono">04 Feb:</span> Shab-E-Barat</li>
                        <li class="flex items-center gap-2"><span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 font-bold font-mono">21 Feb:</span> Shahid Dibosh & International Mother Language Day</li>
                        <li class="flex items-center gap-2"><span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 font-bold font-mono">17 Mar:</span> Shab-E-Qadar</li>
                        <li class="flex items-center gap-2"><span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 font-bold font-mono">19-23 Mar:</span> Jumatul Bida & Eid-Ul-Fitr</li>
                        <li class="flex items-center gap-2"><span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 font-bold font-mono">26 Mar:</span> Independence & National Day</li>
                        <li class="flex items-center gap-2"><span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 font-bold font-mono">14 Apr:</span> Bengali New Year</li>
                        <li class="flex items-center gap-2"><span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 font-bold font-mono">01 May:</span> May Day & Buddha Purnima</li>
                        <li class="flex items-center gap-2"><span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 font-bold font-mono">26-31 May:</span> Eid-Ul-Azha Vacation</li>
                        <li class="flex items-center gap-2"><span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 font-bold font-mono">16 Dec:</span> Victory Day | <span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 font-bold font-mono">25 Dec:</span> Christmas Day</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- 2. Departmental Helplines -->
        <div class="glass-card p-6 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-4 hover-lift">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-3">
                <i class="fa-solid fa-phone-volume text-blue-600 dark:text-sky-400"></i> Departmental Helplines
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <!-- CSE -->
                <a href="tel:01769675560" class="p-3.5 rounded-2xl bg-gradient-to-r from-blue-500/10 via-indigo-500/10 to-blue-500/5 hover:from-blue-500/20 hover:to-indigo-500/20 border border-blue-500/30 flex items-center justify-between group transition-all duration-200 shadow-sm">
                    <div>
                        <span class="text-blue-600 dark:text-blue-400 font-extrabold block text-sm">CSE Dept</span>
                        <span class="text-slate-800 dark:text-slate-200 font-bold font-mono">01769675560</span>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md">
                        <i class="fa-solid fa-phone text-xs"></i>
                    </div>
                </a>

                <!-- EEE -->
                <a href="tel:01769675590" class="p-3.5 rounded-2xl bg-gradient-to-r from-purple-500/10 via-violet-500/10 to-purple-500/5 hover:from-purple-500/20 hover:to-violet-500/20 border border-purple-500/30 flex items-center justify-between group transition-all duration-200 shadow-sm">
                    <div>
                        <span class="text-purple-600 dark:text-purple-400 font-extrabold block text-sm">EEE Dept</span>
                        <span class="text-slate-800 dark:text-slate-200 font-bold font-mono">01769675590</span>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md">
                        <i class="fa-solid fa-phone text-xs"></i>
                    </div>
                </a>

                <!-- ME -->
                <a href="tel:01769675564" class="p-3.5 rounded-2xl bg-gradient-to-r from-emerald-500/10 via-teal-500/10 to-emerald-500/5 hover:from-emerald-500/20 hover:to-teal-500/20 border border-emerald-500/30 flex items-center justify-between group transition-all duration-200 shadow-sm">
                    <div>
                        <span class="text-emerald-600 dark:text-emerald-400 font-extrabold block text-sm">ME Dept</span>
                        <span class="text-slate-800 dark:text-slate-200 font-bold font-mono">01769675564</span>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md">
                        <i class="fa-solid fa-phone text-xs"></i>
                    </div>
                </a>

                <!-- IPE -->
                <a href="tel:01769675566" class="p-3.5 rounded-2xl bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-amber-500/5 hover:from-amber-500/20 hover:to-orange-500/20 border border-amber-500/30 flex items-center justify-between group transition-all duration-200 shadow-sm">
                    <div>
                        <span class="text-amber-600 dark:text-amber-400 font-extrabold block text-sm">IPE Dept</span>
                        <span class="text-slate-800 dark:text-slate-200 font-bold font-mono">01769675566</span>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center group-hover:scale-110 transition-transform shadow-md">
                        <i class="fa-solid fa-phone text-xs"></i>
                    </div>
                </a>

                <!-- CE -->
                <a href="tel:01769672720" class="p-3.5 rounded-2xl bg-gradient-to-r from-cyan-500/10 via-sky-500/10 to-cyan-500/5 hover:from-cyan-500/20 hover:to-sky-500/20 border border-cyan-500/30 flex items-center justify-between group transition-all duration-200 shadow-sm">
                    <div>
                        <span class="text-cyan-600 dark:text-cyan-400 font-extrabold block text-sm">CE Dept</span>
                        <span class="text-slate-800 dark:text-slate-200 font-bold font-mono">01769672720</span>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-cyan-600 text-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md">
                        <i class="fa-solid fa-phone text-xs"></i>
                    </div>
                </a>

                <!-- BBA / MBA -->
                <a href="tel:01769675568" class="p-3.5 rounded-2xl bg-gradient-to-r from-rose-500/10 via-pink-500/10 to-rose-500/5 hover:from-rose-500/20 hover:to-pink-500/20 border border-rose-500/30 flex items-center justify-between group transition-all duration-200 shadow-sm">
                    <div>
                        <span class="text-rose-600 dark:text-rose-400 font-extrabold block text-sm">BBA / MBA</span>
                        <span class="text-slate-800 dark:text-slate-200 font-bold font-mono">01769675568</span>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md">
                        <i class="fa-solid fa-phone text-xs"></i>
                    </div>
                </a>
            </div>
        </div>

    </div>

</div>

<!-- Fullscreen Calendar Lightbox Modal -->
<div id="calendarModal" class="hidden fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-md flex items-center justify-center p-4">
    <div class="max-w-5xl w-full relative">
        <button onclick="document.getElementById('calendarModal').classList.add('hidden')" class="absolute -top-10 right-0 text-white hover:text-sky-400 text-2xl font-bold">
            <i class="fa-solid fa-xmark"></i> Close
        </button>
        <img src="/images/academic_calendar.png" alt="BAUST Calendar 2026 Full View" class="w-full h-auto rounded-3xl border border-white/20 shadow-2xl max-h-[85vh] object-contain">
    </div>
</div>
@endsection
