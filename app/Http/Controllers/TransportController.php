<?php

namespace App\Http\Controllers;

use App\Models\BusRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportController extends Controller
{
    public function index(Request $request)
    {
        $routes = BusRoute::all();
        return view('transport.index', compact('routes'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'route_code' => 'required|string',
            'route_name' => 'required|string',
            'description' => 'nullable|string',
            'morning_schedule' => 'required|string',
            'afternoon_schedule' => 'required|string',
            'pickup_points' => 'required|string',
            'bus_number' => 'nullable|string',
            'driver_name' => 'nullable|string',
            'driver_phone' => 'nullable|string',
            'status' => 'required|string',
        ]);

        BusRoute::create($validated);

        return back()->with('success', 'Bus route added successfully!');
    }
}
