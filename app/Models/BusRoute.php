<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_name',
        'route_code',
        'description',
        'morning_schedule',
        'afternoon_schedule',
        'pickup_points',
        'bus_number',
        'driver_name',
        'driver_phone',
        'status',
    ];
}
