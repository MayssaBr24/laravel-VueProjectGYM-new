<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Http\Request;
class ClientCalendarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Cours disponibles avec places restantes
        $courses = Course::withCount('reservations')
            ->where('start_time', '>=', now())
            ->get()
            ->map(function ($course) {
                $spotsLeft = $course->max_participants - $course->reservations_count;
                
                return [
                    'id' => $course->id,
                    'title' => $course->name . ' (' . $course->coach_name . ')',
                    'start' => $course->start_time,
                    'end' => $course->end_time,
                    'extendedProps' => [
                        'description' => $course->description,
                        'coach' => $course->coach_name,
                        'spotsLeft' => $spotsLeft,
                        'maxParticipants' => $course->max_participants,
                        'status' => $spotsLeft > 0 ? 'available' : 'full',
                        'price' => $course->price,
                        'location' => $course->location,
                    ],
                    'backgroundColor' => $spotsLeft > 0 ? 'rgba(59, 130, 246, 0.2)' : 'rgba(239, 68, 68, 0.2)',
                    'borderColor' => $spotsLeft > 0 ? 'rgba(59, 130, 246, 0.5)' : 'rgba(239, 68, 68, 0.5)',
                    'textColor' => $spotsLeft > 0 ? '#93c5fd' : '#fca5a5'
                ];
            });

        // Réservations de l'utilisateur
        $userReservations = Reservation::with('course')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($reservation) {
                return [
                    'id' => 'reservation_' . $reservation->id,
                    'title' => '✓ ' . $reservation->course->name,
                    'start' => $reservation->course->start_time,
                    'end' => $reservation->course->end_time,
                    'extendedProps' => [
                        'type' => 'reservation',
                        'reservation_id' => $reservation->id,
                        'status' => $reservation->status,
                        'coach' => $reservation->course->coach_name,
                        'description' => $reservation->course->description,
                    ],
                    'backgroundColor' => 'rgba(34, 197, 94, 0.3)',
                    'borderColor' => 'rgba(34, 197, 94, 0.6)',
                    'textColor' => '#86efac'
                ];
            });

        // Fusionner cours disponibles et réservations
        $allEvents = $courses->merge($userReservations);

        // Statistiques pour le client
        $stats = $this->calculateClientStats($user);

        return Inertia::render('Client/Calendar', [
            'initialEvents' => $allEvents,
            'initialCoaches' => $courses->pluck('extendedProps.coach')->unique()->values(),
            'stats' => $stats,
            'userReservations' => $userReservations
        ]);
    }

    private function calculateClientStats($user)
{
    $now = Carbon::now();
    $startOfMonth = $now->copy()->startOfMonth();
    $endOfMonth = $now->copy()->endOfMonth();

    // Réservations ce mois
    $userReservations = Reservation::where('user_id', $user->id)
        ->whereHas('course', function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('start_time', [$startOfMonth, $endOfMonth]);
        })->count();

    // Objectif mensuel
    $targetMonthlySessions = 8;
    $goalsCompleted = min($userReservations, $targetMonthlySessions);

    // Taux d'assiduité SIMPLIFIÉ : pourcentage de l'objectif
    $attendanceRate = round(($userReservations / $targetMonthlySessions) * 100);

    // Prochaine séance
    $nextSession = Reservation::with('course')
        ->where('user_id', $user->id)
        ->whereHas('course', function ($query) {
            $query->where('start_time', '>', now());
        })
        ->join('courses', 'reservations.course_id', '=', 'courses.id')
        ->orderBy('courses.start_time', 'asc')
        ->select('reservations.*')
        ->first();

    $nextSessionText = $nextSession ? 
        Carbon::parse($nextSession->course->start_time)->diffForHumans() : 
        'Aucune';

    return [
        'sessionsThisMonth' => $userReservations,
        'attendanceRate' => $attendanceRate,
        'goalsCompleted' => $goalsCompleted,
        'targetMonthlySessions' => $targetMonthlySessions,
        'nextSession' => $nextSessionText,
        'totalReservations' => Reservation::where('user_id', $user->id)->count()
    ];
}
    public function reserveCourse(Request $request, $courseId)
    {
        $user = Auth::user();
        
        $course = Course::findOrFail($courseId);
        
        // Vérifier les places disponibles
        $spotsLeft = $course->max_participants - $course->reservations()->count();
        
        if ($spotsLeft <= 0) {
            return back()->with('error', 'Désolé, plus de places disponibles pour ce cours.');
        }

        // Vérifier si déjà réservé
        $existingReservation = Reservation::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();

        if ($existingReservation) {
            return back()->with('error', 'Vous avez déjà réservé ce cours.');
        }

        // Créer la réservation
        Reservation::create([
            'user_id' => $user->id,
            'course_id' => $courseId,
            'status' => 'confirmed',
            'reserved_at' => now()
        ]);

        return back()->with('success', 'Cours réservé avec succès!');
    }

   public function cancelReservation(Request $request, $reservationId)
{
    $user = Auth::user();
    
    $reservation = Reservation::where('user_id', $user->id)
        ->where('id', $reservationId)
        ->firstOrFail();

    // Vérifier si on peut annuler (au moins 2h avant)
    $courseStart = Carbon::parse($reservation->course->start_time);
    if (now()->diffInHours($courseStart) < 2) {
        return redirect()->back()->with('error', 'Impossible d\'annuler moins de 2 heures avant le cours.');
    }

    $reservation->delete();

    return redirect()->back()->with('success', 'Réservation annulée avec succès.');
}

    
}