<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LibraryBorrow extends Model
{
    use HasFactory;

    public const LOAN_PERIOD_DAYS = 30;

    public const DAILY_FINE_TAKA = 5;

    protected $fillable = [
        'user_id',
        'library_book_id',
        'token_number',
        'status',
        'issue_date',
        'return_due_date',
        'returned_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(LibraryBook::class, 'library_book_id');
    }

    public function overdueDays(?Carbon $asOf = null): int
    {
        if (! $this->return_due_date) {
            return 0;
        }

        $dueDate = Carbon::parse($this->return_due_date)->startOfDay();
        $comparisonDate = $this->returned_at
            ? Carbon::parse($this->returned_at)->startOfDay()
            : ($asOf ?? today())->startOfDay();

        return max(0, (int) $dueDate->diffInDays($comparisonDate, false));
    }

    public function fineAmount(?Carbon $asOf = null): int
    {
        return $this->overdueDays($asOf) * self::DAILY_FINE_TAKA;
    }
}
