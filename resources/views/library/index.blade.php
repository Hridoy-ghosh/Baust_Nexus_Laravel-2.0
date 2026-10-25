@extends('layouts.app')

@section('title', 'Central Library - BAUST Nexus')

@section('content')
@php
    $activeTab = request()->input('tab', 'dashboard');
    $availableTabs = ['dashboard', 'details', 'records', 'due', 'manage'];
    if (!in_array($activeTab, $availableTabs, true)) {
        $activeTab = 'dashboard';
    }
@endphp
<div class="mx-auto max-w-6xl space-y-6">

    <!-- Header & Navigation Bar (Matching Screenshots 2,3,4,5) -->
    <div class="flex flex-col gap-5 rounded-3xl bg-gradient-to-br from-blue-950 via-indigo-900 to-blue-800 p-5 text-white shadow-xl sm:p-7 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center space-x-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-2xl text-white">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">BAUST Central Library</h1>
                <p class="mt-1 text-xs text-blue-100 sm:text-sm">Find books, place a borrow request and keep track of your library activity.</p>
            </div>
        </div>

        <div class="flex max-w-full items-center gap-1 overflow-x-auto rounded-2xl border border-white/10 bg-indigo-950/40 p-1.5 text-xs font-bold">
            <button onclick="switchTab('dashboard')" id="tab-btn-dashboard" class="shrink-0 rounded-xl px-3.5 py-2.5 transition {{ $activeTab === 'dashboard' ? 'bg-white text-indigo-900 shadow' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                Dashboard
            </button>
            <button onclick="switchTab('details')" id="tab-btn-details" class="shrink-0 rounded-xl px-3.5 py-2.5 transition {{ $activeTab === 'details' ? 'bg-white text-indigo-900 shadow' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                Details
            </button>
            @auth
                <button onclick="switchTab('records')" id="tab-btn-records" class="shrink-0 rounded-xl px-3.5 py-2.5 transition {{ $activeTab === 'records' ? 'bg-white text-indigo-900 shadow' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                    History
                </button>
                <button onclick="switchTab('due')" id="tab-btn-due" class="shrink-0 rounded-xl px-3.5 py-2.5 transition {{ $activeTab === 'due' ? 'bg-white text-indigo-900 shadow' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                    Due books
                </button>
                @if(Auth::user()->isLibraryAdmin() || Auth::user()->isAdmin())
                    <button onclick="switchTab('manage')" id="tab-btn-manage" class="shrink-0 rounded-xl px-3.5 py-2.5 transition {{ $activeTab === 'manage' ? 'bg-white text-indigo-900 shadow' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                        Manage
                    </button>
                @endif
            @endauth
        </div>
    </div>

    @if(session('success') || session('error') || session('info'))
        <div class="space-y-2">
            @foreach(['success', 'error', 'info'] as $key)
                @if(session($key))
                    <div role="status" class="rounded-2xl border px-4 py-3 text-sm font-semibold {{ $key === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200' : ($key === 'error' ? 'border-rose-200 bg-rose-50 text-rose-800 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-200' : 'border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-200') }}">{{ session($key) }}</div>
                @endif
            @endforeach
        </div>
    @endif
    @if($errors->any())
        <div role="alert" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-200">
            <ul class="list-inside list-disc space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div id="tab-content-dashboard" class="space-y-6 {{ $activeTab === 'dashboard' ? '' : 'hidden' }}">
        <!-- Library Overview Banner -->
        <div class="flex flex-col gap-6 rounded-3xl border border-blue-800/40 bg-gradient-to-br from-indigo-950 via-indigo-900 to-blue-900 p-6 text-white shadow-xl sm:p-8 md:flex-row md:items-center md:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-200">Your campus library</p>
                <h2 class="mt-2 text-2xl font-extrabold sm:text-3xl">Knowledge starts here.</h2>
                <p class="mt-2 max-w-xl text-sm leading-6 text-indigo-100">Explore the collection, check availability and request a book with your BAUST account.</p>
                <button type="button" onclick="switchTab('details')" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs font-extrabold text-indigo-900 shadow transition hover:bg-blue-50">
                    Browse books <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
            <div class="grid grid-cols-2 gap-3 md:min-w-56">
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                    <span class="block text-2xl font-extrabold">{{ $stats['total_books'] }}</span>
                    <span class="mt-1 block text-[11px] font-semibold text-indigo-100">Book titles</span>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                    <span class="block text-2xl font-extrabold">{{ $stats['available_copies'] }}</span>
                    <span class="mt-1 block text-[11px] font-semibold text-indigo-100">Available copies</span>
                </div>
            </div>
        </div>

        <section aria-label="Library borrowing policy" class="grid gap-3 sm:grid-cols-3">
            <div class="flex items-center gap-3 rounded-2xl border border-blue-200 bg-white p-4 shadow-sm dark:border-blue-500/20 dark:bg-slate-900">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300"><i class="fa-solid fa-calendar-days"></i></span>
                <div><p class="text-xs font-extrabold text-slate-900 dark:text-white">30-day loan period</p><p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">Return books by the due date.</p></div>
            </div>
            <div class="flex items-center gap-3 rounded-2xl border border-indigo-200 bg-white p-4 shadow-sm dark:border-indigo-500/20 dark:bg-slate-900">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300"><i class="fa-solid fa-book-open"></i></span>
                <div><p class="text-xs font-extrabold text-slate-900 dark:text-white">Borrowing limits</p><p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">Students: 3 books · Teachers: 5 books</p></div>
            </div>
            <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-white p-4 shadow-sm dark:border-rose-500/20 dark:bg-slate-900">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300"><i class="fa-solid fa-coins"></i></span>
                <div><p class="text-xs font-extrabold text-slate-900 dark:text-white">Late return fine</p><p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">৳{{ \App\Models\LibraryBorrow::DAILY_FINE_TAKA }} per day after the due date.</p></div>
            </div>
        </section>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-4">
            <div class="glass-card flex flex-col items-start space-y-2 rounded-2xl border border-slate-200 p-4 dark:border-slate-800 sm:p-5">
                <i class="fa-solid fa-book text-blue-400 text-xl"></i>
                <span class="text-3xl font-extrabold text-blue-400">{{ $stats['active_borrows'] }}</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Active loans</span>
            </div>

            <div class="glass-card flex flex-col items-start space-y-2 rounded-2xl border border-slate-200 p-4 dark:border-slate-800 sm:p-5">
                <i class="fa-solid fa-triangle-exclamation text-rose-400 text-xl"></i>
                <span class="text-3xl font-extrabold text-rose-400">{{ $stats['overdue_borrows'] }}</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Overdue books</span>
            </div>

            <div class="glass-card flex flex-col items-start space-y-2 rounded-2xl border border-slate-200 p-4 dark:border-slate-800 sm:p-5">
                <i class="fa-solid fa-circle-check text-emerald-400 text-xl"></i>
                <span class="text-3xl font-extrabold text-emerald-400">{{ $stats['returned_borrows'] }}</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Returned</span>
            </div>

            <div class="glass-card flex flex-col items-start space-y-2 rounded-2xl border border-slate-200 p-4 dark:border-slate-800 sm:p-5">
                <i class="fa-solid fa-calendar-day text-indigo-400 text-xl"></i>
                <span class="text-3xl font-extrabold text-indigo-400">{{ $stats['today_borrows'] }}</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Today's requests</span>
            </div>

            <div class="glass-card flex flex-col items-start space-y-2 rounded-2xl border border-slate-200 p-4 dark:border-slate-800 sm:p-5">
                <i class="fa-solid fa-receipt text-purple-400 text-xl"></i>
                <span class="text-3xl font-extrabold text-purple-400">{{ $stats['total_records'] }}</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Borrow records</span>
            </div>

            <div class="glass-card flex flex-col items-start space-y-2 rounded-2xl border border-slate-200 p-4 dark:border-slate-800 sm:p-5">
                <i class="fa-solid fa-users text-amber-400 text-xl"></i>
                <span class="text-3xl font-extrabold text-amber-400">{{ $stats['active_users'] }}</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Active borrowers</span>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="glass-card flex items-start gap-4 rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300"><i class="fa-regular fa-clock"></i></span>
                <div><h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Opening hours</h3><p class="mt-1 text-xs text-slate-600 dark:text-slate-400">{{ $settings->opening_hours }}</p></div>
            </div>
            <div class="glass-card flex items-start gap-4 rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300"><i class="fa-solid fa-location-dot"></i></span>
                <div><h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Library location</h3><p class="mt-1 text-xs text-slate-600 dark:text-slate-400">{{ $settings->location }}</p></div>
            </div>
        </div>
    </div>

    <div id="tab-content-details" class="space-y-6 {{ $activeTab === 'details' ? '' : 'hidden' }}">
        <div class="glass-card rounded-3xl border border-slate-200 p-5 shadow-sm dark:border-slate-800 sm:p-6">
            <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Book collection</h2>
                    <p class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">Search and filter by subject, department or availability.</p>
                </div>
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ $books->total() }} titles</span>
            </div>
            <form action="{{ route('library.index') }}" method="GET" class="mb-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
                    <input type="hidden" name="tab" value="details">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Title, author or ISBN..." class="min-w-0 rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-xs text-slate-900 focus:border-indigo-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <select name="category" class="rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-xs text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                    <select name="department" class="rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-xs text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        @foreach($departments as $department)
                            <option value="{{ $department }}" @selected(request('department', 'All') === $department)>{{ $department === 'All' ? 'All departments' : $department }}</option>
                        @endforeach
                    </select>
                    <select name="sort" class="rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-xs text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <option value="newest" @selected(request('sort') !== 'title')>Recently added</option>
                        <option value="title" @selected(request('sort') === 'title')>Title A-Z</option>
                    </select>
                    <div class="flex items-center gap-3">
                        <label class="flex flex-1 items-center gap-2 rounded-xl border border-slate-300 px-3 py-3 text-xs font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-300">
                            <input type="checkbox" name="available_only" value="1" @checked(request()->boolean('available_only')) class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            Available only
                        </label>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-3 text-xs font-bold text-white transition hover:bg-indigo-500">Apply</button>
                    </div>
                </form>
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @forelse($books as $book)
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-900/70">
                        <div class="flex gap-4 p-4">
                            <div class="flex h-28 w-20 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-blue-100 to-indigo-200 text-3xl text-indigo-700 dark:from-blue-950 dark:to-indigo-900 dark:text-indigo-200">
                                <i class="fa-solid fa-book-open"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-700 dark:bg-blue-500/15 dark:text-blue-300">{{ $book->category }}</span>
                                    <span class="shrink-0 rounded-full px-2 py-1 text-[10px] font-bold {{ $book->available_copies > 0 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-500/15 dark:text-rose-300' }}">
                                        {{ $book->available_copies > 0 ? $book->available_copies.' available' : 'Unavailable' }}
                                    </span>
                                </div>
                                <h3 class="mt-2 text-sm font-extrabold leading-snug text-slate-900 dark:text-white">{{ $book->title }}</h3>
                                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">{{ $book->author }}</p>
                                <p class="mt-2 text-[11px] font-semibold text-indigo-700 dark:text-indigo-300">{{ $book->department }} · {{ $book->shelf_location }}</p>
                            </div>
                        </div>
                        @if($book->description)
                            <p class="border-t border-slate-100 px-4 py-3 text-xs leading-5 text-slate-500 dark:border-slate-800 dark:text-slate-400">{{ \Illuminate\Support\Str::limit($book->description, 105) }}</p>
                        @endif
                        <div class="border-t border-slate-100 px-4 py-3 dark:border-slate-800">
                        @auth
                            @if(Auth::user()->isStudent() || Auth::user()->isTeacher())
                                <form action="{{ route('library.borrow', $book) }}" method="POST">
                                    @csrf
                                    <button type="submit" @disabled($book->available_copies < 1) class="w-full rounded-xl bg-indigo-600 px-3 py-2.5 text-xs font-bold text-white transition hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-50">
                                        {{ $book->available_copies > 0 ? 'Request this book' : 'Currently unavailable' }}
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-flex text-xs font-bold text-indigo-700 hover:underline dark:text-indigo-300">Sign in to borrow this book</a>
                        @endauth
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                        No books match your search yet.
                    </div>
                @endforelse
            </div>
            <div class="mt-5">{{ $books->links() }}</div>
        </div>
    </div>

    <!-- ==================== TAB 2: RECORDS TAB (Matching Screenshots 2 & 5) ==================== -->
    <div id="tab-content-records" class="space-y-4 {{ $activeTab === 'records' ? '' : 'hidden' }}">
        <!-- Search & Filter Bar -->
        <div class="glass-card p-4 rounded-2xl border border-slate-800 flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-grow w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="search" id="recordSearch" placeholder="Search by book, token or borrower..." class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-xs text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
            </div>

            <select id="recordFilter" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-xs text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white sm:w-36">
                <option value="All">All</option>
                <option value="Active">Active</option>
                <option value="Returned">Returned</option>
            </select>
        </div>

        <!-- Borrow Records List -->
        <div class="space-y-3">
            @php
                $displayBorrows = Auth::check() && (Auth::user()->isLibraryAdmin() || Auth::user()->isAdmin())
                    ? $allBorrows
                    : $userBorrows;
            @endphp
            @forelse($displayBorrows as $borrow)
                <div data-borrow-record data-status="{{ $borrow->status }}" class="glass-card flex flex-col items-start justify-between gap-4 rounded-2xl border border-slate-200 p-5 dark:border-slate-800 sm:flex-row sm:items-center">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $borrow->book->title }}</h3>
                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">User: <strong class="text-slate-700 dark:text-slate-200">{{ $borrow->user->name ?? 'Student' }} ({{ $borrow->user->student_id ?? '—' }})</strong></p>
                        <p class="mt-1 font-mono text-xs font-bold text-blue-700 dark:text-blue-300">Token: {{ $borrow->token_number }}</p>
                        <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">
                            Due {{ $borrow->return_due_date ? \Illuminate\Support\Carbon::parse($borrow->return_due_date)->format('M j, Y') : '—' }}
                            @if($borrow->fineAmount() > 0)
                                · <span class="font-extrabold text-rose-600 dark:text-rose-300">{{ $borrow->overdueDays() }} overdue days · Fine ৳{{ number_format($borrow->fineAmount()) }}</span>
                            @else
                                · No fine
                            @endif
                        </p>
                    </div>

                    @auth
                        @if((Auth::user()->isLibraryAdmin() || Auth::user()->isAdmin()) && $borrow->status !== 'returned')
                            <form action="{{ route('library.return', $borrow->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-xs font-bold uppercase text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-800">
                                    RETURN
                                </button>
                            </form>
                        @elseif($borrow->status === 'returned')
                            <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase bg-emerald-500/20 text-emerald-300">Returned</span>
                        @else
                            <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase bg-amber-500/20 text-amber-300">{{ $borrow->status }}</span>
                        @endif
                    @endauth
                </div>
            @empty
                <div class="glass-card rounded-2xl border border-slate-200 p-8 text-center text-sm font-semibold text-slate-500 dark:border-slate-800 dark:text-slate-400">
                    No borrowing activity to show.
                </div>
            @endforelse
        </div>
    </div>

    <!-- ==================== TAB 3: DUE TAB (Matching Screenshot 3) ==================== -->
    <div id="tab-content-due" class="space-y-3 {{ $activeTab === 'due' ? '' : 'hidden' }}">
        @forelse($overdueBorrows as $borrow)
            <div class="glass-card flex flex-col justify-between gap-3 rounded-2xl border border-rose-200 p-5 dark:border-rose-500/30 sm:flex-row sm:items-center">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">{{ $borrow->book->title }}</h3>
                    @if(Auth::user()->isLibraryAdmin() || Auth::user()->isAdmin())
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Borrowed by {{ $borrow->user->name }}</p>
                    @endif
                    <p class="mt-1 text-xs font-bold text-rose-600 dark:text-rose-300">Due {{ \Illuminate\Support\Carbon::parse($borrow->return_due_date)->format('M j, Y') }}</p>
                    <p class="mt-1 text-xs font-extrabold text-rose-700 dark:text-rose-200">{{ $borrow->overdueDays() }} overdue {{ \Illuminate\Support\Str::plural('day', $borrow->overdueDays()) }} · Fine due: ৳{{ number_format($borrow->fineAmount()) }}</p>
                </div>
                <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-800 dark:bg-rose-500/15 dark:text-rose-200">Overdue</span>
            </div>
        @empty
            <div class="glass-card rounded-3xl p-12 text-center text-slate-500 dark:text-slate-400">
                <i class="fa-solid fa-circle-check mb-3 block text-4xl text-emerald-500"></i>
                <p class="text-base font-bold text-slate-900 dark:text-white">No overdue books</p>
                <p class="mt-1 text-xs">There are no overdue loans to display.</p>
            </div>
        @endforelse
    </div>

    <!-- ==================== TAB 4: MANAGE TAB (Matching Screenshot 2) ==================== -->
    @auth
    @if(Auth::user()->isLibraryAdmin() || Auth::user()->isAdmin())
    <div id="tab-content-manage" class="space-y-6 {{ $activeTab === 'manage' ? '' : 'hidden' }}">
        <div class="rounded-2xl bg-gradient-to-r from-blue-950 to-indigo-800 p-5 text-white shadow-lg">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="text-sm font-extrabold">Library administration</div>
                    <div class="mt-1 text-xs text-blue-100">Edit library details and manage the book catalog.</div>
                </div>
                <div class="rounded-xl bg-white/10 px-4 py-2 text-center">
                    <div class="text-2xl font-extrabold">{{ $stats['overdue_borrows'] }}</div>
                    <div class="text-[10px] font-bold uppercase text-blue-100">Overdue</div>
                </div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <section class="glass-card space-y-4 rounded-3xl border border-slate-200 p-5 dark:border-slate-800 sm:p-6">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Edit Library Info</h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">These details are shown on the Library dashboard.</p>
                </div>
                <form action="{{ route('library.settings.update') }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <label class="block space-y-1">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Opening hours</span>
                        <input type="text" name="opening_hours" value="{{ old('opening_hours', $settings->opening_hours) }}" required maxlength="100" placeholder="8:00 AM - 5:00 PM" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    </label>
                    <label class="block space-y-1">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Location</span>
                        <input type="text" name="location" value="{{ old('location', $settings->location) }}" required maxlength="255" placeholder="Library building and floor" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    </label>
                    <button type="submit" class="rounded-xl bg-blue-700 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-blue-800">Save library info</button>
                </form>
            </section>

            <section class="glass-card space-y-4 rounded-3xl border border-slate-200 p-5 dark:border-slate-800 sm:p-6">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Add New Book</h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Add a title and its catalog details.</p>
                </div>
                <form action="{{ route('library.books.store') }}" method="POST" class="grid gap-3 sm:grid-cols-2">
                    @csrf
                    <input type="text" name="title" value="{{ old('title') }}" required maxlength="255" placeholder="Book title" class="sm:col-span-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-3 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <input type="text" name="author" value="{{ old('author') }}" required maxlength="255" placeholder="Author" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-3 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <input type="text" name="isbn" value="{{ old('isbn') }}" maxlength="255" placeholder="ISBN (optional)" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-3 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <input type="text" name="department" value="{{ old('department', 'CSE') }}" required maxlength="100" placeholder="Department" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-3 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <select name="category" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-3 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        @foreach($categories as $category)
                            <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="total_copies" value="{{ old('total_copies', 3) }}" min="1" required placeholder="Total copies" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-3 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <input type="text" name="shelf_location" value="{{ old('shelf_location', 'BAUST Library') }}" required maxlength="255" placeholder="Shelf location" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-3 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <textarea name="description" rows="2" maxlength="2000" placeholder="Description (optional)" class="sm:col-span-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-3 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">{{ old('description') }}</textarea>
                    <button type="submit" class="sm:col-span-2 w-full rounded-xl bg-blue-700 py-3 text-xs font-extrabold uppercase tracking-wide text-white transition hover:bg-blue-800">Add book to catalog</button>
                </form>
            </section>
        </div>

        <section class="glass-card space-y-4 rounded-3xl border border-slate-200 p-5 dark:border-slate-800 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Manage Book List</h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Edit catalog data or remove books with no borrowing history.</p>
                </div>
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-800 dark:bg-blue-500/10 dark:text-blue-200">{{ $books->total() }} titles</span>
            </div>
            <div class="space-y-3">
                @foreach($books as $b)
                    <details class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900/70">
                        <summary class="flex cursor-pointer list-none flex-wrap items-center justify-between gap-3">
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-bold text-slate-900 dark:text-white">{{ $b->title }}</span>
                                <span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">{{ $b->author }} · {{ $b->department }} · {{ $b->available_copies }}/{{ $b->total_copies }} available</span>
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-800 dark:bg-blue-500/10 dark:text-blue-200"><i class="fa-solid fa-pen"></i> Edit</span>
                        </summary>
                        <form action="{{ route('library.books.update', $b) }}" method="POST" class="mt-4 grid gap-3 border-t border-slate-100 pt-4 dark:border-slate-800 sm:grid-cols-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="title" value="{{ $b->title }}" required maxlength="255" aria-label="Book title" class="sm:col-span-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <input type="text" name="author" value="{{ $b->author }}" required maxlength="255" aria-label="Author" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <input type="text" name="isbn" value="{{ $b->isbn }}" maxlength="255" aria-label="ISBN" placeholder="ISBN" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <input type="text" name="department" value="{{ $b->department }}" required maxlength="100" aria-label="Department" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <select name="category" required aria-label="Category" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" @selected($b->category === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="total_copies" value="{{ $b->total_copies }}" min="1" required aria-label="Total copies" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <input type="text" name="shelf_location" value="{{ $b->shelf_location }}" required maxlength="255" aria-label="Shelf location" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <textarea name="description" rows="2" maxlength="2000" aria-label="Description" placeholder="Description" class="sm:col-span-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">{{ $b->description }}</textarea>
                            <div class="sm:col-span-2 flex flex-wrap items-center justify-between gap-2">
                                <button type="submit" class="rounded-xl bg-blue-700 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-blue-800">Save book changes</button>
                            </div>
                        </form>
                        <div class="mt-2 flex justify-end">
                            @if($b->borrows_count === 0)
                                <form action="{{ route('library.books.destroy', $b) }}" method="POST" onsubmit="return confirm('Remove this book from the catalog?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 px-4 py-2.5 text-xs font-bold text-rose-700 transition hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10"><i class="fa-solid fa-trash-can"></i> Remove book</button>
                                </form>
                            @else
                                <button type="button" disabled title="Books with borrowing history must remain in the catalog." class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-400 dark:border-slate-700 dark:text-slate-500"><i class="fa-solid fa-lock"></i> Remove unavailable</button>
                                <span class="ml-2 text-xs font-semibold text-slate-500 dark:text-slate-400">Borrowing history is protected</span>
                            @endif
                        </div>
                    </details>
                @endforeach
            </div>
            <div>{{ $books->links() }}</div>
        </section>

        <section id="library-staff" class="glass-card space-y-5 rounded-3xl border border-slate-200 p-5 dark:border-slate-800 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="flex items-center gap-2 text-base font-extrabold text-slate-900 dark:text-white">
                        <i class="fa-solid fa-user-group text-indigo-600 dark:text-indigo-400"></i> Library Staff Management
                    </h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Enter each staff member and their regular library duty hours.</p>
                </div>
                <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-800 dark:bg-indigo-500/10 dark:text-indigo-200">{{ $staff->count() }} staff</span>
            </div>

            <form action="{{ route('library.staff.store') }}" method="POST" class="grid gap-3 rounded-2xl border border-indigo-100 bg-indigo-50/50 p-4 dark:border-indigo-500/20 dark:bg-indigo-500/5 sm:grid-cols-2 lg:grid-cols-4">
                @csrf
                <label class="space-y-1.5">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Staff name</span>
                    <input type="text" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="Enter staff name" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                </label>
                <label class="space-y-1.5">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Position</span>
                    <input type="text" name="position" value="{{ old('position') }}" maxlength="100" placeholder="e.g. Librarian, Assistant" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                </label>
                <label class="space-y-1.5">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Phone (optional)</span>
                    <input type="tel" name="phone" value="{{ old('phone') }}" maxlength="20" placeholder="Staff contact number" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                </label>
                <label class="space-y-1.5">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Duty hours</span>
                    <input type="text" name="duty_hours" value="{{ old('duty_hours') }}" required maxlength="100" placeholder="e.g. 8:00 AM - 4:00 PM" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                </label>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-700 px-4 py-3 text-xs font-extrabold text-white transition hover:bg-indigo-800 sm:col-span-2 lg:col-span-4">
                    <i class="fa-solid fa-user-plus"></i> Add Library Staff
                </button>
            </form>

            <div class="grid gap-3 md:grid-cols-2">
                @forelse($staff as $member)
                    <article class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-lg font-extrabold text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                        <div class="min-w-0 flex-1">
                            <h4 class="truncate text-sm font-extrabold text-slate-900 dark:text-white">{{ $member->name }}</h4>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $member->position ?: 'Library Staff' }} · {{ $member->duty_hours }}</p>
                            @if($member->phone)<p class="mt-1 text-xs font-semibold text-slate-600 dark:text-slate-300">{{ $member->phone }}</p>@endif
                        </div>
                        <form action="{{ route('library.staff.destroy', $member) }}" method="POST" onsubmit="return confirm('Remove {{ addslashes($member->name) }} from library staff?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" aria-label="Remove {{ $member->name }}" class="rounded-xl border border-rose-200 p-2.5 text-rose-700 transition hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm font-semibold text-slate-500 dark:border-slate-700 dark:text-slate-400 md:col-span-2">No library staff have been added yet.</div>
                @endforelse
            </div>
        </section>

    </div>
    @endif
    @endauth

</div>

@section('scripts')
<script>
    function switchTab(tab) {
        ['dashboard', 'details', 'records', 'due', 'manage'].forEach(t => {
            const content = document.getElementById('tab-content-' + t);
            const btn = document.getElementById('tab-btn-' + t);
            if (content) content.classList.add('hidden');
            if (btn) btn.className = 'shrink-0 rounded-xl px-3.5 py-2.5 transition text-indigo-100 hover:bg-white/10 hover:text-white';
        });

        const content = document.getElementById('tab-content-' + tab);
        const activeBtn = document.getElementById('tab-btn-' + tab);
        if (content && activeBtn) {
            content.classList.remove('hidden');
            activeBtn.className = 'shrink-0 rounded-xl px-3.5 py-2.5 transition bg-white text-indigo-900 shadow font-bold';
        }
    }

    const recordSearch = document.getElementById('recordSearch');
    const recordFilter = document.getElementById('recordFilter');
    const filterBorrowRecords = () => {
        const term = recordSearch.value.trim().toLowerCase();
        const status = recordFilter.value.toLowerCase();

        document.querySelectorAll('[data-borrow-record]').forEach(record => {
            const matchesTerm = record.textContent.toLowerCase().includes(term);
            const recordStatus = record.dataset.status;
            const matchesStatus = status === 'all'
                || (status === 'active' && ['pending', 'borrowed'].includes(recordStatus))
                || recordStatus === status;

            record.classList.toggle('hidden', !matchesTerm || !matchesStatus);
        });
    };

    if (recordSearch && recordFilter) {
        recordSearch.addEventListener('input', filterBorrowRecords);
        recordFilter.addEventListener('change', filterBorrowRecords);
    }
</script>
@endsection
@endsection
