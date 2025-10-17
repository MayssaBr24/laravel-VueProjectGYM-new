<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Course;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class ClientDashboardController extends Controller
{
    public function clientDashboard()
{
    $user = Auth::user();

    $reservations = Reservation::with(['course', 'payment']) 
        ->where('user_id', $user->id)
        ->where('status', 'confirmed')
        ->get();

    $validReservations = $reservations->filter(fn($r) => $r->course);

    // Calcul des heures par mois
    $hoursByMonth = $validReservations
        ->groupBy(function($reservation) {
            return Carbon::parse($reservation->course->start_time)->format('Y-m');
        })
        ->map(function($monthReservations) {
            return $monthReservations->sum(function($r) {
                return Carbon::parse($r->course->start_time)->diffInHours(
                    Carbon::parse($r->course->end_time)
                );
            });
        })
        ->sortKeysDesc()
        ->take(6)
        ->mapWithKeys(function($hours, $month) {
            return [
                Carbon::createFromFormat('Y-m', $month)->format('M Y') => $hours
            ];
        });

    $nextCourseReservation = $validReservations
        ->filter(fn($r) => $r->course->start_time > now())
        ->sortBy('course.start_time')
         ->whereNull('paid_at')
        ->first();

    $stats = [
        'reservations_count' => $validReservations->count(),
        'total_hours' => $validReservations->sum(fn($r) => Carbon::parse($r->course->end_time)->diffInHours($r->course->start_time)),
        'weekly_increase' => $validReservations->where('created_at', '>=', now()->startOfWeek())->count(),
        'hours_by_month' => $hoursByMonth,
        'current_month_hours' => $hoursByMonth->first() ?? 0,
        'hours_by_month' => $hoursByMonth,

    ];



        $recentReservations = $validReservations
            ->where('created_at', '>=', now()->subDays(7))
            ->sortByDesc('created_at')
            ->take(5)
            ->map(fn($r) => [
                'id' => $r->id,
                'course' => [
                    'name' => $r->course->name,
                    'start_time' => $r->course->start_time,
                    'end_time' => $r->course->end_time,
                ]
            ])->values();

            

         $pendingPayments = \App\Models\Payment::with('course')
        ->where('user_id', $user->id)
        ->where('attendance_verified', true)
        ->whereNull('paid_at')
        ->get();
        $notifications = Auth::user()->notifications;

    return Inertia::render('Client/Dashboard', [
        'stats' => $stats,
        'nextCourse' => $nextCourseReservation ? [
            'id' => $nextCourseReservation->id,
            'name' => $nextCourseReservation->course->name,
            'description' => $nextCourseReservation->course->description,
            'start_time' => $nextCourseReservation->course->start_time,
            'end_time' => $nextCourseReservation->course->end_time,
            'coach_name' => $nextCourseReservation->course->coach_name,
            'location' => $nextCourseReservation->course->location ?? 'non spécifiée',
            'max_participants' => $nextCourseReservation->course->max_participants,
            'current_participants' => $nextCourseReservation->course->reservations()->count(),
            'paid_at' => $nextCourseReservation->payment->paid_at ?? null, 
            'payment_id' => $nextCourseReservation->payment_id ?? null,
            'user_id' => $user->id, 
            'course_id' => $nextCourseReservation->course->id, 
        ] : null,
        'recentReservations' => $recentReservations,
         'notifications' => $notifications,
        

        //  Lien vers la carte des paiements
        'pendingPayments' => $pendingPayments,
    ]);

     

    
}}
