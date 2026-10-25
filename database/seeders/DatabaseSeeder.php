<?php

namespace Database\Seeders;

use App\Models\AcademicResource;
use App\Models\BusRoute;
use App\Models\CgpaRecord;
use App\Models\LibraryBook;
use App\Models\LibraryBorrow;
use App\Models\Notice;
use App\Models\QueryPost;
use App\Models\QueryReply;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $staffAccounts = [
            'admin' => [
                'email' => env('BAUST_MAIN_ADMIN_EMAIL', 'admin@baust.edu.bd'),
                'password' => env('BAUST_MAIN_ADMIN_PASSWORD'),
            ],
            'cafe_admin' => [
                'email' => env('BAUST_CAFE_ADMIN_EMAIL', 'cafeadmin@baust.edu.bd'),
                'password' => env('BAUST_CAFE_ADMIN_PASSWORD'),
            ],
            'library_admin' => [
                'email' => env('BAUST_LIBRARY_ADMIN_EMAIL', 'libraryadmin@baust.edu.bd'),
                'password' => env('BAUST_LIBRARY_ADMIN_PASSWORD'),
            ],
        ];

        $emails = array_column($staffAccounts, 'email');
        $passwords = array_column($staffAccounts, 'password');

        if (count(array_unique($emails)) !== count($emails)) {
            throw new \InvalidArgumentException('Main, Cafe and Library administrator email addresses must be unique.');
        }

        foreach ($passwords as $password) {
            if (! is_string($password) || strlen($password) < 12) {
                throw new \InvalidArgumentException('Set a unique administrator password of at least 12 characters for each staff role in .env.');
            }
        }

        if (count(array_unique($passwords)) !== count($passwords)) {
            throw new \InvalidArgumentException('Main, Cafe and Library administrator passwords must all be different.');
        }

        // 1. Create Default Role Users
        $admin = User::create([
            'name' => 'BAUST System Admin',
            'email' => $staffAccounts['admin']['email'],
            'password' => Hash::make($staffAccounts['admin']['password']),
            'role' => 'admin',
            'student_id' => 'ADMIN-01',
            'department' => 'Administration',
            'phone' => '+8801700000000',
            'is_approved' => true,
        ]);

        $cafeAdmin = User::create([
            'name' => 'Cafeteria Manager',
            'email' => $staffAccounts['cafe_admin']['email'],
            'password' => Hash::make($staffAccounts['cafe_admin']['password']),
            'role' => 'cafe_admin',
            'department' => 'Cafeteria',
            'phone' => '+8801711002233',
            'is_approved' => true,
        ]);

        $libraryAdmin = User::create([
            'name' => 'Head Librarian',
            'email' => $staffAccounts['library_admin']['email'],
            'password' => Hash::make($staffAccounts['library_admin']['password']),
            'role' => 'library_admin',
            'department' => 'Library',
            'phone' => '+8801711445566',
            'is_approved' => true,
        ]);

        $teacher = User::create([
            'name' => 'Dr. Md. Mahbubur Rahman',
            'email' => 'teacher@baust.edu.bd',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'designation' => 'Associate Professor & Head',
            'department' => 'CSE',
            'phone' => '+8801711223344',
            'is_approved' => true,
        ]);

        $student = User::create([
            'name' => 'Tanvir Hasan',
            'email' => 'student@baust.edu.bd',
            'password' => Hash::make('password'),
            'role' => 'student',
            'student_id' => '200101045',
            'department' => 'CSE',
            'level' => 'Level 3',
            'term' => 'Term 2',
            'phone' => '+8801812345678',
            'completed_credits' => 96.5,
            'cgpa' => 3.82,
            'is_approved' => true,
        ]);

        $seniorStudent = User::create([
            'name' => 'Naimur Rahman (Senior)',
            'email' => 'senior@baust.edu.bd',
            'password' => Hash::make('password'),
            'role' => 'student',
            'student_id' => '190101012',
            'department' => 'CSE',
            'level' => 'Level 4',
            'term' => 'Term 2',
            'phone' => '+8801912345678',
            'completed_credits' => 142.0,
            'cgpa' => 3.89,
            'is_approved' => true,
        ]);

        // 2. Academic Resources
        AcademicResource::create([
            'title' => 'CSE-3201 Web Engineering Lecture Notes - Module 1 to 4',
            'description' => 'Comprehensive HTML5, CSS3, JavaScript ES6+, Laravel & Node.js Architecture lecture slides.',
            'resource_type' => 'lecture_note',
            'department' => 'CSE',
            'level' => 'Level 3',
            'term' => 'Term 2',
            'course_code' => 'CSE-3201',
            'course_title' => 'Web Engineering',
            'external_link' => 'https://drive.google.com/file/d/sample_web_eng_notes/view',
            'uploaded_by' => $teacher->id,
            'downloads_count' => 142,
        ]);

        AcademicResource::create([
            'title' => 'CSE-3202 Web Engineering Lab Manual & Project Guidelines',
            'description' => 'Step-by-step instructions for developing full-stack web applications using Laravel.',
            'resource_type' => 'lab_manual',
            'department' => 'CSE',
            'level' => 'Level 3',
            'term' => 'Term 2',
            'course_code' => 'CSE-3202',
            'course_title' => 'Web Engineering Lab',
            'external_link' => 'https://drive.google.com/file/d/sample_lab_manual/view',
            'uploaded_by' => $teacher->id,
            'downloads_count' => 98,
        ]);

        AcademicResource::create([
            'title' => 'EEE-2105 Electrical Circuit Analysis Lecture Slides',
            'description' => 'AC Circuit Analysis, Phasor Diagrams, and Resonance lecture notes.',
            'resource_type' => 'lecture_note',
            'department' => 'EEE',
            'level' => 'Level 2',
            'term' => 'Term 1',
            'course_code' => 'EEE-2105',
            'course_title' => 'Circuit Analysis',
            'external_link' => 'https://drive.google.com/file/d/sample_eee_notes/view',
            'uploaded_by' => $teacher->id,
            'downloads_count' => 84,
        ]);

        // 3. Notices
        Notice::create([
            'title' => '📢 Final Examination Schedule - Spring 2026 Semester',
            'content' => 'The final semester examinations for all departments (CSE, EEE, ME, CE, IPE, BBA) will commence from November 15, 2026. Admit cards can be downloaded from the portal starting November 5.',
            'category' => 'exam',
            'target_department' => 'All',
            'target_level' => 'All',
            'is_pinned' => true,
            'is_urgent' => true,
            'published_by' => $admin->id,
        ]);

        Notice::create([
            'title' => '🚀 BAUST National Inter-University Tech Fest & Hackathon 2026',
            'content' => 'BAUST Computer Club is organizing the Annual National Tech Fest featuring Web & App Development Hackathon, Competitive Programming, and Robotics Contest. Register your teams before October 25.',
            'category' => 'event',
            'target_department' => 'CSE',
            'target_level' => 'All',
            'is_pinned' => true,
            'is_urgent' => false,
            'published_by' => $teacher->id,
        ]);

        Notice::create([
            'title' => '🚌 Revised BAUST Transport Bus Timings for Winter Schedule',
            'content' => 'Effective next Sunday, the morning campus bus departures from Rangpur and Saidpur will start 15 minutes earlier at 7:30 AM to avoid fog delays.',
            'category' => 'academic',
            'target_department' => 'All',
            'target_level' => 'All',
            'is_pinned' => false,
            'is_urgent' => false,
            'published_by' => $admin->id,
        ]);

        // 4. Library Books
        $book1 = LibraryBook::create([
            'title' => 'Operating System Concepts (10th Edition)',
            'author' => 'Abraham Silberschatz, Peter B. Galvin',
            'isbn' => '978-1118063330',
            'category' => 'Computer Science',
            'department' => 'CSE',
            'total_copies' => 10,
            'available_copies' => 8,
            'shelf_location' => 'Rack CS-04',
            'cover_image' => '/images/library.jpg',
            'description' => 'The fundamental textbook for operating system structure, process management, memory allocation, and file systems.',
        ]);

        $book2 = LibraryBook::create([
            'title' => 'Laravel: Up & Running - A Framework for Building Modern PHP Apps',
            'author' => 'Matt Stauffer',
            'isbn' => '978-1492041214',
            'category' => 'Computer Science',
            'department' => 'CSE',
            'total_copies' => 5,
            'available_copies' => 3,
            'shelf_location' => 'Rack CS-01',
            'cover_image' => '/images/library.jpg',
            'description' => 'In-depth guide to Laravel routing, Blade, Eloquent ORM, authentication, and REST APIs.',
        ]);

        LibraryBook::create([
            'title' => 'Introduction to Algorithms (4th Edition - CLRS)',
            'author' => 'Thomas H. Cormen, Charles E. Leiserson',
            'isbn' => '978-0262046305',
            'category' => 'Computer Science',
            'department' => 'CSE',
            'total_copies' => 12,
            'available_copies' => 10,
            'shelf_location' => 'Rack CS-08',
            'cover_image' => '/images/library.jpg',
            'description' => 'The standard algorithms reference for graph theory, dynamic programming, sorting, and complexity analysis.',
        ]);

        LibraryBook::create([
            'title' => 'Fundamentals of Electric Circuits (7th Edition)',
            'author' => 'Charles K. Alexander, Matthew N. O. Sadiku',
            'isbn' => '978-1260226409',
            'category' => 'Electrical Engineering',
            'department' => 'EEE',
            'total_copies' => 8,
            'available_copies' => 6,
            'shelf_location' => 'Rack EEE-02',
            'cover_image' => '/images/library.jpg',
            'description' => 'Comprehensive textbook for introductory circuit analysis and network theorems.',
        ]);

        // 5. Library Borrow Sample
        LibraryBorrow::create([
            'user_id' => $student->id,
            'library_book_id' => $book2->id,
            'token_number' => 'LIB-2026-9042',
            'status' => 'borrowed',
            'issue_date' => now()->subDays(3)->toDateString(),
            'return_due_date' => now()->addDays(11)->toDateString(),
        ]);

        // 6. Bus Routes
        BusRoute::create([
            'route_code' => 'R-01',
            'route_name' => 'Saidpur Bus Stand ↔ BAUST Campus',
            'description' => 'Departs from Saidpur Central Bus Stand via VIP Road & Cantonment Gate to BAUST Campus.',
            'morning_schedule' => '7:45 AM (Prompt)',
            'afternoon_schedule' => '1:45 PM & 5:00 PM',
            'pickup_points' => 'Saidpur Central Stand, Airport Junction, Bangabandhu Chattar, Cantonment Main Gate, BAUST Gate',
            'bus_number' => 'BAUST Bus #01 (Nitol Tata)',
            'driver_name' => 'Md. Rafiqul Islam',
            'driver_phone' => '+8801712-345678',
            'status' => 'On Time',
        ]);

        BusRoute::create([
            'route_code' => 'R-02',
            'route_name' => 'Rangpur Modern Circular ↔ BAUST Campus',
            'description' => 'Express route connecting Rangpur City Modern More to BAUST Campus.',
            'morning_schedule' => '7:20 AM',
            'afternoon_schedule' => '5:10 PM',
            'pickup_points' => 'Modern Circular, Medical More, Lalbagh, RK Road, Medical East, BAUST Campus',
            'bus_number' => 'BAUST Bus #03 (Ashok Leyland)',
            'driver_name' => 'Md. Shamsul Haque',
            'driver_phone' => '+8801719-876543',
            'status' => 'On Time',
        ]);

        BusRoute::create([
            'route_code' => 'R-03',
            'route_name' => 'Parbatipur Railway Junction ↔ BAUST Campus',
            'description' => 'Daily transport for train commuters arriving at Parbatipur station.',
            'morning_schedule' => '8:00 AM',
            'afternoon_schedule' => '4:30 PM',
            'pickup_points' => 'Parbatipur Station Road, College Road, Market More, BAUST Gate',
            'bus_number' => 'BAUST Bus #05',
            'driver_name' => 'Md. Hamidur Rahman',
            'driver_phone' => '+8801815-112233',
            'status' => 'On Time',
        ]);

        // 8. Query Posts & Replies
        $query1 = QueryPost::create([
            'user_id' => $student->id,
            'title' => 'Which framework is best for CSE-3202 Web Engineering Lab Project?',
            'content' => 'Assalamu Alaikum everyone. For our 3rd year web engineering final lab project, should we choose Laravel or Node.js/React stack? What are the key evaluation criteria by honorable faculty?',
            'category' => 'academic',
            'department' => 'CSE',
            'level' => 'Level 3',
            'is_anonymous' => false,
            'status' => 'resolved',
            'replies_count' => 2,
            'likes_count' => 14,
        ]);

        QueryReply::create([
            'query_post_id' => $query1->id,
            'user_id' => $teacher->id,
            'content' => 'Both Laravel and Node.js are acceptable. However, Laravel is highly encouraged due to its structured MVC pattern, Eloquent ORM, robust session security, and ease of backend architecture demonstration.',
            'is_anonymous' => false,
            'is_accepted_answer' => true,
            'role_badge' => 'Teacher (CSE)',
        ]);

        QueryReply::create([
            'query_post_id' => $query1->id,
            'user_id' => $seniorStudent->id,
            'content' => 'I did my 3rd year project in Laravel last year. It helped me land my web engineering internship! Feel free to knock me if you need template code.',
            'is_anonymous' => false,
            'is_accepted_answer' => false,
            'role_badge' => 'Senior (Level 4)',
        ]);

        // 9. CGPA Records
        CgpaRecord::create([
            'user_id' => $student->id,
            'semester_name' => 'Level 1 Term 1',
            'gpa' => 3.75,
            'credits_earned' => 19.5,
        ]);

        CgpaRecord::create([
            'user_id' => $student->id,
            'semester_name' => 'Level 1 Term 2',
            'gpa' => 3.80,
            'credits_earned' => 20.0,
        ]);

        CgpaRecord::create([
            'user_id' => $student->id,
            'semester_name' => 'Level 2 Term 1',
            'gpa' => 3.85,
            'credits_earned' => 19.0,
        ]);

        $this->call([
            CafeMenuSeeder::class,
            LibraryCatalogSeeder::class,
        ]);
    }
}
