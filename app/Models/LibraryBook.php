<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'category',
        'department',
        'total_copies',
        'available_copies',
        'shelf_location',
        'cover_image',
        'description',
    ];

    public function borrows()
    {
        return $this->hasMany(LibraryBorrow::class);
    }
}
