<?php
use App\Models\Role;

Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        Role::ADMIN => redirect()->route('admin.dashboard'),
        Role::COACH => redirect()->route('coach.dashboard'),
        Role::CLIENT => redirect()->route('client.dashboard'),
        default => abort(403, 'Rôle non autorisé.')
    };
})->name('dashboard');
