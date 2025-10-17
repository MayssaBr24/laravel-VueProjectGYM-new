<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Reservation;
use App\Models\Attendance;
use App\Models\Payment;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        return Inertia::render('Admin/Dashboard', [
            'weeklyCourses' => Course::with('coach')
                ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
                ->orderBy('start_time')
                ->get(),

            'latestReservations' => Reservation::with(['user', 'course'])
                ->latest()
                ->take(10)
                ->get(),

            'reservationStats' => [
                'total'    => Reservation::count(),
                'today'    => Reservation::whereDate('created_at', today())->count(),
                'upcoming' => Reservation::whereHas('course', fn($q) => $q->where('start_time', '>', now()))->count()
            ],
            'pendingAttendances' => Payment::with(['user', 'course'])
            ->where('attendance_verified', false)
            ->whereNull('paid_at')
            ->get(),
            'totalRevenue' => Payment::where('status', 'succeeded')
                         ->where('paid_at', '>=', now()->subDays(30))
                         ->sum('amount'),

    

            'courses' => Course::withCount('reservations')->get(),
            'payments' => Payment::with('user')
                ->latest()
                ->get()
                ->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'member_name' => optional($payment->user)->name ?? 'Membre inconnu',
                        'amount' => $payment->amount,
                        'date' => optional($payment->paid_at)->format('Y-m-d') ?? 'Date inconnue',
                        'status' => $payment->status,
                    ];
                }),
        ]);
        
            

       
        
    }
    
}
