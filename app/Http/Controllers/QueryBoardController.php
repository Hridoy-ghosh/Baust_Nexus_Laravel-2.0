<?php

namespace App\Http\Controllers;

use App\Models\QueryPost;
use App\Models\QueryReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueryBoardController extends Controller
{
    public function index(Request $request)
    {
        $query = QueryPost::with(['user', 'replies.user']);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest()->paginate(10)->withQueryString();

        $departments = ['CSE', 'EEE', 'ME', 'CE', 'IPE', 'BBA', 'English'];
        $categories = [
            'academic' => 'Academic & Courses',
            'campus' => 'Campus Life & Events',
            'admin' => 'Administrative & Accounts',
            'general' => 'General Discussion',
        ];

        return view('queries.index', compact('posts', 'departments', 'categories'));
    }

    public function show(QueryPost $post)
    {
        $post->load(['user', 'replies.user']);

        return view('queries.show', compact('post'));
    }

    public function store(Request $request)
    {
        if (! Auth::check() || Auth::user()->isGuest()) {
            return back()->with('error', 'Guest users cannot post questions. Please register or login.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'department' => 'required|string',
            'level' => 'required|string',
            'is_anonymous' => 'nullable|boolean',
        ]);

        $post = QueryPost::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'department' => $validated['department'],
            'level' => $validated['level'],
            'is_anonymous' => $request->has('is_anonymous'),
            'status' => 'open',
        ]);

        return back()->with('success', 'Your question has been posted to the Query Board!');
    }

    public function reply(Request $request, QueryPost $post)
    {
        if (! Auth::check() || Auth::user()->isGuest()) {
            return back()->with('error', 'Guest users cannot post replies.');
        }

        $validated = $request->validate([
            'content' => 'required|string',
            'is_anonymous' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        $roleBadge = null;

        if ($user->isTeacher()) {
            $roleBadge = 'Teacher ('.($user->department ?? 'BAUST').')';
        } elseif ($user->isAdmin()) {
            $roleBadge = 'Admin';
        } elseif ($user->role === 'cafe_admin') {
            $roleBadge = 'Cafe Admin';
        } elseif ($user->role === 'library_admin') {
            $roleBadge = 'Library Admin';
        } elseif ($user->level === 'Level 4') {
            $roleBadge = 'Senior ('.$user->level.')';
        } else {
            $roleBadge = 'Student ('.$user->level.')';
        }

        QueryReply::create([
            'query_post_id' => $post->id,
            'user_id' => $user->id,
            'content' => $validated['content'],
            'is_anonymous' => $request->has('is_anonymous'),
            'role_badge' => $roleBadge,
        ]);

        $post->increment('replies_count');

        return back()->with('success', 'Reply posted successfully!');
    }

    public function toggleResolve(QueryPost $post)
    {
        if (! Auth::check() || (Auth::id() !== $post->user_id && ! Auth::user()->isAdmin())) {
            abort(403);
        }

        $newStatus = $post->status === 'resolved' ? 'open' : 'resolved';
        $post->update(['status' => $newStatus]);

        return back()->with('success', 'Query status marked as '.strtoupper($newStatus).'.');
    }

    public function destroy(QueryPost $post)
    {
        if (! Auth::check() || (Auth::id() !== $post->user_id && ! Auth::user()->isAdmin())) {
            abort(403);
        }

        $post->delete();

        return redirect()->route('queries.index')->with('success', 'Question deleted.');
    }
}
