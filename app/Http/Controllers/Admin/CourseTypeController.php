<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseTypeController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/CourseTypes/Index', [
            'types' => CourseType::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/CourseTypes/Create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:course_types',
        'price' => 'required|numeric|min:0',
    ]);

    CourseType::create($validated);
        return back()->with('success', 'Type de cours créé avec succès !');
}

    
    public function apiIndex()
    {
        return CourseType::all();
    }
}