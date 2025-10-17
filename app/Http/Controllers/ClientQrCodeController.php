<?php

namespace App\Http\Controllers;
use App\Models\Reservation;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Payment;


class ClientQrCodeController extends Controller
{
  public function show(Request $request)
{
    $userId = auth()->id();
    $courseId = $request->query('course_id');

    if (!$courseId) {
        abort(404, 'Paramètre course_id manquant');
    }

    // Vérifiez que l'utilisateur a une réservation confirmée pour ce cours
    $reservation = Reservation::with('course')
        ->where('user_id', $userId)
        ->whereHas('course', function($query) use ($courseId) {
            $query->where('id', $courseId)
                  ->where('start_time', '>', now());
        })
        ->where('status', 'confirmed')
        ->first();

   

    return inertia('Client/ClientQrCodePage', [
        'courseId' => $courseId,
        'userId' => $userId,
    ]);
    
}


}
