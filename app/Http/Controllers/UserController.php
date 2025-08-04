<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Liste des utilisateurs du tenant (ou tous si platform user).
     */
    public function index()
    {
        $current = Auth::user();

        $users = User::with(['roles', 'tenant'])
        ->when(!$current->is_platform_user(), fn ($q) => $q->where('tenant_id', $current->tenant_id))
        ->join('tenants', 'users.tenant_id', '=', 'tenants.id') // jointure pour accéder au nom
        ->orderBy('tenants.name', 'asc')
        ->select('users.*') // important sinon la jointure surcharge les colonnes
        ->paginate(50);

        return view('back.users.index', compact('users'));
    }

    /**
     * Formulaire de création (accessible si l'utilisateur a create_users).
     */
    public function create()
    {
        $current = Auth::user();

        if (!$current->can('create_users')) {
            abort(403, "Vous n'avez pas l'autorisation de créer des utilisateurs.");
        }

        // Récupère uniquement les rôles du tenant du créateur
        $roles = Role::where('tenant_id', $current->tenant_id)->get();

        return view('back.users.add', compact('roles'));
    }

    /**
     * Enregistre un nouvel utilisateur.
     */
    public function store(Request $request)
    {
        $current = Auth::user();

        if (!$current->can('create_users')) {
            abort(403, "Vous n'avez pas l'autorisation de créer des utilisateurs.");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|exists:roles,id', // Spatie utilise le nom
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            DB::beginTransaction();

            $newUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'tenant_id' => $current->tenant_id, // Toujours le tenant du créateur
                'is_active' => $validated['is_active'],
                'password' => Hash::make($validated['password']),
            ]);
            $role = Role::findOrFail($validated['role']);

            $newUser->syncRoles([$role]);

            DB::commit();

            return redirect()->route('users.index')->with('success', '✅ Utilisateur créé avec succès.');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return back()->with('error', '❌ Une erreur est survenue lors de la création de l’utilisateur.')->withInput();
        }
    }

    /**
     * Formulaire d’édition (uniquement si du même tenant ou platform user).
     */
    public function edit(User $user)
    {
        $current = Auth::user();
        if (!$current->is_platform_user() && $user->tenant_id != $current->tenant_id) {
            abort(403, "Vous n'avez pas l'autorisation de modifier cet utilisateur.");
        }

        if ($current->is_platform_user() && !$current->is_owner && $user->id != $current->id){
            
            abort(403, "Vous n'avez pas l'autorisation de modifier cet utilisateur.");
        }

        $roles = Role::where('tenant_id', $user->tenant_id)->get();

        return view('back.users.edit', compact('user', 'roles'));
    }

    /**
     * Mise à jour d'un utilisateur.
     */
    public function update(Request $request, User $user)
    {
        $current = Auth::user();

        if (!$current->is_platform_user() && $user->tenant_id != $current->tenant_id) {
            abort(403, "Vous n'avez pas l'autorisation de modifier cet utilisateur.");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|string|exists:roles,name',
            'is_active' => 'nullable',
        ]);

        

        $validated['is_active'] = $request->has('is_active');

        $isActive = $validated['is_active'] ?? true;

        // Si c'est un propriétaire, forcer actif
        if ($user->is_owner) {
            $isActive = true;
        }

        try {
            DB::beginTransaction();

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'is_active' =>  $isActive,
                'password' => !empty($validated['password']) ? Hash::make($validated['password']) : $user->password,
            ]);

            $role = Role::where('name', $validated['role'])->firstOrFail();
            $user->syncRoles([$role]);
        
            DB::commit();

            return redirect()->route('users.index')->with('success', '✅ Utilisateur mis à jour avec succès.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', '❌ Une erreur est survenue lors de la mise à jour.')->withInput();
        }
    }

    /**
     * Suppression d'un utilisateur.
     * - Même tenant = OK si permission
     * - Autre tenant = besoin de "delete_any_users"
     */
    public function destroy(User $user)
    {
        $current = Auth::user();

        // Si ce n'est pas le même tenant, vérifier "delete_any_users"
        if ($user->tenant_id != $current->tenant_id && !$current->can('delete_any_users')) {
            abort(403, "Vous n'avez pas l'autorisation de supprimer cet utilisateur.");
        }

        try {
            $user->delete();
            return back()->with('success', "✅ L'utilisateur \"{$user->name}\" a bien été supprimé.");
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', '❌ Une erreur est survenue lors de la suppression.');
        }
    }

    /**
 * Active ou désactive un utilisateur (sauf propriétaire).
 */
public function toggle(String $id)
{
    $user = User::findOrFail($id);
    $current = Auth::user();

    // Interdiction de toucher au propriétaire
    if ($user->is_owner) {
        return back()->with('error', "Vous ne pouvez pas activer/désactiver le propriétaire de l'entreprise.");
    }

    if ($user->id == $current->id) {
        return back()->with('error', "Vous ne pouvez pas activer/désactiver votre propre compte");
    }


    // Vérification des permissions : même tenant OU permission spéciale
    if ($current->tenant_id !== $user->tenant_id && !$current->can('delete_any_users') && !$current->is_platform_user()) {
        return back()->with('error', "Vous n'avez pas le droit de modifier le statut de cet utilisateur.");
    }

    try {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "✅ L'utilisateur « {$user->name} » a été {$status}.");
    } catch (\Throwable $e) {
        report($e);
        return back()->with('error', "❌ Une erreur est survenue lors de la mise à jour de l'utilisateur.");
    }
}

}

