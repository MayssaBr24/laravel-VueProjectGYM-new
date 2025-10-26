<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Historique;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = auth()->user()->reservations()
            ->where('status', 'confirmed')
            ->with('course')
            ->orderBy('reservation_date', 'desc')
            ->paginate(10);

        return Inertia::render('Reservations/MyReservations', [
            'reservations' => $reservations,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::withCount('reservations')->findOrFail($request->course_id);
        $userId = Auth::id();

        // 1️⃣ Vérifie la capacité
        if ($course->reservations_count >= $course->max_participants) {
            return back()->with('error', 'Ce cours est complet.');
        }

        // 2️⃣ Vérifie la double réservation
        if (Reservation::where('user_id', $userId)->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'Vous avez déjà réservé ce cours.');
        }

        // 3️⃣ Crée la réservation
        $reservation = Reservation::create([
            'user_id' => $userId,
            'course_id' => $course->id,
            'reservation_date' => now(),
            'status' => 'confirmed',
            'is_read' => false,
        ]);

       
        Historique::create([
    'action' => 'reservation',
    'user_role' => Auth::user()->role, // ex: 'user'
    'for_role' => 'coach', // c’est pour le coach
    'user_id' => Auth::id(),
    'course_id' => $course->id,

    'description' => "Réservation du cours '{$course->name}' par " . Auth::user()->name,
    'action_at' => now(),
]);
        

        // 4️⃣ Crée ou met à jour la présence
        Attendance::updateOrCreate(
            [
                'user_id' => $userId,
                'course_id' => $course->id,
            ],
            [
                'coach_id' => $course->coach_id ?? $request->coach_id ?? null,
                'status' => 'reserved', // ✅ statut initial correct
            ]
        );

        
        return back()->with('success', 'Réservation réussie ✅');
    }

    public function destroy($id)
{
    $reservation = Reservation::findOrFail($id);
    $course_id = $reservation->course_id; // récupère l'ID du cours

    $reservation->delete();

    // Enregistrement historique
    Historique::create([
        'action' => 'delete_reservation',
        'user_role' => Auth::user()->role,
        'user_id' => Auth::id(),
        'course_id' => $course_id, // <-- ici
        'description' => "Annulation de la réservation du cours '{$reservation->course->name}'",
        'action_at' => now(),
    ]);

    return back()->with('success', 'Réservation annulée avec succès.');
}




   
}
