<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Inertia\Inertia;


class ReservationController extends Controller
{
   public function index()
{
    $reservations = Reservation::with(['user', 'course'])
                    ->latest()
                    ->paginate(15);

    return Inertia::render('Admin/Reservations/Index', [
        'reservations' => $reservations
    ]);
}

}

