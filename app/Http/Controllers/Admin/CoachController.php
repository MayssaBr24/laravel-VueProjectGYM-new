<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = User::where('role', 'coach')->select('id', 'name')->get();
        return response()->json($coaches);
    }
}

