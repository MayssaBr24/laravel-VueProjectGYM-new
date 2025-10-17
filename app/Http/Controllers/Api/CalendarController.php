<?php

namespace App\Http\Controllers\Api;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
class CalendarController extends Controller
{
    public function index()
    {
        $courses = Course::withCount('reservations')->get();
        \Log::info('Courses data:', $courses->toArray());
    
        $events = $courses->map(function ($course) {
            $spotsLeft = $course->max_participants - $course->reservations_count;

            return [
                'id' => $course->id,
                'title' => $course->name . ' (' . $course->coach_name . ')',
                'start' => $course->start_time,
                'end' => $course->end_time,
                'extendedProps' => [
                    'description' => $course->description,
                    'coach' => $course->coach_name,
                    'status' => $spotsLeft <= 0 ? 'full' : 'spots left',
                    'spotsLeft' => $spotsLeft,
                    'maxParticipants' => $course->max_participants,
                ],
            ];
        });

        return Inertia::render('Calendrier/index', [
            'initialEvents' => $events,
            
            'initialCoaches' => $courses->pluck('coach_name')->unique()->values()
        ]);
    }
}