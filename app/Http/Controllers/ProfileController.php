<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Traits\LogsActivity;

class ProfileController extends Controller
{
    use LogsActivity;
    /**
     * Affiche le profil de l'utilisateur connecté
     */
    public function edit()
    {
        $user = Auth::user();
        return view('back.profile.edit', compact('user'));
    }

    /**
     * Met à jour le profil de l'utilisateur connecté
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $user->update([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'phone'    => $validated['phone'] ?? null,
                'password' => !empty($validated['password']) 
                                ? Hash::make($validated['password']) 
                                : $user->password,
            ]);
            $this->saveActivity(
                'update_profile',                       // action
                'Mise à jour des informations de profil', // description
                ['profile_id' => auth()->id()]            // données extra optionnelles
            );
            DB::commit();
            
            return back()->with('success', '✅ Profil mis à jour avec succès.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', '❌ Une erreur est survenue.')->withInput();
        }
    }
}
