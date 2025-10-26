<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        // S'assurer que l'utilisateur existe
        if (!$user) {
            abort(404, 'Utilisateur non trouvé');
        }

        return inertia('Profile/Show', [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'avatar_url' => $user->avatar ? Storage::url($user->avatar) : null,
            ],
            'Role' => $user->role,
            'mustVerifyEmail' => $user instanceof MustVerifyEmail && !$user->hasVerifiedEmail(),
            'status' => session('status'),
        ]);
    }

  public function update(Request $request)
{
    $user = $request->user();
    
    $validated = $request->validate([
        'name' => 'nullable|string|max:255', // nullable au lieu de sometimes|required
        'email' => 'nullable|email|unique:users,email,'.$user->id,
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'password' => 'nullable|min:8|confirmed',
    ]);

    $updateData = [];
    
    if (!empty($validated['name'])) {
        $updateData['name'] = $validated['name'];
    }
    
    if (!empty($validated['email'])) {
        $updateData['email'] = $validated['email'];
    }
    // Mettre à jour l'avatar si un nouveau fichier est fourni
    if ($request->hasFile('avatar')) {
        // Supprimer l'ancien avatar s'il existe
        if ($user->avatar && Storage::exists($user->avatar)) {
            Storage::delete($user->avatar);
        }
        
        // Stocker le nouvel avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $updateData['avatar'] = $path;
    }

    // Mettre à jour le mot de passe seulement s'il est fourni
    if (!empty($validated['password'])) {
        $updateData['password'] = Hash::make($validated['password']);
    }

    // Mettre à jour seulement si des données sont présentes
    if (!empty($updateData)) {
        $user->update($updateData);
    }

    return back()->with('success', 'Profil mis à jour avec succès!');
}
}