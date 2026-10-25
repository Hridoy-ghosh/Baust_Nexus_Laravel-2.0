<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CgpaRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'semester_name',
        'gpa',
        'credits_earned',
        'courses_data',
    ];

    protected $casts = [
        'courses_data' => 'array',
        'gpa' => 'double',
        'credits_earned' => 'double',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
