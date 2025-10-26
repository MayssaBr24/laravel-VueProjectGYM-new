<?php

namespace App\Http\Controllers\Api;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $courses = Course::withCount('reservations')->get();
        
        $events = $courses->map(function ($course) {
            $spotsLeft = $course->max_participants - $course->reservations_count;

            return [
                'id' => $course->id,
                'title' => $course->name . ' (' . $course->coach_name . ')',
                'start' => $course->start_time,
                'end' => $course->end_time,
                'extendedProps' => [
                    'description' => $course->description,
                    'coach' => $course->coach_name,
                    'status' => $spotsLeft <= 0 ? 'full' : 'spots left',
                    'spotsLeft' => $spotsLeft,
                    'maxParticipants' => $course->max_participants,
                ],
            ];
        });

        // Calcul des statistiques
        $stats = $this->calculateStats($courses);

        return Inertia::render('Calendrier/index', [
            'initialEvents' => $events,
            'initialCoaches' => $courses->pluck('coach_name')->unique()->values(),
            'stats' => $stats
        ]);
    }

    private function calculateStats($courses)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Cours ce mois-ci
        $sessionsThisMonth = $courses->filter(function ($course) use ($startOfMonth, $endOfMonth) {
            $courseDate = Carbon::parse($course->start_time);
            return $courseDate->between($startOfMonth, $endOfMonth);
        })->count();

        // Cours disponibles (avec places restantes)
        $availableCourses = $courses->filter(function ($course) {
            return ($course->max_participants - $course->reservations_count) > 0;
        })->count();

        // Nombre de coachs uniques
        $coachesCount = $courses->pluck('coach_name')->unique()->count();

        // Réservations de l'utilisateur connecté CE MOIS
        $userReservations = 0;
        if (Auth::check()) {
            $userReservations = Reservation::where('user_id', Auth::id())
                ->whereHas('course', function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereBetween('start_time', [$startOfMonth, $endOfMonth]);
                })
                ->count();
        }

        // Taux de remplissage moyen des cours
        $averageOccupancy = 0;
        if ($courses->count() > 0) {
            $totalOccupancy = $courses->sum(function ($course) {
                return ($course->reservations_count / $course->max_participants) * 100;
            });
            $averageOccupancy = round($totalOccupancy / $courses->count());
        }

        // OBJECTIF DE FRÉQUENCE - 8 séances par mois
        $targetMonthlySessions = 8; // 2 séances par semaine × 4 semaines
        $goalsCompleted = min($userReservations, $targetMonthlySessions);

        return [
            'sessionsThisMonth' => $sessionsThisMonth,
            'availableCourses' => $availableCourses,
            'coachesCount' => $coachesCount,
            'userReservations' => $userReservations,
            'attendanceRate' => $averageOccupancy,
            'goalsCompleted' => $goalsCompleted,
            'targetMonthlySessions' => $targetMonthlySessions // Pour l'affichage X/8
        ];
    }
}