<?php

namespace App\Http\Controllers\Coach;
use App\Http\Controllers\Controller;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Reservation;
use App\Models\User;

class CourseDetailController extends Controller
{
    public function show(Course $course)
    {
       

        // Charger les réservations avec les utilisateurs
        $course->load(['reservations.user']);

        // Statistiques du cours
        $totalReservations = $course->reservations->count();
        $fillRate = $course->max_participants > 0 ? round(($totalReservations / $course->max_participants) * 100) : 0;
        
        // Statut du cours
        $now = now();
        $status = 'upcoming';
        if ($now >= $course->start_time && $now <= $course->end_time) {
            $status = 'active';
        } elseif ($now > $course->end_time) {
            $status = 'completed';
        }

        // Statistiques par période
        $reservationsToday = $course->reservations()->whereDate('created_at', today())->count();
        $reservationsThisWeek = $course->reservations()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $reservationsThisMonth = $course->reservations()->whereMonth('created_at', now()->month)->count();

        // Toutes les réservations formatées
        $reservations = $course->reservations()
            ->with('user')
            ->latest()
            ->get()
            ->map(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'user' => [
                        'id' => $reservation->user->id,
                        'name' => $reservation->user->name,
                        'email' => $reservation->user->email,
                        'avatar' => $reservation->user->profile_photo_url ?? null,
                        'phone' => $reservation->user->phone ?? null,
                    ],
                    'reserved_at' => $reservation->created_at,
                    'status' => $reservation->status ?? 'confirmed',
                    'attendance_status' => $reservation->attendance_status ?? 'pending',
                    'notes' => $reservation->notes ?? null,
                ];
            });

        // Données du cours
        $courseData = [
            'id' => $course->id,
            'name' => $course->name,
            'description' => $course->description,
            'start_time' => $course->start_time,
            'end_time' => $course->end_time,
            'max_participants' => $course->max_participants,
            'current_participants' => $totalReservations,
            'remaining_slots' => $course->max_participants - $totalReservations,
            'price' => $course->price ?? 0,
            'location' => $course->location ?? null,
            'category' => $course->category ?? null,
            'level' => $course->level ?? null,
            'created_at' => $course->created_at,
            'status' => $status,
            'fill_rate' => $fillRate,
        ];
        $presentIds = \DB::table('scans')
        ->where('course_id', $course->id)
        ->pluck('user_id')
        ->toArray();


        // Statistiques
        $stats = [
            'total_reservations' => $totalReservations,
            'fill_rate' => $fillRate,
            'remaining_slots' => $course->max_participants - $totalReservations,
            'reservations_today' => $reservationsToday,
            'reservations_this_week' => $reservationsThisWeek,
            'reservations_this_month' => $reservationsThisMonth,
            'revenue' => $totalReservations * ($course->price ?? 0),
        ];
         // Intrus (scannés mais pas réservés)
         $intrus = \App\Models\User::whereIn('id', $presentIds)
        ->whereDoesntHave('reservations', function($q) use ($course) {
            $q->where('course_id', $course->id);
        })
        ->get()
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->profile_photo_url ?? null,
                'status' => 'intrus',
            ];
        });

        return Inertia::render('Coach/Courses/Details', [
            'course' => $courseData,
            'reservations' => $reservations,
            'stats' => $stats,
            'intrus' => $intrus,
        ]);
    }

    public function exportParticipants(Course $course)
    {
        // Vérifier que le cours appartient au coach connecté
        if ($course->coach_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }

        $course->load(['reservations.user']);

        $csvData = [];
        $csvData[] = ['Nom', 'Email', 'Téléphone', 'Date d\'inscription', 'Statut', 'Présence'];

        foreach ($course->reservations as $reservation) {
            $csvData[] = [
                $reservation->user->name,
                $reservation->user->email,
                $reservation->user->phone ?? 'Non renseigné',
                $reservation->created_at->format('d/m/Y H:i'),
                $reservation->status ?? 'Confirmé',
                $reservation->attendance_status ?? 'En attente'
            ];
        }

        $filename = 'participants_' . str_replace(' ', '_', $course->name) . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function attendanceList($course_id)
    {
        // IDs des utilisateurs ayant scanné
        $presentIds = \DB::table('scans')
            ->where('course_id', $course_id)
            ->pluck('user_id')
            ->toArray();

        // Réservations présentes (scannées)
        $present = Reservation::with('user')
            ->where('course_id', $course_id)
            ->whereIn('user_id', $presentIds)
            ->get();

        // Réservations non scannées
        $pending = Reservation::with('user')
            ->where('course_id', $course_id)
            ->whereNotIn('user_id', $presentIds)
            ->get();

        // Intrus : scannés mais pas réservés
        $intrus = User::whereIn('id', $presentIds)
            ->whereDoesntHave('reservations', function($q) use ($course_id) {
                $q->where('course_id', $course_id);
            })
            ->get();

        // Optionnel : trier par nom
        $present = $present->sortBy('user.name')->values();
        $pending = $pending->sortBy('user.name')->values();
        $intrus  = $intrus->sortBy('name')->values();

        return response()->json([
            'present' => $present,
            'pending' => $pending,
            'intrus'  => $intrus,
        ]);
    }
}
