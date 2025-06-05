<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:usuarios',
                'password' => 'required|string|min:8',
            ]);

            $validated['password'] = Hash::make($validated['password']);

            User::create($validated);

            return redirect()->route('users.index')
                ->with('flash.success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('flash.error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:usuarios,username,' . $user->id,
                'password' => 'nullable|string|min:8',
            ]);

            if ($validated['password']) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            return redirect()->route('users.index')
                ->with('flash.success', 'Usuario actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('flash.error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect()->route('users.index')
                ->with('flash.success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('flash.error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
} 