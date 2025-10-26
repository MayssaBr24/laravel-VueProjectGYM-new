<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Support\Str; 
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservations = Reservation::with(['user', 'course'])
            ->when($request->filled('coach'), function ($query) use ($request) {
                $query->whereHas('course', function ($q) use ($request) {
                    $q->where('coach_name', 'like', '%' . $request->coach . '%');
                });
            })
            ->latest()
            ->paginate(15);

        // Récupérer la liste des coachs uniques pour le filtre
        $coaches = Course::select('coach_name')
            ->whereNotNull('coach_name')
            ->distinct()
            ->pluck('coach_name')
            ->filter()
            ->values();

        return Inertia::render('Admin/Reservations/Index', [
            'reservations' => $reservations,
            'coaches' => $coaches,
            'filters' => $request->only(['coach'])
        ]);
    }

    public function exportPdf(Request $request)
    {
        $reservations = Reservation::with(['user', 'course'])
            ->when($request->filled('coach'), function ($query) use ($request) {
                $query->whereHas('course', function ($q) use ($request) {
                    $q->where('coach_name', 'like', '%' . $request->coach . '%');
                });
            })
            ->latest()
            ->get();

        $pdf = Pdf::loadView('pdf.reservations', [
            'reservations' => $reservations,
            'coachFilter' => $request->coach
        ]);

        $filename = $request->coach 
            ? 'reservations-coach-' . Str::slug($request->coach) . '-' . now()->format('d-m-Y') . '.pdf'
            : 'reservations-' . now()->format('d-m-Y') . '.pdf';

        return $pdf->download($filename);
    }
}