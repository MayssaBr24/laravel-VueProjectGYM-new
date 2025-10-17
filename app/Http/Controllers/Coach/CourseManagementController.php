<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:coach']);
    }

    public function mesCours()
    {
        
        $stats = [
            'total' => Course::where('coach_id', auth()->id())->count(),
            'upcoming' => Course::where('coach_id', auth()->id())
                             ->where('start_time', '>', now())
                             ->count(),
            'active' => Course::where('coach_id', auth()->id())
                          ->where('start_time', '<=', now())
                          ->where('end_time', '>=', now())
                          ->count(),
            'completed' => Course::where('coach_id', auth()->id())
                              ->where('end_time', '<', now())
                              ->count(),
        ];

        $query = Course::where('coach_id', auth()->id())
                     ->withCount('reservations');

        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        if (request('status')) {
            match (request('status')) {
                'upcoming' => $query->where('start_time', '>', now()),
                'active' => $query->where('start_time', '<=', now())->where('end_time', '>=', now()),
                'completed' => $query->where('end_time', '<', now()),
                default => null,
            };
        }

        match (request('sort', 'latest')) {
            'name' => $query->orderBy('name'),
            'date' => $query->orderBy('start_time'),
            'participants' => $query->orderByDesc('reservations_count'),
            default => $query->latest(),
        };

        $courses = $query->paginate(10);




        return Inertia::render('Coach/Courses/MesCours', [
            'stats' => $stats,
            'courses' => $courses,
            'filters' => request()->only(['search', 'status', 'sort'])
        ]);
    }
}

