@extends('layouts.app')

@section('title', 'BAUST Official Transport Schedule - BAUST Nexus')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- Official Header Banner (Matching Official BAUST Transport Poster) -->
    <div class="bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-2xl space-y-4 border border-emerald-500/30">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <img src="/images/baust_logo.png" alt="BAUST Emblem" class="w-12 h-12 object-contain">
                <div>
                    <h1 class="text-xl md:text-2xl font-extrabold tracking-tight">BANGLADESH ARMY UNIVERSITY OF SCIENCE AND TECHNOLOGY</h1>
                    <p class="text-xs text-emerald-300 font-semibold uppercase tracking-widest mt-0.5">Official Campus Transport Facility & Stoppage Schedule</p>
                </div>
            </div>

            <span class="px-4 py-1.5 rounded-full text-xs font-extrabold bg-emerald-500 text-slate-950 shadow-md">
                <i class="fa-solid fa-bus mr-1"></i> Official Schedule
            </span>
        </div>

        <!-- Bus Depot Hero Photo -->
        <div class="relative overflow-hidden rounded-2xl border border-emerald-500/30 bg-slate-950/70 shadow-2xl group cursor-pointer" onclick="document.getElementById('busImageModal').classList.remove('hidden')">
            <div class="p-2 md:p-4 flex justify-center bg-slate-950/50">
                <img src="/images/bus.jpeg" alt="BAUST Bus Fleet Garage" class="w-full max-h-[380px] md:max-h-[460px] object-contain rounded-xl transition-transform duration-300 group-hover:scale-[1.01]">
            </div>
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/95 via-slate-950/60 to-transparent flex items-end p-4">
                <div class="flex items-center justify-between w-full text-xs font-bold text-white">
                    <span><i class="fa-solid fa-circle-info text-emerald-400 mr-2"></i> BAUST Bus Fleet Garage & Transport Terminal</span>
                    <span class="bg-emerald-500 hover:bg-emerald-400 px-3 py-1.5 rounded-xl text-slate-950 font-extrabold shadow flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-expand"></i> View Full Photo
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Official 3 Route Schedule Tables (Matching Poster Columns) -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-table-list text-emerald-500"></i> Official Stoppage & Fare Schedule Tables
            </h2>

            @auth
                @if(Auth::user()->isAdmin())
                    <button onclick="document.getElementById('routeModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold bg-emerald-500 hover:bg-emerald-400 text-slate-950 shadow-md">
                        + Add Route
                    </button>
                @endif
            @endauth
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- 1. Nilphamari Route Column -->
            <div class="glass-card rounded-3xl border border-emerald-500/40 overflow-hidden flex flex-col justify-between hover-lift shadow-lg">
                <div>
                    <div class="bg-gradient-to-r from-emerald-700 via-teal-700 to-emerald-800 p-4 text-white text-center border-b border-emerald-600">
                        <h3 class="text-base font-extrabold">বিএইউএসটি টু নীলফামারী</h3>
                        <div class="text-xs text-emerald-200 mt-0.5 font-bold">সময়: সকাল ৭.০০ ঘটিকা (7:00 AM)</div>
                    </div>

                    <div class="p-4 space-y-1.5 text-xs">
                        <div class="flex justify-between p-2 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">চৌরঙ্গী</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.১০ মি. (৪০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-2 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">কালিবাড়ী</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.১৫ মি. (৪০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-2 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">দারোয়ানি</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.২০ মি. (৪০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-2 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">কাজির হাট</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.২৫ মি. (৪০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-2 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">টেলাপীর</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.৩০ মি. (২০ টাকা)</span>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border-t border-emerald-200 dark:border-emerald-800/50 text-[11px] text-slate-700 dark:text-slate-300 text-center font-bold">
                    <i class="fa-solid fa-phone text-emerald-600 dark:text-emerald-400 mr-1"></i> Driver: <a href="tel:01725618750" class="text-emerald-600 dark:text-emerald-400 hover:underline">01725-618750</a>
                </div>
            </div>

            <!-- 2. Rangpur Route Column -->
            <div class="glass-card rounded-3xl border border-emerald-500/40 overflow-hidden flex flex-col justify-between hover-lift shadow-lg">
                <div>
                    <div class="bg-gradient-to-r from-emerald-700 via-teal-700 to-emerald-800 p-4 text-white text-center border-b border-emerald-600">
                        <h3 class="text-base font-extrabold">বিএইউএসটি টু রংপুর</h3>
                        <div class="text-xs text-emerald-200 mt-0.5 font-bold">সময়: সকাল ৬.৩০ ঘটিকা (6:30 AM)</div>
                    </div>

                    <div class="p-4 space-y-1.5 text-xs">
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">শাপলা</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৬.৩০ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">জাহাজ কোম্পানি</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৬.৩৫ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">পায়রা</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৬.৩৭ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">চেক পোস্ট</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৬.৫০ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">মেডিকেল মোড়</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.০০ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">সিও বাজার</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.০৫ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">হাসনা</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.১০ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">হাজীর হাট</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.১৫ মি. (৬০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">পাগলাপীর</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.২৫ মি. (৫০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">ইকরচালী</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.৩৫ মি. (৩৫ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">তারাগঞ্জ</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.৪৫ মি. (২৫ টাকা)</span>
                        </div>
                    </div>

                    <div class="p-2 bg-amber-500/10 text-amber-700 dark:text-amber-300 border-t border-amber-500/30 text-[11px] text-center font-extrabold">
                        বিএইউএসটি থেকে ছাড়ার সময়: ২.৩৫ মিনিট (2:35 PM)
                    </div>
                </div>

                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border-t border-emerald-200 dark:border-emerald-800/50 text-[11px] text-slate-700 dark:text-slate-300 text-center font-bold">
                    <i class="fa-solid fa-phone text-emerald-600 dark:text-emerald-400 mr-1"></i> Drivers: <a href="tel:01728656973" class="text-emerald-600 dark:text-emerald-400 hover:underline">01728-656973</a>, <a href="tel:01794112243" class="text-emerald-600 dark:text-emerald-400 hover:underline">01794-112243</a>
                </div>
            </div>

            <!-- 3. Dinajpur Route Column -->
            <div class="glass-card rounded-3xl border border-emerald-500/40 overflow-hidden flex flex-col justify-between hover-lift shadow-lg">
                <div>
                    <div class="bg-gradient-to-r from-emerald-700 via-teal-700 to-emerald-800 p-4 text-white text-center border-b border-emerald-600">
                        <h3 class="text-base font-extrabold">বিএইউএসটি টু দিনাজপুর</h3>
                        <div class="text-xs text-emerald-200 mt-0.5 font-bold">সময়: সকাল ৬.২০ ঘটিকা (6:20 AM)</div>
                    </div>

                    <div class="p-4 space-y-1.5 text-xs">
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">সুইহারী তেলপাম্প</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৬.২০ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">মর্ডান মোড়</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৬.২২ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">লিলির মোড়</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৬.২৬ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">মেডিকেল মোড়</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৬.২৮ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">মহারাজার মোড়</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৬.৩০ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">দিনাজপুর টার্মিনাল</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৬.৩৫ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">কলেজ মোড়</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৬.৪০ মি. (৭০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">বাঁশেরহাট</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৬.৫০ মি. (৬০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">দশ মাইল</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.০০ মি. (৪৫ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">ভূষির বন্দর</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.০৫ মি. (৪০ টাকা)</span>
                        </div>
                        <div class="flex justify-between p-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                            <span class="font-bold text-slate-800 dark:text-white">রাণীর বন্দর</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">৭.১০ মি. (২৫ টাকা)</span>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border-t border-emerald-200 dark:border-emerald-800/50 text-[11px] text-slate-700 dark:text-slate-300 text-center font-bold">
                    <i class="fa-solid fa-phone text-emerald-600 dark:text-emerald-400 mr-1"></i> Driver: <a href="tel:01706811221" class="text-emerald-600 dark:text-emerald-400 hover:underline">01706-811221</a>
                </div>
            </div>

        </div>
    </div>

    <!-- Official Driver & Transport Contact Box (Matching Poster Footer Exactly) -->
    <div class="glass-card p-6 md:p-8 rounded-3xl border border-indigo-500/40 text-slate-900 dark:text-white shadow-2xl text-center space-y-4">
        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30 text-xs font-bold uppercase">
            <i class="fa-solid fa-phone-volume"></i>
            <span>BAUST Official Transport Contacts</span>
        </div>

        <div class="space-y-2 text-sm md:text-base font-extrabold">
            <p>
                <i class="fa-solid fa-user-tie text-emerald-600 dark:text-emerald-400 mr-1.5"></i> Md. Omar Faruk: 
                <a href="tel:01303895114" class="text-amber-600 dark:text-amber-400 hover:underline">01303-895114</a>, 
                <a href="tel:01717310081" class="text-amber-600 dark:text-amber-400 hover:underline">01717-310081</a>
            </p>

            <div class="pt-3 border-t border-slate-200 dark:border-slate-800 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs md:text-sm font-bold">
                <div class="p-3 bg-slate-100 dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700">
                    <span class="text-amber-600 dark:text-amber-400 block mb-0.5"><i class="fa-solid fa-id-badge mr-1"></i> Dinajpur Driver</span>
                    <a href="tel:01706811221" class="text-slate-800 dark:text-white hover:text-emerald-500 font-mono">01706-811221</a>
                </div>
                <div class="p-3 bg-slate-100 dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700">
                    <span class="text-amber-600 dark:text-amber-400 block mb-0.5"><i class="fa-solid fa-id-badge mr-1"></i> Rangpur Drivers</span>
                    <a href="tel:01728656973" class="text-slate-800 dark:text-white hover:text-emerald-500 font-mono">01728-656973</a>, 
                    <a href="tel:01794112243" class="text-slate-800 dark:text-white hover:text-emerald-500 font-mono">01794-112243</a>
                </div>
                <div class="p-3 bg-slate-100 dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700">
                    <span class="text-amber-600 dark:text-amber-400 block mb-0.5"><i class="fa-solid fa-id-badge mr-1"></i> Nilphamari Driver</span>
                    <a href="tel:01725618750" class="text-slate-800 dark:text-white hover:text-emerald-500 font-mono">01725-618750</a>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Bus Image Fullscreen Lightbox Modal -->
<div id="busImageModal" class="hidden fixed inset-0 z-50 bg-slate-950/95 backdrop-blur-md flex items-center justify-center p-4">
    <div class="max-w-5xl w-full relative space-y-3">
        <div class="flex items-center justify-between text-white border-b border-slate-800 pb-3">
            <h3 class="text-sm font-extrabold flex items-center gap-2 text-emerald-400">
                <i class="fa-solid fa-bus"></i> BAUST Official Transport Terminal & Fleet Garage
            </h3>
            <button onclick="document.getElementById('busImageModal').classList.add('hidden')" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-xmark"></i> Close
            </button>
        </div>
        <div class="flex justify-center bg-slate-900/80 p-2 rounded-2xl border border-slate-800">
            <img src="/images/bus.jpeg" alt="BAUST Bangladesh Army Bus Fleet" class="w-auto max-w-full rounded-xl shadow-2xl max-h-[82vh] object-contain">
        </div>
    </div>
</div>

<!-- Route Modal -->
<div id="routeModal" class="hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="glass-card max-w-lg w-full rounded-3xl p-6 border border-slate-700 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-bus text-emerald-400"></i> Add Bus Route
            </h3>
            <button onclick="document.getElementById('routeModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('transport.store') }}" method="POST" class="space-y-3">
            @csrf
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Route Code</label>
                    <input type="text" name="route_code" required placeholder="R-05" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Bus Number</label>
                    <input type="text" name="bus_number" placeholder="BAUST-05" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Route Name</label>
                <input type="text" name="route_name" required placeholder="e.g. BAUST Dinajpur Route" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Morning Schedule</label>
                    <input type="text" name="morning_schedule" required placeholder="6:20 AM" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Afternoon Schedule</label>
                    <input type="text" name="afternoon_schedule" required placeholder="2:35 PM" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Pickup Points & Fare</label>
                <input type="text" name="pickup_points" required placeholder="Point A (50 Tk) -> Point B (30 Tk)..." class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs">
                    <option value="On Time">On Time</option>
                    <option value="Delayed">Delayed</option>
                    <option value="Maintenance">Maintenance</option>
                </select>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl font-bold bg-emerald-600 text-slate-950 hover:bg-emerald-500 text-xs uppercase">
                Save Bus Route
            </button>
        </form>
    </div>
</div>
@endsection
