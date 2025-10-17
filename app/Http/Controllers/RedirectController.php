<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\Role;

class RedirectController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $role = Auth::user()->role;

        return match ($role) {
            Role::ADMIN => redirect()->route('admin.dashboard'),
            Role::COACH => redirect()->route('coach.dashboard'),
            Role::CLIENT => redirect()->route('client.dashboard'),
            default => abort(403, 'Rôle non autorisé.')
        };
    }
}

