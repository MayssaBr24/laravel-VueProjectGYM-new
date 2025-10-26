<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class CoachCalendarController extends Controller
{
    public function index()
    {
        $coach = Auth::user();
        
        // Cours du coach
        $courses = Course::withCount('reservations')
            ->where('coach_name', $coach->name) // Adaptez selon votre structure
            ->get()
            ->map(function ($course) {
                $spotsLeft = $course->max_participants - $course->reservations_count;
                $occupancyRate = $course->max_participants > 0 ? 
                    round(($course->reservations_count / $course->max_participants) * 100) : 0;

                return [
                    'id' => $course->id,
                    'title' => $course->name . ' (' . $spotsLeft . ' places)',
                    'start' => $course->start_time,
                    'end' => $course->end_time,
                    'extendedProps' => [
                        'description' => $course->description,
                        'coach' => $course->coach_name,
                        'spotsLeft' => $spotsLeft,
                        'maxParticipants' => $course->max_participants,
                        'reservationsCount' => $course->reservations_count,
                        'occupancyRate' => $occupancyRate,
                        'status' => $spotsLeft > 0 ? 'available' : 'full',
                        'price' => $course->price,
                        'location' => $course->location,
                    ],
                    'backgroundColor' => $occupancyRate >= 80 ? 'rgba(34, 197, 94, 0.2)' : 
                                       ($occupancyRate >= 50 ? 'rgba(59, 130, 246, 0.2)' : 'rgba(99, 102, 241, 0.2)'),
                    'borderColor' => $occupancyRate >= 80 ? 'rgba(34, 197, 94, 0.5)' : 
                                    ($occupancyRate >= 50 ? 'rgba(59, 130, 246, 0.5)' : 'rgba(99, 102, 241, 0.5)'),
                    'textColor' => $occupancyRate >= 80 ? '#86efac' : 
                                  ($occupancyRate >= 50 ? '#93c5fd' : '#a5b4fc')
                ];
            });

        // Statistiques pour le coach
        $stats = $this->calculateCoachStats($coach);

        return Inertia::render('Coach/Calendar', [
            'initialEvents' => $courses,
            'stats' => $stats,
            'myCourses' => $courses // Pour l'onglet "Mes Cours"
        ]);
    }

    private function calculateCoachStats($coach)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Cours du coach ce mois
        $coursesThisMonth = Course::where('coach_name', $coach->name)
            ->whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->count();

        // Réservations totales pour les cours du coach
        $totalReservations = Reservation::whereHas('course', function ($query) use ($coach) {
                $query->where('coach_name', $coach->name);
            })->count();

        // Taux de remplissage moyen
        $courses = Course::withCount('reservations')
            ->where('coach_name', $coach->name)
            ->get();

        $averageOccupancy = 0;
        if ($courses->count() > 0) {
            $totalOccupancy = $courses->sum(function ($course) {
                return $course->max_participants > 0 ? 
                    ($course->reservations_count / $course->max_participants) * 100 : 0;
            });
            $averageOccupancy = round($totalOccupancy / $courses->count());
        }

        // Revenus estimés (si vous avez un prix)
        $estimatedRevenue = $courses->sum(function ($course) {
            return $course->reservations_count * ($course->price ?? 0);
        });

        // Prochain cours
        $nextCourse = Course::where('coach_name', $coach->name)
            ->where('start_time', '>', now())
            ->orderBy('start_time', 'asc')
            ->first();

        $nextCourseText = $nextCourse ? 
            Carbon::parse($nextCourse->start_time)->diffForHumans() : 
            'Aucun';

        return [
            'coursesThisMonth' => $coursesThisMonth,
            'totalReservations' => $totalReservations,
            'averageOccupancy' => $averageOccupancy,
            'estimatedRevenue' => $estimatedRevenue,
            'nextCourse' => $nextCourseText,
            'totalCourses' => $courses->count()
        ];
    }

    public function store(Request $request)
    {
        $coach = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'max_participants' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255'
        ]);

        Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'max_participants' => $request->max_participants,
            'coach_name' => $coach->name,
            'price' => $request->price,
            'location' => $request->location
        ]);

        return redirect()->back()->with('success', 'Cours créé avec succès!');
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'max_participants' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255'
        ]);

        $course->update($request->all());

        return redirect()->back()->with('success', 'Cours modifié avec succès!');
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        // Vérifier s'il y a des réservations
        if ($course->reservations()->count() > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer un cours avec des réservations.');
        }

        $course->delete();

        return redirect()->back()->with('success', 'Cours supprimé avec succès!');
    }
}