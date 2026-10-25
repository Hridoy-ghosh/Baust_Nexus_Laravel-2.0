<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->to($this->destinationFor(Auth::user()));
        }

        return view('auth.login', [
            'role' => null,
            'roleLabel' => null,
            'loginRoute' => 'login',
        ]);
    }

    public function login(Request $request)
    {
        return $this->authenticate($request);
    }

    public function showAdminLogin()
    {
        return $this->showRoleLogin('admin');
    }

    public function adminLogin(Request $request)
    {
        return $this->authenticate($request, 'admin');
    }

    public function showCafeAdminLogin()
    {
        return $this->showRoleLogin('cafe_admin');
    }

    public function cafeAdminLogin(Request $request)
    {
        return $this->authenticate($request, 'cafe_admin');
    }

    public function showLibraryAdminLogin()
    {
        return $this->showRoleLogin('library_admin');
    }

    public function libraryAdminLogin(Request $request)
    {
        return $this->authenticate($request, 'library_admin');
    }

    private function showRoleLogin(string $role)
    {
        if (Auth::check()) {
            return redirect()->to($this->destinationFor(Auth::user()));
        }

        return view('auth.login', [
            'role' => $role,
            'roleLabel' => match ($role) {
                'admin' => 'Main Administrator',
                'cafe_admin' => 'Cafe Administrator',
                'library_admin' => 'Library Administrator',
            },
            'loginRoute' => 'login.' . match ($role) {
                'admin' => 'admin',
                'cafe_admin' => 'cafe',
                'library_admin' => 'library',
            } . '.submit',
        ]);
    }

    private function authenticate(Request $request, ?string $requiredRole = null)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();

            if ($user->isGuest()) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Guest visitors can use Guest Mode without signing in.',
                ])->onlyInput('email');
            }

            if ($requiredRole && $user->role !== $requiredRole) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'This sign-in page is only for ' . str_replace('_', ' ', $requiredRole) . ' accounts.',
                ])->onlyInput('email');
            }

            if (!$user->is_approved && !$user->isAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account registration is pending Admin approval. Please wait for an administrator to approve your account before logging in.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            $destination = $this->destinationFor($user);

            return $requiredRole
                ? redirect()->to($destination)->with('success', 'Welcome back, ' . $user->name . '!')
                : redirect()->intended($destination)->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:student,teacher',
            'student_id' => 'required_if:role,student|nullable|string|max:50',
            'teacher_id' => 'required_if:role,teacher|nullable|string|max:50',
            'department' => 'required|string',
            'level' => 'exclude_unless:role,student|nullable|string',
            'term' => 'exclude_unless:role,student|nullable|string',
            'designation' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'student_id' => $validated['student_id'] ?? null,
            'teacher_id' => $validated['teacher_id'] ?? null,
            'department' => $validated['department'],
            'level' => $validated['role'] === 'student' ? ($validated['level'] ?? 'Level 1') : null,
            'term' => $validated['role'] === 'student' ? ($validated['term'] ?? 'Term 1') : null,
            'designation' => $validated['designation'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'is_approved' => false,
        ]);

        return redirect()->route('login')->with('success', 'Account registered successfully! Your account is currently pending Admin approval. Once approved, you can log in.');
    }

    private function destinationFor(User $user): string
    {
        return match ($user->role) {
            'admin' => route('admin.index'),
            'cafe_admin' => route('cafe.index'),
            'library_admin' => route('library.index'),
            default => route('dashboard'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'You have been logged out.');
    }

    public function profile()
    {
        $user = Auth::user();
        $avatarUrl = $user->avatar ? Storage::disk('public')->url($user->avatar) : null;

        return view('profile.show', compact('user', 'avatarUrl'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string',
            'level' => 'nullable|string',
            'term' => 'nullable|string',
            'designation' => 'nullable|string',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'emergency_contact' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'completed_credits' => 'nullable|numeric|min:0|max:200',
            'cgpa' => 'nullable|numeric|min:0|max:4.00',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($user->isTeacher()) {
            $validated['level'] = null;
            $validated['term'] = null;
        }

        $oldAvatar = $user->avatar;
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        if (isset($validated['avatar']) && $oldAvatar) {
            Storage::disk('public')->delete($oldAvatar);
        }

        return back()->with('success', 'Profile updated successfully!');
    }
}
