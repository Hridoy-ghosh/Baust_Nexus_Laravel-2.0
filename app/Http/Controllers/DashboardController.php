<?php

namespace App\Http\Controllers;

use App\Models\AcademicResource;
use App\Models\BusRoute;
use App\Models\CafeItem;
use App\Models\LibraryBook;
use App\Models\Notice;
use App\Models\QueryPost;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'cafe_admin') {
            return redirect()->route('cafe.index');
        }

        if ($user->role === 'library_admin') {
            return redirect()->route('library.index');
        }

        // Statistics
        $stats = [
            'total_resources' => AcademicResource::count(),
            'total_notices' => Notice::count(),
            'available_books' => LibraryBook::where('available_copies', '>', 0)->count(),
            'active_bus_routes' => BusRoute::count(),
            'cafe_items' => CafeItem::where('is_available', true)->count(),
            'open_queries' => QueryPost::where('status', 'open')->count(),
            'total_students' => User::where('role', 'student')->count(),
        ];

        // Latest pinned & urgent notices
        $urgentNotices = Notice::where('is_pinned', true)
            ->orWhere('is_urgent', true)
            ->latest()
            ->take(3)
            ->get();

        $recentNotices = Notice::latest()->take(5)->get();

        // Featured resources
        $featuredResources = AcademicResource::latest()->take(4)->get();

        // Featured cafe special offers
        $specialOffers = CafeItem::where('is_special_offer', true)->where('is_available', true)->get();

        // Recent Q&A queries
        $recentQueries = QueryPost::with('user', 'replies')->latest()->take(4)->get();

        // Bus schedules quick glance
        $busRoutes = BusRoute::take(3)->get();

        return view('dashboard', compact(
            'stats',
            'urgentNotices',
            'recentNotices',
            'featuredResources',
            'specialOffers',
            'recentQueries',
            'busRoutes'
        ));
    }
}
