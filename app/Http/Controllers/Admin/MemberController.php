<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class MemberController extends Controller
{
    public function index()
    {
        $members = User::whereIn('role', ['member', 'client', 'user'])
                       ->orderByDesc('created_at')
                       ->get();

        return response()->json($members);
    }

    public function edit(User $member)
    {
        return Inertia::render('Client/membres/Edit', [
            'user' => [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'role' => $member->role,
            ]
        ]);
    }

    public function update(Request $request, User $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->id,
            'role' => 'required|in:user,member,client,admin',
            'status' => 'nullable|in:active,inactive,suspended',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'role', 'status']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $member->update($data);

        return redirect()->route('admin.members.edit', $member->id)
                         ->with('success', 'Membre mis à jour avec succès.');
    }

    public function destroy(User $member)
    {
        $member->delete();
        return redirect()->back()->with('success', 'Membre supprimé avec succès.');
    }
}

