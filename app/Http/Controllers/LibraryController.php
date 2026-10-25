<?php

namespace App\Http\Controllers;

use App\Models\LibraryBook;
use App\Models\LibraryBorrow;
use App\Models\LibrarySetting;
use App\Models\PortalStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $settings = LibrarySetting::firstOrCreate(
            ['id' => 1],
            ['opening_hours' => '8:00 AM - 5:00 PM', 'location' => 'BAUST Central Library']
        );
        $staff = PortalStaff::query()->where('unit', 'library')->orderBy('name')->get();
        $query = LibraryBook::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('department') && $request->department !== 'All') {
            $query->where('department', $request->department);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->boolean('available_only')) {
            $query->where('available_copies', '>', 0);
        }

        if ($request->input('sort') === 'title') {
            $query->orderBy('title');
        } else {
            $query->latest();
        }

        $books = $query->withCount('borrows')->paginate(12)->withQueryString();

        $userBorrows = Auth::check()
            ? LibraryBorrow::with('book')->where('user_id', Auth::id())->latest()->get()
            : collect();

        $allBorrows = (Auth::check() && (Auth::user()->isLibraryAdmin() || Auth::user()->isAdmin()))
            ? LibraryBorrow::with(['user', 'book'])->latest()->paginate(10)
            : collect();

        $stats = [
            'active_borrows' => LibraryBorrow::whereIn('status', ['pending', 'borrowed'])->count(),
            'overdue_borrows' => LibraryBorrow::whereIn('status', ['pending', 'borrowed'])
                ->whereDate('return_due_date', '<', now()->toDateString())
                ->count(),
            'returned_borrows' => LibraryBorrow::where('status', 'returned')->count(),
            'total_books' => LibraryBook::count(),
            'available_copies' => LibraryBook::sum('available_copies'),
            'total_records' => LibraryBorrow::count(),
            'today_borrows' => LibraryBorrow::whereDate('issue_date', now()->toDateString())->count(),
            'active_users' => LibraryBorrow::whereIn('status', ['pending', 'borrowed'])
                ->distinct()
                ->count('user_id'),
        ];

        $overdueQuery = LibraryBorrow::with(['book', 'user'])
            ->whereIn('status', ['pending', 'borrowed'])
            ->whereDate('return_due_date', '<', now()->toDateString());

        if (! Auth::check() || (! Auth::user()->isLibraryAdmin() && ! Auth::user()->isAdmin())) {
            $overdueQuery->where('user_id', Auth::id());
        }

        $overdueBorrows = Auth::check() ? $overdueQuery->latest()->get() : collect();

        $categories = ['Computer Science', 'Electrical Engineering', 'Mechanical Engineering', 'Civil Engineering', 'Basic Science', 'Literature'];
        $departments = ['All', 'CSE', 'EEE', 'ME', 'CE', 'IPE', 'BBA'];

        return view('library.index', compact('books', 'userBorrows', 'allBorrows', 'overdueBorrows', 'stats', 'categories', 'departments', 'settings', 'staff'));
    }

    public function borrow(Request $request, LibraryBook $book)
    {
        if (! Auth::check() || (! Auth::user()->isStudent() && ! Auth::user()->isTeacher())) {
            return back()->with('error', 'Please sign in with your student or teacher account to borrow a book.');
        }

        if ($book->available_copies <= 0) {
            return back()->with('error', 'Sorry, all copies of this book are currently checked out.');
        }

        $borrowLimit = Auth::user()->isTeacher() ? 5 : 3;
        $activeBorrowCount = LibraryBorrow::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'borrowed'])
            ->count();

        if ($activeBorrowCount >= $borrowLimit) {
            return back()->with('error', "You can borrow up to {$borrowLimit} books at a time.");
        }

        $existing = LibraryBorrow::where('user_id', Auth::id())
            ->where('library_book_id', $book->id)
            ->whereIn('status', ['pending', 'borrowed'])
            ->first();

        if ($existing) {
            return back()->with('info', 'You already have an active token for this book. Token: '.$existing->token_number);
        }

        $token = 'LIB-'.date('Y').'-'.rand(1000, 9999);

        LibraryBorrow::create([
            'user_id' => Auth::id(),
            'library_book_id' => $book->id,
            'token_number' => $token,
            'status' => 'pending',
            'issue_date' => now()->toDateString(),
            'return_due_date' => now()->addDays(LibraryBorrow::LOAN_PERIOD_DAYS)->toDateString(),
        ]);

        $book->decrement('available_copies');

        return back()->with('success', 'Book borrow request submitted! Show Token Code: '.$token.' to library desk.');
    }

    public function returnBook(LibraryBorrow $borrow)
    {
        if (! Auth::check() || (! Auth::user()->isLibraryAdmin() && ! Auth::user()->isAdmin())) {
            abort(403);
        }

        if ($borrow->status === 'returned') {
            return back()->with('info', 'This book has already been returned.');
        }

        $borrow->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);

        $borrow->book->increment('available_copies');

        return back()->with('success', 'Book marked as returned successfully!');
    }

    public function storeBook(Request $request)
    {
        if (! Auth::check() || (! Auth::user()->isLibraryAdmin() && ! Auth::user()->isAdmin())) {
            abort(403, 'Unauthorized access. Only Library Admin and Main Administrator can add catalog books.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'total_copies' => 'required|integer|min:1',
            'shelf_location' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        $validated['available_copies'] = $validated['total_copies'];
        $validated['cover_image'] = '/images/library.jpg';

        LibraryBook::create($validated);

        return back()->with('success', 'New book added to library catalog!');
    }

    public function updateBook(Request $request, LibraryBook $book)
    {
        if (! Auth::check() || (! Auth::user()->isLibraryAdmin() && ! Auth::user()->isAdmin())) {
            abort(403, 'Unauthorized access. Only Library Admin and Main Administrator can update catalog books.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'total_copies' => 'required|integer|min:1',
            'shelf_location' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        $borrowedCopies = $book->total_copies - $book->available_copies;
        if ($validated['total_copies'] < $borrowedCopies) {
            return back()->withErrors([
                'total_copies' => "At least {$borrowedCopies} copies are currently on loan.",
            ])->withInput();
        }

        $validated['available_copies'] = $validated['total_copies'] - $borrowedCopies;
        $book->update($validated);

        return back()->with('success', 'Book details updated successfully.');
    }

    public function destroyBook(LibraryBook $book)
    {
        if (! Auth::check() || (! Auth::user()->isLibraryAdmin() && ! Auth::user()->isAdmin())) {
            abort(403, 'Unauthorized access. Only Library Admin and Main Administrator can remove catalog books.');
        }

        if ($book->borrows()->exists()) {
            return back()->with('error', 'This book has borrowing history and cannot be deleted.');
        }

        $book->delete();

        return back()->with('success', 'Book removed from the library catalog.');
    }

    public function updateSettings(Request $request)
    {
        if (! Auth::check() || (! Auth::user()->isLibraryAdmin() && ! Auth::user()->isAdmin())) {
            abort(403, 'Unauthorized access. Only Library Admin and Main Administrator can update library information.');
        }

        $validated = $request->validate([
            'opening_hours' => 'required|string|max:100',
            'location' => 'required|string|max:255',
        ]);

        LibrarySetting::updateOrCreate(['id' => 1], $validated);

        return back()->with('success', 'Library information updated successfully.');
    }
}
