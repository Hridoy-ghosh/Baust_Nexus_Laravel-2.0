<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // student, teacher, admin, cafe_admin, library_admin, guest
        'student_id',
        'teacher_id',
        'department',
        'level',
        'term',
        'designation',
        'phone',
        'gender',
        'date_of_birth',
        'blood_group',
        'emergency_contact',
        'address',
        'avatar',
        'completed_credits',
        'cgpa',
        'is_approved',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'password' => 'hashed',
            'is_approved' => 'boolean',
            'completed_credits' => 'double',
            'cgpa' => 'double',
        ];
    }

    public function academicResources()
    {
        return $this->hasMany(AcademicResource::class, 'uploaded_by');
    }

    public function notices()
    {
        return $this->hasMany(Notice::class, 'published_by');
    }

    public function queryPosts()
    {
        return $this->hasMany(QueryPost::class);
    }

    public function queryReplies()
    {
        return $this->hasMany(QueryReply::class);
    }

    public function libraryBorrows()
    {
        return $this->hasMany(LibraryBorrow::class);
    }

    public function cafeOrders()
    {
        return $this->hasMany(CafeOrder::class);
    }

    public function cgpaRecords()
    {
        return $this->hasMany(CgpaRecord::class);
    }

    // Role Helper Methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCafeAdmin()
    {
        return $this->role === 'cafe_admin' || $this->role === 'admin';
    }

    public function isLibraryAdmin()
    {
        return $this->role === 'library_admin' || $this->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isGuest()
    {
        return $this->role === 'guest';
    }

    // Permission Rule: Students CANNOT upload resources; only Teacher and Main Admin can!
    public function canUploadResource()
    {
        return $this->isTeacher() || $this->isAdmin();
    }
}
