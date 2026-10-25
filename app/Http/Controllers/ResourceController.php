<?php

namespace App\Http\Controllers;

use App\Models\AcademicResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = AcademicResource::query();

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('term')) {
            $query->where('term', $request->term);
        }

        if ($request->filled('resource_type')) {
            $query->where('resource_type', $request->resource_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('course_code', 'like', "%{$search}%")
                    ->orWhere('course_title', 'like', "%{$search}%");
            });
        }

        $resources = $query->latest()->paginate(12)->withQueryString();

        $departments = ['CSE', 'EEE', 'ME', 'CE', 'IPE', 'BBA', 'English'];
        $levels = ['Level 1', 'Level 2', 'Level 3', 'Level 4'];
        $terms = ['Term 1', 'Term 2'];
        $types = [
            'lecture_note' => 'Lecture Note',
            'lab_manual' => 'Lab Manual',
            'question' => 'Previous Question',
            'assignment' => 'Assignment',
            'study_material' => 'Study Material',
        ];

        return view('resources.index', compact('resources', 'departments', 'levels', 'terms', 'types'));
    }

    public function store(Request $request)
    {
        if (! Auth::check() || ! Auth::user()->canUploadResource()) {
            return back()->with('error', 'Only Teachers and Main Administrator can manage academic resources.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'resource_type' => 'required|string',
            'department' => 'required|string',
            'level' => 'required|string',
            'term' => 'required|string',
            'course_code' => 'required|string',
            'course_title' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:20480',
        ]);

        $filePath = $request->file('file')->store('academic_resources', 'local');

        AcademicResource::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'resource_type' => $validated['resource_type'],
            'department' => $validated['department'],
            'level' => $validated['level'],
            'term' => $validated['term'],
            'course_code' => strtoupper($validated['course_code']),
            'course_title' => $validated['course_title'],
            'external_link' => null,
            'file_path' => $filePath,
            'uploaded_by' => Auth::id(),
        ]);

        return back()->with('success', 'Academic Resource uploaded successfully!');
    }

    public function update(Request $request, AcademicResource $resource)
    {
        if (! Auth::check() || ! Auth::user()->canUploadResource()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'resource_type' => 'required|string',
            'department' => 'required|string',
            'level' => 'required|string',
            'term' => 'required|string',
            'course_code' => 'required|string',
            'course_title' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:20480',
        ]);

        $oldFilePath = $resource->file_path;
        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('academic_resources', 'local');
            $validated['external_link'] = null;
        }

        $resource->update($validated);

        if (isset($validated['file_path']) && $oldFilePath && Storage::disk('local')->exists($oldFilePath)
            && ! Storage::disk('local')->delete($oldFilePath)) {
            return back()->with('error', 'Resource updated, but the previous file could not be removed.');
        }

        return back()->with('success', 'Academic Resource updated successfully.');
    }

    public function destroy(AcademicResource $resource)
    {
        if (! Auth::check() || ! Auth::user()->canUploadResource()) {
            abort(403);
        }

        if ($resource->file_path && Storage::disk('local')->exists($resource->file_path)
            && ! Storage::disk('local')->delete($resource->file_path)) {
            return back()->with('error', 'Resource could not be removed because its file could not be deleted.');
        }

        $resource->delete();

        return back()->with('success', 'Academic Resource deleted successfully.');
    }

    public function download(AcademicResource $resource)
    {
        if ($resource->external_link) {
            $resource->increment('downloads_count');

            return redirect()->away($resource->external_link);
        }

        if ($resource->file_path && Storage::disk('local')->exists($resource->file_path)) {
            $resource->increment('downloads_count');

            $extension = pathinfo($resource->file_path, PATHINFO_EXTENSION);
            $downloadName = Str::slug($resource->title).($extension ? '.'.$extension : '');

            return Storage::disk('local')->download($resource->file_path, $downloadName);
        }

        return back()->with('error', 'Resource file link unavailable.');
    }
}
