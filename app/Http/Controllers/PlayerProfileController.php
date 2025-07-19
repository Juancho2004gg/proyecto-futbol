<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlayerProfile;
use Illuminate\Support\Facades\Auth;

class PlayerProfileController extends Controller
{
    public function create()
    {
        // Verifica si el usuario es futbolista y si ya tiene un perfil
        // Si no es futbolista o ya tiene un perfil, redirige al dashboard
        // Si es futbolista y no tiene perfil, muestra el formulario de creación
    $user = auth()->user();
    if ($user->role !== 'futbolista') {
        return redirect()->route('dashboard');
    }
    if ($user->playerProfile) {
        return redirect()->route('dashboard');
    }
        return inertia('PlayerProfile/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'age' => 'required|integer|min:16|max:40',
            'positions' => 'nullable|string|max:255',
            'preferred_foot' => 'nullable|string|max:20',
            'current_clubs' => 'nullable|string|max:255',
            'previous_clubs' => 'nullable|string|max:255',
            'leagues_seasons' => 'nullable|string|max:255',
            'stats' => 'nullable|string',
            'achievements' => 'nullable|string',
            'about' => 'nullable|string',
        ]);

        PlayerProfile::create([
            'user_id' => Auth::id(),
            'age' => $request->age,
            'positions' => $request->positions,
            'preferred_foot' => $request->preferred_foot,
            'current_clubs' => $request->current_clubs,
            'previous_clubs' => $request->previous_clubs,
            'leagues_seasons' => $request->leagues_seasons,
            'stats' => $request->stats,
            'achievements' => $request->achievements,
            'about' => $request->about,
        ]);

        return redirect()->route('dashboard')->with('success', 'Perfil deportivo guardado.');
    }
}
