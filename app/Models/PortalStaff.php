<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalStaff extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit',
        'name',
        'position',
        'phone',
        'duty_hours',
        'created_by',
    ];
}
