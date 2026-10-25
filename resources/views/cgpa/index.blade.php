@extends('layouts.app')

@section('title', 'CGPA Calculator & Academic Progress Tracker - BAUST Nexus')

@section('content')
<div class="space-y-8">

    <!-- Header -->
    <div class="glass-card p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-700/80 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-sm">
        <div>
            <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-500/10 text-emerald-800 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-500/30 text-xs font-bold uppercase tracking-wider">
                BAUST Grading & Credit Monitor
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2">CGPA Calculator & Academic Progress</h1>
            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mt-1">Calculate semester GPA, cumulative CGPA, and track your completed degree credits against 160 B.Sc requirements</p>
        </div>
    </div>

    <!-- Academic Progress Tracker Overview Banner -->
    <div class="glass-card p-8 rounded-3xl border border-slate-200 dark:border-slate-700/80 bg-gradient-to-br from-slate-50 via-emerald-50/50 to-white dark:from-slate-900 dark:via-emerald-950/20 dark:to-slate-950">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-3 max-w-xl">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-500/20 border border-emerald-300 dark:border-emerald-500/30 flex items-center justify-center text-emerald-700 dark:text-emerald-400 text-xl font-extrabold">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Degree Progress Monitor</h2>
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Total B.Sc Engineering Requirement: <strong>160.0 Credits</strong></span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div>
                    <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        <span>Completion Rate: {{ $completionPercentage }}%</span>
                        <span class="text-emerald-700 dark:text-emerald-400 font-extrabold">{{ $completedCredits }} / 160.0 Credits</span>
                    </div>
                    <div class="w-full h-3 bg-slate-200 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-300 dark:border-slate-800 p-0.5">
                        <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-1000" style="width: {{ min(100, $completionPercentage) }}%"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 w-full md:w-auto min-w-[280px]">
                <div class="bg-white p-4 rounded-2xl border border-slate-200 text-center shadow-sm dark:bg-slate-900/90 dark:border-slate-800">
                    <div class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase">Current CGPA</div>
                    <div class="text-3xl font-extrabold text-emerald-700 dark:text-emerald-400 mt-1">
                        {{ Auth::check() ? number_format(Auth::user()->cgpa, 2) : '3.75' }}
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-slate-200 text-center shadow-sm dark:bg-slate-900/90 dark:border-slate-800">
                    <div class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase">Remaining Credits</div>
                    <div class="text-3xl font-extrabold text-amber-700 dark:text-amber-400 mt-1">
                        {{ number_format($remainingCredits, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive CGPA Calculator Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left: Semester GPA Calculator Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-700/80 shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-calculator text-emerald-600 dark:text-emerald-400"></i> Semester GPA Calculator
                    </h3>
                    <button type="button" onclick="addCourseRow()" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 transition dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30 dark:hover:bg-emerald-500/30">
                        <i class="fa-solid fa-plus mr-1"></i> Add Course
                    </button>
                </div>

                <form id="gpaCalcForm" action="{{ route('cgpa.save') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">Semester Name</label>
                        <input type="text" name="semester_name" value="Level 3 Term 2" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>

                    <!-- Dynamic Course Rows Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-slate-600 dark:text-slate-400 uppercase border-b border-slate-200 dark:border-slate-800">
                                    <th class="pb-3 pl-1">Course Code & Name</th>
                                    <th class="pb-3 w-24">Credits</th>
                                    <th class="pb-3 w-28">Grade</th>
                                    <th class="pb-3 w-12 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="courseRows" class="divide-y divide-slate-200 dark:divide-slate-800/60">
                                <tr class="course-row">
                                    <td class="py-2.5 pr-2">
                                        <input type="text" placeholder="CSE-3201 Web Engineering" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-500 text-xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                    </td>
                                    <td class="py-2.5 pr-2">
                                        <input type="number" step="0.5" value="3.0" onchange="calculateLiveGpa()" class="credit-input w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                    </td>
                                    <td class="py-2.5 pr-2">
                                        <select onchange="calculateLiveGpa()" class="grade-input w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                            <option value="4.00">A+ (4.00)</option>
                                            <option value="3.75">A (3.75)</option>
                                            <option value="3.50">A- (3.50)</option>
                                            <option value="3.25">B+ (3.25)</option>
                                            <option value="3.00">B (3.00)</option>
                                            <option value="2.75">B- (2.75)</option>
                                            <option value="2.50">C+ (2.50)</option>
                                            <option value="2.25">C (2.25)</option>
                                            <option value="2.00">D (2.00)</option>
                                            <option value="0.00">F (0.00)</option>
                                        </select>
                                    </td>
                                    <td class="py-2.5 text-center">
                                        <button type="button" onclick="removeRow(this)" class="text-rose-400 hover:text-rose-300 text-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr class="course-row">
                                    <td class="py-2.5 pr-2">
                                        <input type="text" placeholder="CSE-3202 Web Engineering Lab" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-500 text-xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                    </td>
                                    <td class="py-2.5 pr-2">
                                        <input type="number" step="0.5" value="1.5" onchange="calculateLiveGpa()" class="credit-input w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                    </td>
                                    <td class="py-2.5 pr-2">
                                        <select onchange="calculateLiveGpa()" class="grade-input w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                            <option value="4.00" selected>A+ (4.00)</option>
                                            <option value="3.75">A (3.75)</option>
                                            <option value="3.50">A- (3.50)</option>
                                        </select>
                                    </td>
                                    <td class="py-2.5 text-center">
                                        <button type="button" onclick="removeRow(this)" class="text-rose-400 hover:text-rose-300 text-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Calculated Result Banner -->
                    <div class="p-5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-500/30 flex items-center justify-between">
                        <div>
                            <div class="text-xs text-slate-700 dark:text-slate-300 font-bold uppercase">Calculated Semester GPA</div>
                            <div id="liveGpaDisplay" class="text-3xl font-extrabold text-emerald-700 dark:text-emerald-400">4.00</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-700 dark:text-slate-300 font-bold uppercase">Earned Credits</div>
                            <div id="liveCreditsDisplay" class="text-xl font-extrabold text-slate-900 dark:text-white text-right">4.5</div>
                        </div>
                    </div>

                    <!-- Hidden Inputs for Form Submission -->
                    <input type="hidden" name="gpa" id="hiddenGpa" value="4.00">
                    <input type="hidden" name="credits_earned" id="hiddenCredits" value="4.5">

                    @auth
                        <button type="submit" class="w-full py-3.5 rounded-xl font-bold bg-emerald-700 text-white hover:bg-emerald-800 transition text-xs uppercase tracking-wider shadow-md shadow-emerald-700/20 dark:bg-emerald-500 dark:text-slate-950 dark:hover:bg-emerald-400">
                            Save Semester Result to Academic Profile
                        </button>
                    @endauth
                </form>
            </div>
        </div>

        <!-- Right: Official BAUST Grading Scale Standard -->
        <div class="space-y-6">
            <div class="glass-card p-6 rounded-3xl border border-slate-200 dark:border-slate-700/80">
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-award text-amber-600 dark:text-amber-400"></i> BAUST Official Grading Scale
                </h3>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-100">80% and above</span>
                        <span class="font-bold text-emerald-700 dark:text-emerald-400">A+ (4.00)</span>
                    </div>
                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-100">75% to &lt;80%</span>
                        <span class="font-bold text-emerald-700 dark:text-emerald-400">A (3.75)</span>
                    </div>
                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-100">70% to &lt;75%</span>
                        <span class="font-bold text-emerald-700 dark:text-emerald-400">A- (3.50)</span>
                    </div>
                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-100">65% to &lt;70%</span>
                        <span class="font-bold text-emerald-700 dark:text-emerald-400">B+ (3.25)</span>
                    </div>
                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-100">60% to &lt;65%</span>
                        <span class="font-bold text-emerald-700 dark:text-emerald-400">B (3.00)</span>
                    </div>
                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-100">55% to &lt;60%</span>
                        <span class="font-bold text-emerald-700 dark:text-emerald-400">B- (2.75)</span>
                    </div>
                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-100">50% to &lt;55%</span>
                        <span class="font-bold text-emerald-700 dark:text-emerald-400">C+ (2.50)</span>
                    </div>
                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-100">45% to &lt;50%</span>
                        <span class="font-bold text-emerald-700 dark:text-emerald-400">C (2.25)</span>
                    </div>
                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-100">40% to &lt;45%</span>
                        <span class="font-bold text-amber-700 dark:text-amber-400">D (2.00)</span>
                    </div>
                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-100">Less than 40%</span>
                        <span class="font-bold text-rose-700 dark:text-rose-400">F (0.00)</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@section('scripts')
<script>
    function calculateLiveGpa() {
        const rows = document.querySelectorAll('.course-row');
        let totalPoints = 0;
        let totalCredits = 0;

        rows.forEach(row => {
            const credit = parseFloat(row.querySelector('.credit-input').value) || 0;
            const grade = parseFloat(row.querySelector('.grade-input').value) || 0;

            totalPoints += (credit * grade);
            totalCredits += credit;
        });

        const gpa = totalCredits > 0 ? (totalPoints / totalCredits).toFixed(2) : "0.00";

        document.getElementById('liveGpaDisplay').textContent = gpa;
        document.getElementById('liveCreditsDisplay').textContent = totalCredits.toFixed(1);

        document.getElementById('hiddenGpa').value = gpa;
        document.getElementById('hiddenCredits').value = totalCredits;
    }

    function addCourseRow() {
        const tbody = document.getElementById('courseRows');
        const tr = document.createElement('tr');
        tr.className = 'course-row';
        tr.innerHTML = `
            <td class="py-2.5 pr-2">
                <input type="text" placeholder="Course Code & Title" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-500 text-xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
            </td>
            <td class="py-2.5 pr-2">
                <input type="number" step="0.5" value="3.0" onchange="calculateLiveGpa()" class="credit-input w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
            </td>
            <td class="py-2.5 pr-2">
                <select onchange="calculateLiveGpa()" class="grade-input w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    <option value="4.00">A+ (4.00)</option>
                    <option value="3.75">A (3.75)</option>
                    <option value="3.50">A- (3.50)</option>
                    <option value="3.25">B+ (3.25)</option>
                    <option value="3.00">B (3.00)</option>
                    <option value="2.75">B- (2.75)</option>
                    <option value="2.50">C+ (2.50)</option>
                    <option value="2.25">C (2.25)</option>
                    <option value="2.00">D (2.00)</option>
                    <option value="0.00">F (0.00)</option>
                </select>
            </td>
            <td class="py-2.5 text-center">
                <button type="button" onclick="removeRow(this)" class="text-rose-400 hover:text-rose-300 text-sm">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        calculateLiveGpa();
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
        calculateLiveGpa();
    }

    document.addEventListener("DOMContentLoaded", calculateLiveGpa);
</script>
@endsection
@endsection
