@extends('layouts.app')

@section('title', 'Students Dress Code - BAUST Nexus')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-2xl space-y-5 border border-blue-500/30">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <img src="/images/baust_logo.png" alt="BAUST Logo" class="w-12 h-12 object-contain">
                <div>
                    <h1 class="text-xl md:text-2xl font-extrabold tracking-tight">BANGLADESH ARMY UNIVERSITY OF SCIENCE AND TECHNOLOGY</h1>
                    <p class="text-xs text-blue-200 uppercase tracking-widest mt-0.5">Saidpur, Nilphamari • Official Students' Dress Code Policy</p>
                </div>
            </div>

            <span class="px-4 py-1.5 rounded-full text-xs font-extrabold bg-blue-500 text-white shadow-md flex items-center gap-1.5 self-start md:self-auto">
                <i class="fa-solid fa-shield-halved"></i> Official Notice
            </span>
        </div>

        <p class="text-xs md:text-sm text-slate-200 leading-relaxed bg-slate-950/60 p-4 rounded-2xl border border-slate-800/80">
            <i class="fa-solid fa-circle-info text-blue-400 mr-1.5"></i>
            Students are to wear dress with displayed identity card as per <strong>"Dress Code"</strong> prescribed by BAUST authority.
        </p>

        <!-- Official BAUST Dress Code Image Showcase (Exact Poster from Authority) -->
        <div class="relative overflow-hidden rounded-2xl border border-blue-400/30 bg-slate-950/80 shadow-2xl group cursor-pointer" onclick="openModal('officialPosterModal')">
            <div class="p-2 md:p-4 flex justify-center bg-slate-950/60">
                <img src="/images/dress_code_official.jpg" alt="BAUST Official Students Dress Code Poster" class="w-full max-h-[480px] md:max-h-[560px] object-contain rounded-xl transition-transform duration-300 group-hover:scale-[1.01]">
            </div>
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/95 via-slate-950/60 to-transparent p-4 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-image text-blue-400"></i> Official BAUST Dress Code Notice Poster
                </span>
                <span class="bg-blue-600 hover:bg-blue-500 px-3 py-1.5 rounded-xl text-xs font-extrabold text-white shadow flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-expand"></i> View Full Poster
                </span>
            </div>
        </div>
    </div>

    <!-- Official Dress Code Specification Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- 1. Male Student Specifications -->
        <div class="glass-card rounded-3xl border border-slate-200 dark:border-slate-800/80 overflow-hidden flex flex-col justify-between hover-lift shadow-xl">
            <div class="p-6 space-y-5">
                <div class="flex items-center space-x-3 pb-3 border-b border-slate-200 dark:border-slate-800">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/20 border border-blue-300 dark:border-blue-500/30 flex items-center justify-center text-blue-700 dark:text-blue-400 text-lg font-bold">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Male Student Dress Code</h3>
                        <span class="text-[11px] font-bold text-slate-600 dark:text-slate-400">Summer & Winter Specifications</span>
                    </div>
                </div>

                <!-- Summer -->
                <div class="p-4 bg-slate-50 dark:bg-slate-900/80 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center space-x-2 text-amber-700 dark:text-amber-400 font-extrabold text-xs uppercase">
                            <i class="fa-solid fa-sun"></i> <span>Summer Season</span>
                        </span>
                        <span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/10 text-amber-800 dark:text-amber-300 text-[10px] font-bold border border-amber-300 dark:border-amber-500/30">Half Sleeve</span>
                    </div>
                    <p class="text-slate-800 dark:text-slate-200 leading-relaxed font-semibold">
                        Ash coloured half sleeve shirt (tucked in), Black coloured fullpants, Black Oxford shoes and Black socks.
                    </p>
                </div>

                <!-- Winter -->
                <div class="p-4 bg-slate-50 dark:bg-slate-900/80 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center space-x-2 text-sky-700 dark:text-sky-400 font-extrabold text-xs uppercase">
                            <i class="fa-solid fa-snowflake"></i> <span>Winter Season</span>
                        </span>
                        <span class="px-2 py-0.5 rounded-md bg-sky-100 dark:bg-sky-500/10 text-sky-800 dark:text-sky-300 text-[10px] font-bold border border-sky-300 dark:border-sky-500/30">With Blazer</span>
                    </div>
                    <p class="text-slate-800 dark:text-slate-200 leading-relaxed font-semibold">
                        Ash coloured full sleeve shirt (tucked in), Black coloured full pants, Blue coloured blazer, Black Oxford shoes and Black socks.
                    </p>
                </div>
            </div>

            <div class="p-4 bg-blue-50 dark:bg-blue-950/30 border-t border-slate-200 dark:border-slate-800 text-[11px] text-slate-700 dark:text-slate-400 text-center font-bold">
                <i class="fa-solid fa-id-card text-blue-600 dark:text-blue-400 mr-1"></i> Display Identity Card at all times on campus.
            </div>
        </div>

        <!-- 2. Female Student Specifications -->
        <div class="glass-card rounded-3xl border border-slate-200 dark:border-slate-800/80 overflow-hidden flex flex-col justify-between hover-lift shadow-xl">
            <div class="p-6 space-y-5">
                <div class="flex items-center space-x-3 pb-3 border-b border-slate-200 dark:border-slate-800">
                    <div class="w-10 h-10 rounded-xl bg-pink-100 dark:bg-pink-500/20 border border-pink-300 dark:border-pink-500/30 flex items-center justify-center text-pink-700 dark:text-pink-400 text-lg font-bold">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Female Student Dress Code</h3>
                        <span class="text-[11px] font-bold text-slate-600 dark:text-slate-400">Summer & Winter Specifications</span>
                    </div>
                </div>

                <!-- Summer -->
                <div class="p-4 bg-slate-50 dark:bg-slate-900/80 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center space-x-2 text-amber-700 dark:text-amber-400 font-extrabold text-xs uppercase">
                            <i class="fa-solid fa-sun"></i> <span>Summer Season</span>
                        </span>
                        <span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/10 text-amber-800 dark:text-amber-300 text-[10px] font-bold border border-amber-300 dark:border-amber-500/30">3/4 Sleeve Kamiz</span>
                    </div>
                    <p class="text-slate-800 dark:text-slate-200 leading-relaxed font-semibold">
                        Ash coloured three quarter sleeve Kamiz, White coloured Sallowar and Dopatta, Black ladies shoes and socks.
                    </p>
                </div>

                <!-- Winter -->
                <div class="p-4 bg-slate-50 dark:bg-slate-900/80 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center space-x-2 text-sky-700 dark:text-sky-400 font-extrabold text-xs uppercase">
                            <i class="fa-solid fa-snowflake"></i> <span>Winter Season</span>
                        </span>
                        <span class="px-2 py-0.5 rounded-md bg-sky-100 dark:bg-sky-500/10 text-sky-800 dark:text-sky-300 text-[10px] font-bold border border-sky-300 dark:border-sky-500/30">With Blazer</span>
                    </div>
                    <p class="text-slate-800 dark:text-slate-200 leading-relaxed font-semibold">
                        Ash coloured three quarter sleeve Kamiz, White coloured Sallowar and Dopatta, Blue coloured blazer, Black ladies shoes and socks.
                    </p>
                </div>
            </div>

            <div class="p-4 bg-pink-50 dark:bg-pink-950/30 border-t border-slate-200 dark:border-slate-800 text-[11px] text-slate-700 dark:text-slate-400 text-center font-bold">
                <i class="fa-solid fa-id-card text-pink-600 dark:text-pink-400 mr-1"></i> Display Identity Card at all times on campus.
            </div>
        </div>

    </div>

    <!-- Official Contact & Authority Footer Bar -->
    <div class="glass-card p-6 rounded-3xl border border-blue-300 dark:border-blue-500/30 text-center space-y-3">
        <div class="flex flex-wrap items-center justify-center gap-6 text-xs font-extrabold text-slate-700 dark:text-slate-300">
            <span class="flex items-center gap-1.5"><i class="fa-solid fa-phone text-emerald-600 dark:text-emerald-400"></i> BAUST Helpline: 
                <a href="tel:01769675588" class="text-slate-900 dark:text-white hover:underline">01769675588</a>, 
                <a href="tel:01769675554" class="text-slate-900 dark:text-white hover:underline">01769675554</a>, 
                <a href="tel:01786278922" class="text-slate-900 dark:text-white hover:underline">01786278922</a>
            </span>
            <span class="flex items-center gap-1.5"><i class="fa-solid fa-globe text-blue-600 dark:text-blue-400"></i> Official Website: 
                <a href="https://www.baust.edu.bd" target="_blank" class="text-blue-700 dark:text-blue-400 hover:underline">www.baust.edu.bd</a>
            </span>
        </div>
    </div>

</div>

<!-- Official Poster Lightbox Modal -->
<div id="officialPosterModal" class="hidden fixed inset-0 z-50 bg-slate-950/95 backdrop-blur-md flex items-center justify-center p-4">
    <div class="max-w-6xl w-full relative space-y-3">
        <div class="flex items-center justify-between text-white border-b border-slate-800 pb-3">
            <h3 class="text-sm font-extrabold flex items-center gap-2 text-blue-400">
                <i class="fa-solid fa-image"></i> BAUST Official Students' Dress Code Poster
            </h3>
            <button onclick="closeModal('officialPosterModal')" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-xmark"></i> Close
            </button>
        </div>
        <div class="flex justify-center bg-slate-900/90 p-3 rounded-2xl border border-slate-800">
            <img src="/images/dress_code_official.jpg" alt="BAUST Official Students Dress Code Notice Poster" class="w-auto max-w-full rounded-xl shadow-2xl max-h-[85vh] object-contain">
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endsection
