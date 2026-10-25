@extends('layouts.app')

@section('title', 'Create a Student or Teacher Account - BAUST Nexus')

@section('content')
<div class="mx-auto max-w-2xl py-8">
    <div class="glass-card rounded-3xl border border-blue-100 p-8 shadow-xl dark:border-blue-900/50">
        <div class="text-center mb-8">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-tr from-blue-700 to-sky-500 shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-user-plus text-2xl text-white"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">Create BAUST Account</h2>
            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mt-1">Student and teacher accounts are reviewed by the Main Administrator.</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-5" x-data="{ role: @js(old('role', 'student')), showPassword: false, showPasswordConfirmation: false }">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Tanvir Hasan"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="student@baust.edu.bd" :placeholder="role === 'teacher' ? 'teacher@baust.edu.bd' : 'student@baust.edu.bd'"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Account Role</label>
                    <select name="role" x-model="role" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-900 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <option value="student" {{ old('role', 'student') === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Department</label>
                    <select name="department" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        @foreach(['CSE', 'EEE', 'ME', 'CE', 'IPE', 'BBA', 'English'] as $department)
                            <option value="{{ $department }}" {{ old('department', 'CSE') === $department ? 'selected' : '' }}>{{ $department }}</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="role === 'student'" x-cloak>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Student ID</label>
                    <input type="text" name="student_id" value="{{ old('student_id') }}" placeholder="e.g. 200101045" :required="role === 'student'" :disabled="role !== 'student'"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                </div>

                <div x-show="role === 'teacher'" x-cloak>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Teacher ID</label>
                    <input type="text" name="teacher_id" value="{{ old('teacher_id') }}" placeholder="Enter your faculty ID" :required="role === 'teacher'" :disabled="role !== 'teacher'"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                </div>

                <div x-show="role === 'student'" x-cloak>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Academic Level</label>
                    <select name="level" :disabled="role !== 'student'" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <option value="Level 1">Level 1</option>
                        <option value="Level 2">Level 2</option>
                        <option value="Level 3" selected>Level 3</option>
                        <option value="Level 4">Level 4</option>
                    </select>
                </div>

                <div x-show="role === 'student'" x-cloak>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Academic Term</label>
                    <select name="term" :disabled="role !== 'student'" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <option value="Term 1">Term I</option>
                        <option value="Term 2" selected>Term II</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="Minimum 6 characters"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 pr-12 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <button type="button" @click="showPassword = !showPassword" :aria-label="showPassword ? 'Hide password' : 'Show password'" :aria-pressed="showPassword.toString()"
                            class="absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-500 transition hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-300">
                            <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Confirm Password</label>
                    <div class="relative">
                        <input :type="showPasswordConfirmation ? 'text' : 'password'" name="password_confirmation" required placeholder="Repeat password"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 pr-12 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation" :aria-label="showPasswordConfirmation ? 'Hide confirm password' : 'Show confirm password'" :aria-pressed="showPasswordConfirmation.toString()"
                            class="absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-500 transition hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-300">
                            <i :class="showPasswordConfirmation ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full rounded-xl bg-blue-700 py-3.5 text-sm font-bold uppercase text-white shadow-lg shadow-blue-600/30 transition hover:bg-blue-800">
                Complete Registration
            </button>
            <p class="text-center text-xs font-medium text-slate-500 dark:text-slate-400">
                New accounts need administrator approval before campus access is enabled.
            </p>
        </form>

        <div class="mt-6 text-center text-xs font-semibold text-slate-600 dark:text-slate-400">
            Already registered? 
            <a href="{{ route('login') }}" class="font-bold text-blue-700 hover:underline dark:text-blue-400">Sign In Here</a>
        </div>
    </div>
</div>
@endsection
