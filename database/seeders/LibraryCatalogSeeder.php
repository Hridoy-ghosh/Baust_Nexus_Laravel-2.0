<?php

namespace Database\Seeders;

use App\Models\LibraryBook;
use Illuminate\Database\Seeder;

class LibraryCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            ['Computer Networks: A Systems Approach', 'Larry Peterson, Bruce Davie', 'Computer Science', 'CSE'],
            ['Computer Networking: A Top-Down Approach', 'James Kurose, Keith Ross', 'Computer Science', 'CSE'],
            ['Database System Concepts', 'Abraham Silberschatz, Henry Korth, S. Sudarshan', 'Computer Science', 'CSE'],
            ['Fundamentals of Database Systems', 'Ramez Elmasri, Shamkant Navathe', 'Computer Science', 'CSE'],
            ['Software Engineering', 'Ian Sommerville', 'Computer Science', 'CSE'],
            ['Clean Code', 'Robert C. Martin', 'Computer Science', 'CSE'],
            ['Artificial Intelligence: A Modern Approach', 'Stuart Russell, Peter Norvig', 'Computer Science', 'CSE'],
            ['Pattern Recognition and Machine Learning', 'Christopher M. Bishop', 'Computer Science', 'CSE'],
            ['Deep Learning', 'Ian Goodfellow, Yoshua Bengio, Aaron Courville', 'Computer Science', 'CSE'],
            ['Computer Organization and Design', 'David A. Patterson, John L. Hennessy', 'Computer Science', 'CSE'],
            ['Modern Operating Systems', 'Andrew S. Tanenbaum, Herbert Bos', 'Computer Science', 'CSE'],
            ['The C Programming Language', 'Brian W. Kernighan, Dennis M. Ritchie', 'Computer Science', 'CSE'],
            ['Programming in C++', 'Bjarne Stroustrup', 'Computer Science', 'CSE'],
            ['Introduction to the Theory of Computation', 'Michael Sipser', 'Computer Science', 'CSE'],
            ['Compilers: Principles, Techniques, and Tools', 'Alfred V. Aho et al.', 'Computer Science', 'CSE'],
            ['Web Development with HTML, CSS, JavaScript', 'Jon Duckett', 'Computer Science', 'CSE'],
            ['Cryptography and Network Security', 'William Stallings', 'Computer Science', 'CSE'],
            ['Discrete Mathematics and Its Applications', 'Kenneth H. Rosen', 'Computer Science', 'CSE'],
            ['Digital Design', 'M. Morris Mano, Michael D. Ciletti', 'Electrical Engineering', 'EEE'],
            ['Microelectronic Circuits', 'Adel S. Sedra, Kenneth C. Smith', 'Electrical Engineering', 'EEE'],
            ['Electronic Devices and Circuit Theory', 'Robert L. Boylestad, Louis Nashelsky', 'Electrical Engineering', 'EEE'],
            ['Electric Machinery Fundamentals', 'Stephen J. Chapman', 'Electrical Engineering', 'EEE'],
            ['Power System Analysis', 'John J. Grainger, William D. Stevenson', 'Electrical Engineering', 'EEE'],
            ['Control Systems Engineering', 'Norman S. Nise', 'Electrical Engineering', 'EEE'],
            ['Signals and Systems', 'Alan V. Oppenheim, Alan S. Willsky', 'Electrical Engineering', 'EEE'],
            ['Engineering Mechanics: Statics', 'Russell C. Hibbeler', 'Mechanical Engineering', 'ME'],
            ['Thermodynamics: An Engineering Approach', 'Yunus A. Cengel, Michael A. Boles', 'Mechanical Engineering', 'ME'],
            ['Fluid Mechanics', 'Frank M. White', 'Mechanical Engineering', 'ME'],
            ["Shigley's Mechanical Engineering Design", 'Richard G. Budynas, J. Keith Nisbett', 'Mechanical Engineering', 'ME'],
            ['Manufacturing Engineering and Technology', 'Serope Kalpakjian, Steven Schmid', 'Mechanical Engineering', 'ME'],
            ['Structural Analysis', 'Russell C. Hibbeler', 'Civil Engineering', 'CE'],
            ['Design of Concrete Structures', 'Arthur H. Nilson, David Darwin, Charles W. Dolan', 'Civil Engineering', 'CE'],
            ['Principles of Geotechnical Engineering', 'Braja M. Das', 'Civil Engineering', 'CE'],
            ['Engineering Mathematics', 'K. A. Stroud, Dexter J. Booth', 'Basic Science', 'All'],
            ['University Physics', 'Hugh D. Young, Roger A. Freedman', 'Basic Science', 'All'],
        ];

        foreach ($books as [$title, $author, $category, $department]) {
            LibraryBook::firstOrCreate(
                ['title' => $title],
                [
                    'author' => $author,
                    'category' => $category,
                    'department' => $department,
                    'total_copies' => 3,
                    'available_copies' => 3,
                    'shelf_location' => 'BAUST Library',
                    'cover_image' => '/images/library.jpg',
                    'description' => "Catalog listing for {$title}.",
                ]
            );
        }
    }
}
