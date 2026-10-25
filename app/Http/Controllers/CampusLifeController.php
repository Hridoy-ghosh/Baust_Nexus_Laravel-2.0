<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CampusLifeController extends Controller
{
    public function dressCode()
    {
        $maleRules = [
            'Regular Class Days' => 'Formal shirt (full/half sleeve), formal trousers, dark leather belt, closed formal shoes with socks. Clean shaved or well-groomed beard.',
            'Lab & Workshop Days' => 'Standard white lab aprons compulsory during all laboratory sessions.',
            'Winter Attire' => 'Navy blue / Dark grey blazer, formal sweater, or navy cardigan.',
            'Footwear' => 'Black or dark brown formal leather shoes. Sandals, slippers, or flip-flops are strictly prohibited inside academic blocks.',
        ];

        $femaleRules = [
            'Regular Class Days' => 'Modest Salwar Kameez with Dupatta / Formal attire. Clean and neat appearance adhering to university decorum.',
            'Lab & Workshop Days' => 'White lab coat over standard attire during chemistry, physics, and engineering laboratory sessions.',
            'Winter Attire' => 'Formal cardigan, blazer, or modest coat in dark navy or neutral shades.',
            'Footwear' => 'Closed shoes, pumps, or formal sandals. High heels and casual slippers are not recommended.',
        ];

        $generalRules = [
            'BAUST ID Card' => 'Student ID Card with lanyard MUST be visible around the neck at all times inside campus.',
            'Disciplinary Action' => 'Students violating the dress code policy will be reported to the Proctorial Body and denied entry to class/exams.',
        ];

        return view('campus.dress_code', compact('maleRules', 'femaleRules', 'generalRules'));
    }

    public function academicCalendar()
    {
        $events = [
            [
                'title' => 'Start of Spring Semester 2026 Classes',
                'date' => 'July 1, 2026',
                'category' => 'Academic',
                'description' => 'Commencement of regular classroom lectures and lab sessions for all levels.',
            ],
            [
                'title' => 'First Class Test (CT-1) Examination Week',
                'date' => 'August 10 - August 16, 2026',
                'category' => 'Exam',
                'description' => 'First internal evaluation for 1st, 2nd, 3rd & 4th year students.',
            ],
            [
                'title' => 'Mid-Semester Recess & National Holiday',
                'date' => 'September 1, 2026',
                'category' => 'Holiday',
                'description' => 'Campus remains closed for Mid-Semester Recess.',
            ],
            [
                'title' => 'Second Class Test (CT-2) & Assignment Deadline',
                'date' => 'October 12 - October 18, 2026',
                'category' => 'Exam',
                'description' => 'Submission of lab project reports and final class tests.',
            ],
            [
                'title' => 'Spring 2026 Semester Final Examinations',
                'date' => 'November 15 - December 05, 2026',
                'category' => 'Final Exam',
                'description' => 'Central semester final examinations conducted by BAUST Exam Section.',
            ],
        ];

        return view('campus.calendar', compact('events'));
    }
}
