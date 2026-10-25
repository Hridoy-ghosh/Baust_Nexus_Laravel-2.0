<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'query_post_id',
        'user_id',
        'content',
        'is_anonymous',
        'is_accepted_answer',
        'role_badge',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_accepted_answer' => 'boolean',
    ];

    public function queryPost()
    {
        return $this->belongsTo(QueryPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
