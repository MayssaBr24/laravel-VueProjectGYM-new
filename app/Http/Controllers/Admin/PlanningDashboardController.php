<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Reservation;
use Carbon\Carbon;
use Inertia\Inertia;

class PlanningDashboardController extends Controller
{
    public function index()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        return Inertia::render('Admin/Planning/Dashboard', [
            'weeklyCourses' => Course::with('coach')
                ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
                ->orderBy('start_time')
                ->get(),
            'latestReservations' => Reservation::with(['user', 'course'])
                ->latest()
                ->take(10)
                ->get(),
            'reservationStats' => [
                'total' => Reservation::count(),
                'today' => Reservation::whereDate('created_at', today())->count(),
                'upcoming' => Reservation::whereHas('course', fn($q) => $q->where('start_time', '>', now()))->count()
            ]
        ]);
    }
}

