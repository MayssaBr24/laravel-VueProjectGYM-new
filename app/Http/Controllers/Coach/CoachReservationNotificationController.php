<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Historique;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
class CoachReservationNotificationController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::id();
            
            $notifications = Historique::
               where('is_read', false)
                ->orderBy('action_at', 'desc')
                ->take(3)
                ->with(['user', 'course'])
                ->get();

            return response()->json($notifications);
            
        } catch (\Exception $e) {
            \Log::error('Erreur historique: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    public function markAsRead($id)
    {
        $notification = Historique::findOrFail($id);

       

        $notification->is_read = true;
        $notification->save();

        return response()->json(['success' => true]);
    }

    public function all()
    {
        $userId = Auth::id();
        $historiques = Historique::whereIn('action', ['reservation', 'delete_reservation']) 

            ->orderBy('action_at', 'desc')
            ->with(['user', 'course'])
            ->get();

        return Inertia::render('Coach/HistoriqueAll', [
            'historiques' => $historiques,
            'filters' => [
                'all' => 'Toutes',
                'unread' => 'Non lues', 
                'add' => 'Ajouts',
                'update' => 'Modifications',
                'delete' => 'Suppressions'
            ]
        ]);
    }
}