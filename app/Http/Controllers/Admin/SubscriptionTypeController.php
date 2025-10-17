<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionTypeController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/SubscriptionTypes/Index', [
            'types' => SubscriptionType::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subscription_types',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
        ]);

        SubscriptionType::create($validated);

           return back()->with('success', 'Type d’abonnement ajouté !');

    }
}
