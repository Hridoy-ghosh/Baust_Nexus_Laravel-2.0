@extends('layouts.app')

@section('title', 'My Account Profile - BAUST Nexus')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="glass-card rounded-3xl p-8 border border-slate-200 dark:border-slate-700/80 shadow-xl space-y-6">
        
        <div class="flex items-center gap-4">
            @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="{{ $user->name }} profile picture" class="h-20 w-20 rounded-2xl object-cover shadow-lg ring-2 ring-emerald-400">
            @else
                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-2xl font-extrabold text-white shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300">My account</p>
                <h1 class="mt-1 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $user->name }}</h1>
                <p class="break-all font-mono text-xs text-slate-600 dark:text-slate-400">{{ $user->email }}</p>
                <span class="mt-2 inline-flex rounded-full bg-blue-100 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-blue-800 dark:bg-blue-500/20 dark:text-blue-300">{{ str_replace('_', ' ', $user->role) }}</span>
            </div>
        </div>

        @php
            $profileDetails = [
                'Department' => $user->department ?: 'Not set',
                'Phone' => $user->phone ?: 'Not set',
                'Gender' => $user->gender ? ucwords(str_replace('_', ' ', $user->gender)) : 'Not set',
                'Date of birth' => $user->date_of_birth?->format('d M, Y') ?: 'Not set',
                'Age' => $user->date_of_birth ? $user->date_of_birth->age.' years' : 'Not set',
                'Blood group' => $user->blood_group ?: 'Not set',
                'Emergency contact' => $user->emergency_contact ?: 'Not set',
                'Address' => $user->address ?: 'Not set',
            ];

            if ($user->isTeacher()) {
                $profileDetails['Teacher ID'] = $user->teacher_id ?: 'Not set';
                $profileDetails['Designation'] = $user->designation ?: 'Not set';
            } elseif ($user->isStudent()) {
                $profileDetails['Student ID'] = $user->student_id ?: 'Not set';
                $profileDetails['Level'] = $user->level ?: 'Not set';
                $profileDetails['Term'] = $user->term ?: 'Not set';
            }
        @endphp
        <section class="grid grid-cols-2 gap-3 border-t border-slate-200 pt-5 dark:border-slate-800 sm:grid-cols-3">
            @foreach($profileDetails as $label => $value)
                <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-800/70">
                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $label }}</p>
                    <p class="mt-1 break-words text-xs font-extrabold text-slate-900 dark:text-white">{{ $value }}</p>
                </div>
            @endforeach
            @if($user->isStudent())
                <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-800/70">
                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">Completed credits</p>
                    <p class="mt-1 text-xs font-extrabold text-slate-900 dark:text-white">{{ $user->completed_credits }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-800/70">
                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">CGPA</p>
                    <p class="mt-1 text-xs font-extrabold text-slate-900 dark:text-white">{{ number_format((float) $user->cgpa, 2) }}</p>
                </div>
            @endif
        </section>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 border-t border-slate-200 pt-5 dark:border-slate-800">
            @csrf

            @if(session('success'))
                <p role="status" class="rounded-xl bg-emerald-50 px-4 py-3 text-xs font-bold text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300">{{ session('success') }}</p>
            @endif
            @if($errors->any())
                <div role="alert" class="rounded-xl bg-rose-50 px-4 py-3 text-xs font-bold text-rose-800 dark:bg-rose-500/10 dark:text-rose-300">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div>
                <label for="avatar" class="mb-1 block text-xs font-bold uppercase text-slate-700 dark:text-slate-300">Profile picture</label>
                <input id="avatar" type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs font-semibold text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-3 file:py-2 file:font-bold file:text-white dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                <p class="mt-1 text-[10px] text-slate-500 dark:text-slate-400">JPG, PNG or WebP; maximum 2 MB.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white font-semibold text-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Contact Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+88017..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white font-semibold text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label for="gender" class="mb-1 block text-xs font-bold uppercase text-slate-700 dark:text-slate-300">Gender</label>
                    <select id="gender" name="gender" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <option value="">Prefer not to specify</option>
                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        <option value="prefer_not_to_say" {{ old('gender', $user->gender) === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                    </select>
                </div>
                <div>
                    <label for="date_of_birth" class="mb-1 block text-xs font-bold uppercase text-slate-700 dark:text-slate-300">Date of birth</label>
                    <input id="date_of_birth" type="date" name="date_of_birth" max="{{ now()->toDateString() }}" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                </div>
                <div>
                    <label for="blood_group" class="mb-1 block text-xs font-bold uppercase text-slate-700 dark:text-slate-300">Blood group</label>
                    <select id="blood_group" name="blood_group" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <option value="">Select blood group</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bloodGroup)
                            <option value="{{ $bloodGroup }}" {{ old('blood_group', $user->blood_group) === $bloodGroup ? 'selected' : '' }}>{{ $bloodGroup }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="emergency_contact" class="mb-1 block text-xs font-bold uppercase text-slate-700 dark:text-slate-300">Emergency contact</label>
                    <input id="emergency_contact" type="tel" name="emergency_contact" maxlength="20" autocomplete="tel" value="{{ old('emergency_contact', $user->emergency_contact) }}" placeholder="Emergency phone number" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                </div>
                <div class="sm:col-span-2">
                    <label for="address" class="mb-1 block text-xs font-bold uppercase text-slate-700 dark:text-slate-300">Address</label>
                    <textarea id="address" name="address" maxlength="500" rows="2" autocomplete="street-address" placeholder="Present address" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 {{ $user->isStudent() ? 'md:grid-cols-3' : 'md:grid-cols-1' }} gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Department</label>
                    <select name="department" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white font-semibold text-xs">
                        <option value="CSE" {{ $user->department == 'CSE' ? 'selected' : '' }}>CSE</option>
                        <option value="EEE" {{ $user->department == 'EEE' ? 'selected' : '' }}>EEE</option>
                        <option value="ME" {{ $user->department == 'ME' ? 'selected' : '' }}>ME</option>
                        <option value="CE" {{ $user->department == 'CE' ? 'selected' : '' }}>CE</option>
                        <option value="IPE" {{ $user->department == 'IPE' ? 'selected' : '' }}>IPE</option>
                        <option value="BBA" {{ $user->department == 'BBA' ? 'selected' : '' }}>BBA</option>
                        <option value="English" {{ $user->department == 'English' ? 'selected' : '' }}>English</option>
                    </select>
                </div>

                @if($user->isStudent())
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Level</label>
                        <select name="level" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white font-semibold text-xs">
                            <option value="Level 1" {{ $user->level == 'Level 1' ? 'selected' : '' }}>Level 1</option>
                            <option value="Level 2" {{ $user->level == 'Level 2' ? 'selected' : '' }}>Level 2</option>
                            <option value="Level 3" {{ $user->level == 'Level 3' ? 'selected' : '' }}>Level 3</option>
                            <option value="Level 4" {{ $user->level == 'Level 4' ? 'selected' : '' }}>Level 4</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Term</label>
                        <select name="term" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white font-semibold text-xs">
                            <option value="Term 1" {{ $user->term == 'Term 1' ? 'selected' : '' }}>Term I</option>
                            <option value="Term 2" {{ $user->term == 'Term 2' ? 'selected' : '' }}>Term II</option>
                        </select>
                    </div>
                @endif
            </div>

            @if($user->isStudent())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Completed Credits</label>
                        <input type="number" step="0.5" name="completed_credits" value="{{ old('completed_credits', $user->completed_credits) }}" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Current Cumulative CGPA</label>
                        <input type="number" step="0.01" min="0" max="4.00" name="cgpa" value="{{ old('cgpa', $user->cgpa) }}" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs">
                    </div>
                </div>
            @endif

            <button type="submit" class="w-full py-3.5 rounded-xl font-bold bg-emerald-500 text-slate-950 hover:bg-emerald-400 text-xs uppercase">
                Update Profile Settings
            </button>
        </form>

    </div>
</div>
@endsection
