<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Attendance;

class QrScannerController extends Controller
{
    public function scan(Request $request)
    {
        $userId = $request->input('user_id');
        $courseId = $request->input('course_id');

        // Vérifier si l’utilisateur existe
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Utilisateur introuvable'], 404);
        }

        // Vérifier si une réservation existe
        $reservation = Reservation::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if ($reservation) {
            $reservation->attendance_status = 'present';
            $reservation->save();

            Attendance::updateOrCreate(
                ['user_id' => $userId, 'course_id' => $courseId],
                ['status' => 'present']
            );

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur marqué comme présent',
                'status'  => 'present'
            ]);
        } else {
            Attendance::updateOrCreate(
                ['user_id' => $userId, 'course_id' => $courseId],
                ['status' => 'intrus']
            );

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur scanné mais sans réservation',
                'status'  => 'intrus'
            ]);
        }
    }
    public function showScannerPage()
{
    return inertia('Client/ScanQr'); // si tu utilises Inertia/Vue
    // ou
    // return view('client.scan-qr'); // si tu utilises Blade
}


   
}
