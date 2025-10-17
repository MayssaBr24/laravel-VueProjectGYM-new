<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Inertia\Inertia;

class PlanningController extends Controller
{
    public function index()
    {
        $courses = Course::with('coach')->orderBy('start_time')->get();

        return Inertia::render('Admin/Planning/Index', [
            'courses' => $courses
        ]);
    }
}

