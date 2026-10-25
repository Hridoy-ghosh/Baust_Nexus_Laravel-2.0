<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'resource_type',
        'department',
        'level',
        'term',
        'course_code',
        'course_title',
        'file_path',
        'external_link',
        'uploaded_by',
        'downloads_count',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
