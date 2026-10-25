@extends('layouts.app')

@section('title', 'Admin Management Panel - BAUST Nexus')

@section('content')
<div class="space-y-6" x-data="{ editUser: null }">
    <div class="overflow-hidden rounded-3xl bg-[#202b82] text-white shadow-xl shadow-blue-950/20">
        <div class="flex items-center justify-between px-5 py-5 md:px-8">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-blue-200">System Administration</span>
                <h1 class="mt-1 text-2xl font-extrabold tracking-tight md:text-3xl">Admin Dashboard</h1>
                <p class="mt-1 text-xs font-medium text-blue-100/80">{{ $totalUsers }} registered accounts</p>
            </div>
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/15 bg-white/10 text-xl text-blue-100">
                <i class="fa-solid fa-shield-halved"></i>
            </span>
        </div>
        <nav aria-label="Admin dashboard sections" class="grid grid-cols-3 border-t border-white/15">
            <a href="{{ route('admin.index', ['section' => 'pending']) }}#admin-accounts" @if($activeSection === 'pending') aria-current="page" @endif class="flex min-h-[68px] items-center justify-center gap-2 border-b-2 px-3 text-sm font-bold transition {{ $activeSection === 'pending' ? 'border-white bg-white/10 text-white' : 'border-transparent text-blue-100/75 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-ellipsis"></i><span>Pending <span class="ml-1 rounded-full bg-white/15 px-2 py-0.5 text-[10px]">{{ $pendingUsers }}</span></span>
            </a>
            <a href="{{ route('admin.index', ['section' => 'edit']) }}#admin-accounts" @if($activeSection === 'edit') aria-current="page" @endif class="flex min-h-[68px] items-center justify-center gap-2 border-b-2 px-3 text-sm font-bold transition {{ $activeSection === 'edit' ? 'border-white bg-white/10 text-white' : 'border-transparent text-blue-100/75 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-pen-to-square"></i><span>Edit</span>
            </a>
            <a href="{{ route('admin.index', ['section' => 'features']) }}#admin-actions" @if($activeSection === 'features') aria-current="page" @endif class="flex min-h-[68px] items-center justify-center gap-2 border-b-2 px-3 text-sm font-bold transition {{ $activeSection === 'features' ? 'border-white bg-white/10 text-white' : 'border-transparent text-blue-100/75 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-table-cells-large"></i><span>Features</span>
            </a>
        </div>
    </div>

    @if($activeSection === 'features')
    <section id="admin-actions" class="scroll-mt-6 space-y-4" aria-labelledby="admin-actions-title">
        <div>
            <h2 id="admin-actions-title" class="text-lg font-extrabold text-slate-900 dark:text-white">Manage Campus Features</h2>
            <p class="mt-1 text-xs font-medium text-slate-600 dark:text-slate-400">Open a campus section to manage its existing content and tools.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="group flex items-center gap-4 rounded-3xl bg-gradient-to-r from-orange-500 to-amber-500 p-5 text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:shadow-xl md:p-6">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-2xl"><i class="fa-solid fa-pencil"></i></span>
            <span class="min-w-0 flex-1">
                <span class="block text-lg font-extrabold md:text-xl">Campus Dashboard</span>
                <span class="mt-1 block text-xs font-semibold text-white/85">Open the main campus overview and announcements</span>
            </span>
            <i class="fa-solid fa-arrow-right text-lg transition group-hover:translate-x-1"></i>
        </a>
        <div class="grid gap-3 md:grid-cols-2">
            <a href="{{ route('library.index') }}" class="group flex min-h-[88px] items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-lg text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300"><i class="fa-solid fa-book-open"></i></span>
                <span class="min-w-0 flex-1"><span class="block font-extrabold text-slate-900 dark:text-white">Manage Library &amp; Books</span><span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Book list, borrowing and library tools</span></span>
                <i class="fa-solid fa-chevron-right text-slate-400 transition group-hover:translate-x-1"></i>
            </a>
            <a href="{{ route('cafe.index') }}" class="group flex min-h-[88px] items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-lg text-rose-700 dark:bg-rose-500/15 dark:text-rose-300"><i class="fa-solid fa-utensils"></i></span>
                <span class="min-w-0 flex-1"><span class="block font-extrabold text-slate-900 dark:text-white">Order from Campus Cafe</span><span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Browse the menu and place a personal order</span></span>
                <i class="fa-solid fa-chevron-right text-slate-400 transition group-hover:translate-x-1"></i>
            </a>
            <a href="{{ route('transport.index') }}" class="group flex min-h-[88px] items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-lg text-sky-700 dark:bg-sky-500/15 dark:text-sky-300"><i class="fa-solid fa-bus"></i></span>
                <span class="min-w-0 flex-1"><span class="block font-extrabold text-slate-900 dark:text-white">Manage Bus Schedule</span><span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">View routes and add transport entries</span></span>
                <i class="fa-solid fa-chevron-right text-slate-400 transition group-hover:translate-x-1"></i>
            </a>
            <a href="{{ route('notices.index') }}" class="group flex min-h-[88px] items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-lg text-amber-700 dark:bg-amber-500/15 dark:text-amber-300"><i class="fa-solid fa-bullhorn"></i></span>
                <span class="min-w-0 flex-1"><span class="block font-extrabold text-slate-900 dark:text-white">Post New Notice</span><span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Publish academic, exam and event notices</span></span>
                <i class="fa-solid fa-chevron-right text-slate-400 transition group-hover:translate-x-1"></i>
            </a>
            <a href="{{ route('campus.calendar') }}" class="group flex min-h-[88px] items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-lg text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="min-w-0 flex-1"><span class="block font-extrabold text-slate-900 dark:text-white">Academic Calendar</span><span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">View the published semester dates and holidays</span></span>
                <i class="fa-solid fa-chevron-right text-slate-400 transition group-hover:translate-x-1"></i>
            </a>
            <a href="{{ route('campus.dress_code') }}" class="group flex min-h-[88px] items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-lg text-purple-700 dark:bg-purple-500/15 dark:text-purple-300"><i class="fa-solid fa-shirt"></i></span>
                <span class="min-w-0 flex-1"><span class="block font-extrabold text-slate-900 dark:text-white">Dress Code</span><span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">View the official campus dress code</span></span>
                <i class="fa-solid fa-chevron-right text-slate-400 transition group-hover:translate-x-1"></i>
            </a>
            <a href="{{ route('resources.index') }}" class="group flex min-h-[88px] items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 md:col-span-2">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-lg text-blue-700 dark:bg-blue-500/15 dark:text-blue-300"><i class="fa-solid fa-folder-open"></i></span>
                <span class="min-w-0 flex-1"><span class="block font-extrabold text-slate-900 dark:text-white">Academic Resources</span><span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Upload, update and remove course materials</span></span>
                <i class="fa-solid fa-chevron-right text-slate-400 transition group-hover:translate-x-1"></i>
            </a>
            <a href="{{ route('queries.index') }}" class="group flex min-h-[88px] items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-teal-100 text-lg text-teal-700 dark:bg-teal-500/15 dark:text-teal-300"><i class="fa-solid fa-comments"></i></span>
                <span class="min-w-0 flex-1"><span class="block font-extrabold text-slate-900 dark:text-white">Query Board</span><span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Ask questions and join the campus Q&amp;A community</span></span>
                <i class="fa-solid fa-chevron-right text-slate-400 transition group-hover:translate-x-1"></i>
            </a>
            <a href="{{ route('cgpa.index') }}" class="group flex min-h-[88px] items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-lg text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300"><i class="fa-solid fa-calculator"></i></span>
                <span class="min-w-0 flex-1"><span class="block font-extrabold text-slate-900 dark:text-white">CGPA &amp; Progress</span><span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Use the semester GPA calculator and progress tracker</span></span>
                <i class="fa-solid fa-chevron-right text-slate-400 transition group-hover:translate-x-1"></i>
            </a>
        </div>
    </section>
    @endif

    @if($activeSection === 'edit')
    <div id="admin-overview" class="grid scroll-mt-6 grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">
        <div class="glass-card rounded-2xl border border-blue-100 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-blue-900/50">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wide text-slate-600 dark:text-slate-400">Students</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300"><i class="fa-solid fa-user-graduate"></i></span>
            </div>
            <div class="mt-3 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $totalStudents }}</div>
        </div>
        <div class="glass-card rounded-2xl border border-blue-100 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-blue-900/50">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wide text-slate-600 dark:text-slate-400">Teachers</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300"><i class="fa-solid fa-chalkboard-user"></i></span>
            </div>
            <div class="mt-3 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $totalTeachers }}</div>
        </div>
        <div class="glass-card rounded-2xl border border-blue-100 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-blue-900/50">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wide text-slate-600 dark:text-slate-400">Resources</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-300"><i class="fa-solid fa-folder-open"></i></span>
            </div>
            <div class="mt-3 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $totalResources }}</div>
        </div>
        <div class="glass-card rounded-2xl border border-blue-100 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-blue-900/50">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wide text-slate-600 dark:text-slate-400">Library Borrows</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-cyan-100 text-cyan-700 dark:bg-cyan-500/15 dark:text-cyan-300"><i class="fa-solid fa-book-open-reader"></i></span>
            </div>
            <div class="mt-3 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $totalBorrows }}</div>
        </div>
        <div class="glass-card rounded-2xl border border-blue-100 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-blue-900/50">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wide text-slate-600 dark:text-slate-400">Notices</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-100 text-violet-700 dark:bg-violet-500/15 dark:text-violet-300"><i class="fa-solid fa-bullhorn"></i></span>
            </div>
            <div class="mt-3 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $totalNotices }}</div>
        </div>
        <a href="{{ route('admin.index', ['section' => 'pending']) }}#admin-accounts" class="glass-card rounded-2xl border border-amber-200 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-amber-800/50">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wide text-slate-600 dark:text-slate-400">Pending Approvals</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300"><i class="fa-solid fa-user-clock"></i></span>
            </div>
            <div class="mt-3 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $pendingUsers }}</div>
        </a>
    </div>
    @endif

    @if($activeSection !== 'features')
    <div id="admin-accounts" class="glass-card scroll-mt-6 space-y-4 rounded-3xl border border-slate-200 p-5 shadow-sm dark:border-slate-800 md:p-6">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h3 class="flex items-center gap-2 text-lg font-extrabold text-slate-900 dark:text-white">
                    <i class="fa-solid {{ $activeSection === 'pending' ? 'fa-user-clock text-amber-600 dark:text-amber-400' : 'fa-users-gear text-blue-600 dark:text-blue-400' }}"></i>
                    {{ $activeSection === 'pending' ? 'Pending Account Approvals' : 'User Accounts & Approvals' }}
                </h3>
                <p class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">
                    {{ $activeSection === 'pending' ? 'Review new student and teacher applications waiting for approval.' : 'Review access, update roles, and manage registered accounts.' }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if($activeSection === 'pending')
                    <a href="{{ route('admin.index', ['section' => 'edit']) }}#admin-accounts" class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-800 transition hover:bg-blue-100 dark:bg-slate-800 dark:text-blue-200 dark:hover:bg-slate-700">View all accounts</a>
                @else
                    <a href="{{ route('admin.index', ['section' => 'pending']) }}#admin-accounts" class="rounded-lg bg-amber-50 px-3 py-2 text-xs font-bold text-amber-800 transition hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-200 dark:hover:bg-amber-500/20">Pending ({{ $pendingUsers }})</a>
                @endif
                <span class="px-1 text-xs font-bold text-blue-700 dark:text-blue-300">Page {{ $users->currentPage() }} of {{ max(1, $users->lastPage()) }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-xs">
                <thead>
                    <tr class="border-y border-blue-100 bg-blue-50/80 font-extrabold uppercase tracking-wide text-blue-900 dark:border-blue-900/60 dark:bg-blue-950/40 dark:text-blue-200">
                        <th class="py-3 pl-3">User Details</th>
                        <th class="py-3">Department & Level</th>
                        <th class="py-3">Role</th>
                        <th class="py-3">Approval</th>
                        <th class="py-3 pr-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium dark:divide-slate-800/60">
                    @forelse($users as $u)
                        <tr class="transition-colors hover:bg-blue-50/70 dark:hover:bg-blue-950/20">
                            <td class="py-3 pl-3">
                                <div class="font-extrabold text-slate-900 dark:text-white">{{ $u->name }}</div>
                                <div class="text-[11px] text-slate-600 dark:text-slate-400 font-mono">{{ $u->email }}</div>
                                @if($u->student_id || $u->teacher_id)
                                    <div class="mt-1 text-[10px] font-bold text-blue-700 dark:text-blue-300">
                                        {{ $u->role === 'teacher' ? 'Teacher ID' : 'Student ID' }}: {{ $u->role === 'teacher' ? ($u->teacher_id ?? '—') : ($u->student_id ?? '—') }}
                                    </div>
                                @endif
                            </td>

                            <td class="py-3 text-slate-800 dark:text-slate-300">
                                <div><strong>{{ $u->department ?? 'General' }}</strong></div>
                                @if($u->isStudent())
                                    <div class="text-[10px] text-slate-600 dark:text-slate-400">{{ $u->level }} {{ $u->term }}</div>
                                @endif
                            </td>

                            <td class="py-3">
                                <form action="{{ route('admin.users.role', $u->id) }}" method="POST">
                                    @csrf
                                    <select name="role" onchange="this.form.submit()" class="rounded-lg border border-slate-300 bg-white px-2.5 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                        <option value="student" {{ $u->role === 'student' ? 'selected' : '' }}>Student</option>
                                        <option value="teacher" {{ $u->role === 'teacher' ? 'selected' : '' }}>Teacher</option>
                                        <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="cafe_admin" {{ $u->role === 'cafe_admin' ? 'selected' : '' }}>Cafe Admin</option>
                                        <option value="library_admin" {{ $u->role === 'library_admin' ? 'selected' : '' }}>Library Admin</option>
                                    </select>
                                </form>
                            </td>

                            <td class="py-3">
                                <form action="{{ route('admin.users.approval', $u->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" title="Click to Toggle Approval" class="rounded-full px-3 py-1.5 text-[10px] font-extrabold uppercase transition {{ $u->is_approved ? 'border border-emerald-300 bg-emerald-100 text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/20 dark:text-emerald-300' : 'animate-pulse border border-rose-300 bg-rose-100 text-rose-800 dark:border-rose-500/30 dark:bg-rose-500/20 dark:text-rose-300' }}">
                                        {{ $u->is_approved ? '✓ Approved' : '⏳ Pending' }}
                                    </button>
                                </form>
                            </td>

                            <td class="py-3 pr-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                            @click="editUser = @js([
                                                'id' => $u->id,
                                                'role' => $u->role,
                                                'roleLabel' => ucwords(str_replace('_', ' ', $u->role)),
                                                'name' => $u->name,
                                                'email' => $u->email,
                                                'student_id' => $u->student_id,
                                                'teacher_id' => $u->teacher_id,
                                                'department' => $u->department,
                                                'level' => $u->level,
                                                'term' => $u->term,
                                                'designation' => $u->designation,
                                                'phone' => $u->phone,
                                                'gender' => $u->gender,
                                                'date_of_birth' => $u->date_of_birth?->format('Y-m-d'),
                                                'blood_group' => $u->blood_group,
                                                'emergency_contact' => $u->emergency_contact,
                                                'address' => $u->address,
                                                'updateUrl' => route('admin.users.update', $u->id),
                                            ])"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-2 text-[11px] font-bold text-blue-800 transition hover:bg-blue-100 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-200 dark:hover:bg-blue-500/20">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" onsubmit="return confirm('{{ $u->is_approved ? 'Delete this user account?' : 'Remove this pending application?' }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-300 bg-rose-100 px-2.5 py-2 text-[11px] font-bold text-rose-700 transition hover:bg-rose-200 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500/20">
                                            <i class="fa-solid fa-trash"></i> {{ $u->is_approved ? 'Remove' : 'Remove pending' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <span class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-xl text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
                                    <i class="fa-solid {{ $activeSection === 'pending' ? 'fa-circle-check' : 'fa-users' }}"></i>
                                </span>
                                <p class="font-extrabold text-slate-800 dark:text-slate-100">{{ $activeSection === 'pending' ? 'All applications are reviewed' : 'No accounts to display' }}</p>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $activeSection === 'pending' ? 'There are no pending student or teacher applications right now.' : 'Registered user accounts will appear here.' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <div x-show="editUser" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/60 p-4" @click.self="editUser = null" @keydown.escape.window="editUser = null">
        <div class="my-auto w-full max-w-2xl rounded-3xl border border-blue-100 bg-white p-6 shadow-2xl dark:border-slate-700 dark:bg-slate-900">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300">Administrator controls</span>
                    <h2 class="mt-1 text-xl font-extrabold text-slate-900 dark:text-white">Edit user details</h2>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Update account information. Use the role selector in the table to change access.</p>
                    <span class="mt-2 inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide text-blue-800 dark:bg-blue-500/15 dark:text-blue-200" x-text="editUser ? editUser.roleLabel : ''"></span>
                </div>
                <button type="button" @click="editUser = null" aria-label="Close edit dialog" class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-slate-800 dark:hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" :action="editUser ? editUser.updateUrl : '#'" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="space-y-1.5">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Full name</span>
                        <input name="name" x-model="editUser.name" required maxlength="255" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <label class="space-y-1.5">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Email</span>
                        <input type="email" name="email" x-model="editUser.email" required maxlength="255" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <template x-if="editUser.role === 'student'">
                        <label class="space-y-1.5">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Student ID</span>
                            <input name="student_id" x-model="editUser.student_id" maxlength="50" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        </label>
                    </template>
                    <template x-if="editUser.role === 'teacher'">
                        <label class="space-y-1.5">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Teacher ID</span>
                            <input name="teacher_id" x-model="editUser.teacher_id" maxlength="50" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        </label>
                    </template>
                    <label class="space-y-1.5">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Department</span>
                        <input name="department" x-model="editUser.department" maxlength="100" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <label class="space-y-1.5">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Phone</span>
                        <input name="phone" x-model="editUser.phone" maxlength="20" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <template x-if="editUser.role === 'student'">
                        <label class="space-y-1.5">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Academic level</span>
                            <input name="level" x-model="editUser.level" maxlength="50" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        </label>
                    </template>
                    <template x-if="editUser.role === 'student'">
                        <label class="space-y-1.5">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Academic term</span>
                            <input name="term" x-model="editUser.term" maxlength="50" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        </label>
                    </template>
                    <template x-if="editUser.role === 'teacher'">
                        <label class="space-y-1.5 sm:col-span-2">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Designation</span>
                            <input name="designation" x-model="editUser.designation" maxlength="100" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        </label>
                    </template>
                    <label class="space-y-1.5">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Gender</span>
                        <select name="gender" x-model="editUser.gender" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option value="">Not specified</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                            <option value="prefer_not_to_say">Prefer not to say</option>
                        </select>
                    </label>
                    <label class="space-y-1.5">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Date of birth</span>
                        <input type="date" name="date_of_birth" x-model="editUser.date_of_birth" max="{{ now()->toDateString() }}" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <label class="space-y-1.5">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Blood group</span>
                        <select name="blood_group" x-model="editUser.blood_group" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option value="">Not specified</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bloodGroup)
                                <option value="{{ $bloodGroup }}">{{ $bloodGroup }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="space-y-1.5">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Emergency contact</span>
                        <input type="tel" name="emergency_contact" x-model="editUser.emergency_contact" maxlength="20" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <label class="space-y-1.5 sm:col-span-2">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Address</span>
                        <textarea name="address" x-model="editUser.address" maxlength="500" rows="2" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white"></textarea>
                    </label>
                </div>
                <div class="flex justify-end gap-2 border-t border-slate-200 pt-4 dark:border-slate-800">
                    <button type="button" @click="editUser = null" class="rounded-xl border border-slate-300 px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Cancel</button>
                    <button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-xs font-bold text-white transition hover:bg-blue-800 dark:bg-blue-500 dark:text-slate-950 dark:hover:bg-blue-400">Save changes</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
