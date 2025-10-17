<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class AdminQrController extends Controller
{
    public function showScanPage()
    {
        return inertia('Admin/AdminScanQrPage', [
            'permissions' => [
                'camera' => $this->checkCameraPermissions()
            ]
        ]);
    }

    public function verifyAttendance(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        try {
            $payment = Payment::where('user_id', $validated['user_id'])
                ->where('course_id', $validated['course_id'])
                ->firstOrFail();

            if ($payment->attendance_verified) {
                return response()->json([
                    'message' => 'Présence déjà confirmée',
                    'already_verified' => true
                ], 400);
            }

            $payment->update([
                'attendance_verified' => true,
                'verified_at' => now()
            ]);

            return response()->json([
                'message' => 'Présence confirmée avec succès',
                'payment' => $payment
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur vérification présence: " . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la vérification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function checkCameraPermissions()
    {
        // Logique pour vérifier les permissions (peut nécessiter du JS côté client)
        return true;
    }
}