<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $query = Notice::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('department') && $request->department !== 'All') {
            $query->where(function ($q) use ($request) {
                $q->where('target_department', $request->department)
                  ->orWhere('target_department', 'All');
            });
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('content', 'like', "%{$request->search}%");
        }

        $pinnedNotices = (clone $query)->where('is_pinned', true)->latest()->get();
        $notices = $query->orderBy('is_pinned', 'desc')->latest()->paginate(10)->withQueryString();

        $departments = ['All', 'CSE', 'EEE', 'ME', 'CE', 'IPE', 'BBA', 'English'];

        return view('notices.index', compact('notices', 'pinnedNotices', 'departments'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isTeacher()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'target_department' => 'required|string',
            'target_level' => 'required|string',
            'is_pinned' => 'nullable|boolean',
            'is_urgent' => 'nullable|boolean',
        ]);

        Notice::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'target_department' => $validated['target_department'],
            'target_level' => $validated['target_level'],
            'is_pinned' => $request->has('is_pinned'),
            'is_urgent' => $request->has('is_urgent'),
            'published_by' => Auth::id(),
        ]);

        return back()->with('success', 'Notice published successfully on Smart Notice Board!');
    }
}
