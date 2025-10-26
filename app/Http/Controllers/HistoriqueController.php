<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Models\Historique; 

class HistoriqueController extends Controller
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
        $historiques = Historique::
            orderBy('action_at', 'desc')
            ->with(['user', 'course'])
            ->get();

        return Inertia::render('Admin/HistoriqueAll', [
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
