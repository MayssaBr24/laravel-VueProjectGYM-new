<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Reservation;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Afficher les présences pour un cours spécifique.
     */
    public function show($courseId)
    {
        $course = Course::with(['coach', 'attendances.user'])->findOrFail($courseId);

        $attendances = Attendance::with('user')
            ->where('course_id', $courseId)
            ->orderByRaw("FIELD(status, 'present', 'reserved', 'absent', 'intrus')")
            ->get();

        return Inertia::render('Attendances/Details', [
            'course' => $course,
            'attendances' => $attendances,
        ]);
    }

    /**
     * Mettre à jour le statut manuellement.
     */
    public function updateStatus(Request $request, $attendanceId)
    {
        $request->validate([
            'status' => 'required|in:present,absent,reserved,intrus,unreserved',
        ]);

        $attendance = Attendance::findOrFail($attendanceId);
        $attendance->update([
            'status' => $request->status,
            'attended_at' => $request->status === 'present' ? now() : null,
        ]);

        return back()->with('success', 'Statut mis à jour avec succès.');
    }

    /**
     * API JSON pour les participants (rechargement dynamique).
     */
    public function participantsJson($courseId)
    {
        $attendances = Attendance::with('user')
            ->where('course_id', $courseId)
            ->orderByRaw("FIELD(status, 'present', 'reserved', 'absent', 'intrus')")
            ->get();

        return response()->json([
            'participants' => $attendances
        ]);
    }

    /**
     * Lorsqu'un utilisateur scanne le QR code.
     */
    public function markAttendance(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'coach_id'  => 'required|integer',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié.',
            ], 401);
        }

        $course = Course::find($request->course_id);
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Cours non trouvé.',
            ], 404);
        }

        // 🔹 Vérifier la réservation
        $reservation = Reservation::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        $attendance = Attendance::firstOrNew([
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);

        if ($reservation) {
            // ✅ Si réservé → passe à "present"
            $attendance->fill([
                'coach_id'      => $request->coach_id,
                'status'        => 'present',
                'checked_in_at' => now(),
                'attended_at'   => now(),
            ])->save();

            $reservation->update([
                'status'        => 'confirmed',
                'checked_in_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'status'  => 'present',
                'message' => 'Présence confirmée ✅',
            ]);
        }

        // ❌ Sinon → intrus
        $attendance->fill([
            'coach_id'      => $request->coach_id,
            'status'        => 'intrus',
            'checked_in_at' => now(),
        ])->save();

        return response()->json([
            'success' => true,
            'status'  => 'intrus',
            'message' => 'Présence enregistrée comme intrus ❌',
        ]);
    }

    /**
     * Marquer les absents automatiquement à la fin du cours.
     */
    public function markAbsents($courseId)
    {
        Attendance::where('course_id', $courseId)
            ->where('status', 'reserved')
            ->update(['status' => 'absent']);

        return back()->with('success', 'Absents marqués automatiquement.');
    }
}
