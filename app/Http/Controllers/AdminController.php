<?php

namespace App\Http\Controllers;

use App\Models\AcademicResource;
use App\Models\LibraryBorrow;
use App\Models\Notice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('is_approved', false)->count();
        $requestedSection = request()->query('section');
        $activeSection = in_array($requestedSection, ['pending', 'edit', 'features'], true)
            ? $requestedSection
            : (request()->query('status') === 'pending' ? 'pending' : 'edit');
        $userStatus = $activeSection === 'pending' ? 'pending' : 'all';
        $usersQuery = User::latest();
        if ($userStatus === 'pending') {
            $usersQuery->where('is_approved', false);
        }
        $users = $usersQuery->paginate(15)->withQueryString();
        $totalUsers = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalResources = AcademicResource::count();
        $totalBorrows = LibraryBorrow::count();
        $totalNotices = Notice::count();

        return view('admin.index', compact(
            'users',
            'pendingUsers',
            'totalUsers',
            'activeSection',
            'userStatus',
            'totalStudents',
            'totalTeachers',
            'totalResources',
            'totalBorrows',
            'totalNotices'
        ));
    }

    public function toggleUserApproval(User $user)
    {
        if ($user->is(auth()->user()) && $user->is_approved) {
            return back()->with('error', 'You cannot revoke approval from your own administrator account.');
        }

        $user->update(['is_approved' => ! $user->is_approved]);

        return back()->with('success', 'User approval status toggled.');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'student_id' => 'nullable|string|max:50',
            'teacher_id' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:100',
            'level' => 'nullable|string|max:50',
            'term' => 'nullable|string|max:50',
            'designation' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'emergency_contact' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        if ($user->isStudent()) {
            $validated['teacher_id'] = null;
            $validated['designation'] = null;
        } elseif ($user->isTeacher()) {
            $validated['student_id'] = null;
            $validated['level'] = null;
            $validated['term'] = null;
        } else {
            $validated['student_id'] = null;
            $validated['teacher_id'] = null;
            $validated['level'] = null;
            $validated['term'] = null;
            $validated['designation'] = null;
        }

        $user->update($validated);

        return back()->with('success', 'User details updated successfully.');
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:student,teacher,admin,cafe_admin,library_admin',
        ]);

        if ($user->is(auth()->user()) && $request->role !== 'admin') {
            return back()->with('error', 'You cannot change your own administrator role.');
        }

        if ($user->isAdmin() && $request->role !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'The system must keep at least one main administrator.');
        }

        $roleFields = match ($request->role) {
            'student' => ['teacher_id' => null, 'designation' => null],
            'teacher' => ['student_id' => null, 'level' => null, 'term' => null],
            default => [
                'student_id' => null,
                'teacher_id' => null,
                'level' => null,
                'term' => null,
                'designation' => null,
            ],
        };

        $user->update(['role' => $request->role, ...$roleFields]);

        return back()->with('success', 'User role updated to '.ucfirst($request->role).'.');
    }

    public function deleteUser(User $user)
    {
        if ($user->is(auth()->user())) {
            return back()->with('error', 'You cannot remove your own administrator account.');
        }

        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the main administrator.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
