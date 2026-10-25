<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'target_department',
        'target_level',
        'is_pinned',
        'is_urgent',
        'published_by',
        'file_attachment',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_urgent' => 'boolean',
    ];

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
