<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseType;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseManagementController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/CourseManagement/Index', [
            'courseTypes' => CourseType::all(),
            'subscriptionTypes' => SubscriptionType::all()
        ]);
    }

    // Course Type Methods
    public function storeCourseType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:course_types',
            'price' => 'required|numeric|min:0',
        ]);

        CourseType::create($validated);
        
        return back()->with('success', 'Type de cours ajouté avec succès !');
    }

    public function destroyCourseType($id)
    {
        $courseType = CourseType::findOrFail($id);
        $courseType->delete();
        
        return back()->with('success', 'Type de cours supprimé avec succès !');
    }

    // Subscription Type Methods
    public function storeSubscriptionType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subscription_types',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
        ]);

        SubscriptionType::create($validated);
        
        return back()->with('success', 'Type d\'abonnement ajouté avec succès !');
    }

    public function destroySubscriptionType($id)
    {
        $subscriptionType = SubscriptionType::findOrFail($id);
        $subscriptionType->delete();
        
        return back()->with('success', 'Type d\'abonnement supprimé avec succès !');
    }
}