@extends('layouts.app')

@section('title', 'Academic Resource Hub - BAUST Nexus')

@section('content')
<div class="mx-auto max-w-7xl space-y-6" x-data="{ editResource: {}, editingResource: false }">

    <!-- Header & Action Bar -->
    <div class="flex flex-col items-start justify-between gap-6 rounded-3xl border border-blue-800/40 bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-800 p-6 text-white shadow-xl md:flex-row md:items-center md:p-8">
        <div>
            <span class="inline-flex items-center gap-2 rounded-full border border-blue-300/30 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-blue-100">
                <i class="fa-solid fa-graduation-cap"></i>
                Academic Repository
            </span>
            <h1 class="mt-3 text-2xl font-extrabold tracking-tight sm:text-3xl">BAUST Academic Resources</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-blue-100">Find notes, lab manuals, previous questions and study materials by department, level and term.</p>
        </div>

        @auth
            <!-- Strict Rule: Only Teachers & Main Administrator can upload resources! Students cannot upload. -->
            @if(Auth::user()->canUploadResource())
                <button type="button" onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-extrabold text-blue-900 shadow-lg transition hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-300/40">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload Resource
                </button>
            @else
                <div class="flex items-center gap-2 rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-xs font-semibold text-blue-50">
                    <i class="fa-solid fa-download"></i> Download resources
                </div>
            @endif
        @endauth
    </div>

    <!-- Filter & Search Bar -->
    <div class="glass-card rounded-2xl border border-blue-100 p-5 shadow-sm dark:border-blue-950">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
            <div>
                <h2 class="text-sm font-extrabold text-slate-900 dark:text-white">Find a resource</h2>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Choose your filters, then apply them together.</p>
            </div>
            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-800 dark:bg-blue-500/10 dark:text-blue-200">{{ $resources->total() }} resources</span>
        </div>
        <form action="{{ route('resources.index') }}" method="GET" class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5">
            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-400 uppercase mb-1">Department</label>
                <select name="department" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-400 uppercase mb-1">Level</label>
                <select name="level" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <option value="">All Levels</option>
                    @foreach($levels as $lvl)
                        <option value="{{ $lvl }}" {{ request('level') == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-400 uppercase mb-1">Term</label>
                <select name="term" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <option value="">All Terms</option>
                    @foreach($terms as $t)
                        <option value="{{ $t }}" {{ request('term') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-400 uppercase mb-1">Resource Type</label>
                <select name="resource_type" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <option value="">All Types</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ request('resource_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-400 uppercase mb-1">Search Keyword</label>
                <div class="flex gap-1">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Course code or title..." class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder-slate-500">
                    <button type="submit" aria-label="Search resources" class="rounded-xl bg-blue-700 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300/40">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Resource Grid -->
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse($resources as $res)
            <article class="glass-card flex flex-col justify-between rounded-2xl border border-slate-200 p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-lg dark:border-slate-800 dark:hover:border-blue-900">
                <div>
                    <div class="mb-4 flex items-center justify-between gap-2">
                        <span class="rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1 text-[10px] font-extrabold uppercase text-blue-800 dark:border-blue-500/30 dark:bg-blue-500/15 dark:text-blue-200">
                            {{ strtoupper(str_replace('_', ' ', $res->resource_type)) }}
                        </span>
                        <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-300">{{ $res->course_code }}</span>
                    </div>

                    <h3 class="text-base font-extrabold leading-snug text-slate-900 transition hover:text-blue-700 dark:text-white dark:hover:text-blue-300">
                        {{ $res->title }}
                    </h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Course: <strong class="text-slate-900 dark:text-slate-200 font-bold">{{ $res->course_title }}</strong></p>

                    <p class="text-xs text-slate-700 dark:text-slate-300 mt-3 line-clamp-3 leading-relaxed">
                        {{ $res->description ?? 'No extra description provided.' }}
                    </p>
                </div>

                <div class="mt-5 border-t border-slate-200 pt-4 dark:border-slate-800/80">
                    <div class="mb-3 flex items-center justify-between gap-2 text-[11px] font-medium text-slate-600 dark:text-slate-400">
                        <span><i class="fa-solid fa-layer-group mr-1 text-blue-600 dark:text-blue-400"></i> {{ $res->department }} ({{ $res->level }} {{ $res->term }})</span>
                        <span><i class="fa-solid fa-download mr-1 text-slate-500"></i> {{ $res->downloads_count }} Downloads</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('resources.download', $res->id) }}" class="flex min-h-10 flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-3 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700">
                            <i class="fa-solid fa-download"></i> Download Resource
                        </a>
                        @auth
                            @if(Auth::user()->canUploadResource())
                                <button type="button"
                                        title="Edit resource"
                                        aria-label="Edit {{ $res->title }}"
                                        @click="editResource = @js([
                                            'id' => $res->id,
                                            'title' => $res->title,
                                            'description' => $res->description,
                                            'resource_type' => $res->resource_type,
                                            'department' => $res->department,
                                            'level' => $res->level,
                                            'term' => $res->term,
                                            'course_code' => $res->course_code,
                                            'course_title' => $res->course_title,
                                            'updateUrl' => route('resources.update', $res->id),
                                        ]); editingResource = true"
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 transition hover:bg-blue-200 dark:bg-blue-500/15 dark:text-blue-300 dark:hover:bg-blue-500/25">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('resources.destroy', $res->id) }}" method="POST" onsubmit="return confirm('Delete this academic resource? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete resource" aria-label="Delete {{ $res->title }}" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-700 transition hover:bg-rose-200 dark:bg-rose-500/15 dark:text-rose-300 dark:hover:bg-rose-500/25">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full glass-card p-12 rounded-3xl text-center text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-800">
                <i class="fa-solid fa-folder-open text-4xl text-slate-400 dark:text-slate-600 mb-3 block"></i>
                <p class="text-base font-semibold">No academic resources matching criteria found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $resources->links() }}
    </div>

@auth
    @if(Auth::user()->canUploadResource())
        <div x-show="editingResource" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/70 p-4 backdrop-blur-sm" @click.self="editingResource = false" @keydown.escape.window="editingResource = false">
            <div class="my-auto w-full max-w-xl rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-700 dark:bg-slate-900 md:p-8">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300">Teacher / Admin tools</span>
                        <h2 class="mt-1 text-xl font-extrabold text-slate-900 dark:text-white">Edit academic resource</h2>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Replace the attached file only if you select a new one.</p>
                    </div>
                    <button type="button" @click="editingResource = false" aria-label="Close edit dialog" class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 dark:hover:bg-slate-800">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data" :action="editingResource ? editResource.updateUrl : '#'" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="space-y-1 sm:col-span-2">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Resource title</span>
                            <input name="title" x-model="editResource.title" required maxlength="255" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        </label>
                        <label class="space-y-1">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Course code</span>
                            <input name="course_code" x-model="editResource.course_code" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        </label>
                        <label class="space-y-1">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Course title</span>
                            <input name="course_title" x-model="editResource.course_title" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        </label>
                        <label class="space-y-1">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Department</span>
                            <select name="department" x-model="editResource.department" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @foreach($departments as $dept)<option value="{{ $dept }}">{{ $dept }}</option>@endforeach
                            </select>
                        </label>
                        <label class="space-y-1">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Level</span>
                            <select name="level" x-model="editResource.level" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @foreach($levels as $lvl)<option value="{{ $lvl }}">{{ $lvl }}</option>@endforeach
                            </select>
                        </label>
                        <label class="space-y-1">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Term</span>
                            <select name="term" x-model="editResource.term" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @foreach($terms as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach
                            </select>
                        </label>
                        <label class="space-y-1">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Resource type</span>
                            <select name="resource_type" x-model="editResource.resource_type" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @foreach($types as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                            </select>
                        </label>
                        <label class="space-y-1 sm:col-span-2">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Description</span>
                            <textarea name="description" x-model="editResource.description" rows="3" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white"></textarea>
                        </label>
                        <label class="space-y-1 sm:col-span-2">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Replace file (optional)</span>
                            <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 text-xs text-slate-700 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300">
                        </label>
                    </div>
                    <div class="flex justify-end gap-2 border-t border-slate-200 pt-4 dark:border-slate-800">
                        <button type="button" @click="editingResource = false" class="rounded-xl border border-slate-300 px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Cancel</button>
                        <button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-xs font-bold text-white transition hover:bg-blue-800">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endauth

<!-- Upload Resource Modal (For Teachers & Main Admin Only) -->
@auth
    @if(Auth::user()->canUploadResource())
        <div id="uploadModal" class="{{ $errors->has('file') ? '' : 'hidden' }} fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-md flex items-center justify-center p-4">
            <div class="glass-card max-w-xl w-full rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-slate-700 shadow-2xl relative bg-white dark:bg-slate-900">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up text-blue-700 dark:text-blue-300"></i> Upload Academic Resource
                    </h3>
                    <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 dark:hover:text-white transition">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" x-data="{ fileName: '' }">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Resource Title</label>
                        <input type="text" name="title" required placeholder="e.g. CSE-3201 Lecture Slides 01" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-semibold">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Course Code</label>
                            <input type="text" name="course_code" required placeholder="e.g. CSE-3201" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Course Title</label>
                            <input type="text" name="course_title" required placeholder="e.g. Web Engineering" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Department</label>
                            <select name="department" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-semibold">
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Level</label>
                            <select name="level" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-semibold">
                                @foreach($levels as $lvl)
                                    <option value="{{ $lvl }}">{{ $lvl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Term</label>
                            <select name="term" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-semibold">
                                @foreach($terms as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Resource Category</label>
                        <select name="resource_type" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-semibold">
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="resource-file" class="mb-1 block text-xs font-bold uppercase text-slate-700 dark:text-slate-300">Choose file from your laptop</label>
                        <label for="resource-file" class="flex cursor-pointer items-center gap-3 rounded-xl border border-dashed border-blue-300 bg-blue-50/70 p-4 transition hover:border-blue-500 hover:bg-blue-100/70 dark:border-blue-500/40 dark:bg-blue-500/5 dark:hover:bg-blue-500/10">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300">
                                <i class="fa-solid fa-folder-open"></i>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-xs font-extrabold text-blue-800 dark:text-blue-200">Browse and select a resource</span>
                                <span class="mt-1 block truncate text-[11px] text-slate-500 dark:text-slate-400" x-text="fileName || 'PDF, DOC, DOCX, PPT, PPTX or ZIP · up to 20 MB'"></span>
                            </span>
                            <span class="rounded-lg bg-blue-700 px-3 py-2 text-[11px] font-bold text-white dark:bg-blue-500 dark:text-slate-950">Browse</span>
                        </label>
                        <input id="resource-file" type="file" name="file" required accept=".pdf,.doc,.docx,.ppt,.pptx,.zip"
                               @change="fileName = $event.target.files[0]?.name || ''"
                               class="sr-only">
                        @error('file')
                            <span class="mt-1 block text-xs font-bold text-rose-600 dark:text-rose-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Description</label>
                        <textarea name="description" rows="2" placeholder="Brief outline of notes or lab details..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-semibold"></textarea>
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-blue-700 py-3 text-xs font-bold uppercase tracking-wider text-white shadow-lg transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300/40">
                        Upload to BAUST Nexus Repository
                    </button>
                </form>
            </div>
        </div>
    @endif
@endauth

</div>
@endsection
