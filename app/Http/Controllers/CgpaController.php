<?php

namespace App\Http\Controllers;

use App\Models\CgpaRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CgpaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $records = $user ? CgpaRecord::where('user_id', $user->id)->latest()->get() : collect();

        // BAUST Standard Grading Scale Mapping
        $gradeScale = [
            'A+' => 4.00,
            'A'  => 3.75,
            'A-' => 3.50,
            'B+' => 3.25,
            'B'  => 3.00,
            'B-' => 2.75,
            'C+' => 2.50,
            'C'  => 2.25,
            'D'  => 2.00,
            'F'  => 0.00,
        ];

        // Total Degree Requirements
        $totalRequiredCredits = 160.0;
        $completedCredits = $user ? $user->completed_credits : 96.5;
        $remainingCredits = max(0, $totalRequiredCredits - $completedCredits);
        $completionPercentage = round(($completedCredits / $totalRequiredCredits) * 100, 1);

        return view('cgpa.index', compact(
            'records',
            'gradeScale',
            'totalRequiredCredits',
            'completedCredits',
            'remainingCredits',
            'completionPercentage'
        ));
    }

    public function saveRecord(Request $request)
    {
        if (!Auth::check() || Auth::user()->isGuest()) {
            return back()->with('error', 'Please login to save your academic records.');
        }

        $validated = $request->validate([
            'semester_name' => 'required|string',
            'gpa' => 'required|numeric|min:0|max:4.00',
            'credits_earned' => 'required|numeric|min:0|max:30',
            'courses_data' => 'nullable|array',
        ]);

        CgpaRecord::create([
            'user_id' => Auth::id(),
            'semester_name' => $validated['semester_name'],
            'gpa' => $validated['gpa'],
            'credits_earned' => $validated['credits_earned'],
            'courses_data' => $validated['courses_data'] ?? [],
        ]);

        // Recalculate student overall CGPA
        $records = CgpaRecord::where('user_id', Auth::id())->get();
        $totalPoints = 0;
        $totalCredits = 0;

        foreach ($records as $rec) {
            $totalPoints += ($rec->gpa * $rec->credits_earned);
            $totalCredits += $rec->credits_earned;
        }

        if ($totalCredits > 0) {
            $newCgpa = round($totalPoints / $totalCredits, 2);
            Auth::user()->update([
                'cgpa' => $newCgpa,
                'completed_credits' => $totalCredits,
            ]);
        }

        return back()->with('success', 'Semester GPA saved and overall academic progress updated!');
    }
}
