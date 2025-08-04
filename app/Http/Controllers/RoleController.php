<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\Permission;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Affiche la liste des rôles.
     */
    public function index()
    {
        $user = Auth::user();

        $roles = Role::with(['permissions', 'tenant'])
            ->when(!$user->is_platform_user(), fn ($query) => $query->where('tenant_id', $user->tenant_id))
            ->latest()
            ->paginate(50);

        return view('back.roles.index', compact('roles'));
    }

    /**
     * Affiche le formulaire de création d'un rôle.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->is_platform_user()) {
            $tenants = Tenant::where('is_active', true)->get();
            $permissions = Permission::all();
        } else {
            $tenants = [$user->tenant];
            $active_subscription = Subscription::where('tenant_id', $user->tenant_id)
                                               ->where('is_active', true)
                                               ->first();

            // On récupère les permissions via le plan
            $permissions = $active_subscription?->plan?->permissions ?? collect();
        }

        return view('back.roles.add', compact('tenants', 'permissions'));
    }

    /**
     * Enregistre un nouveau rôle.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tenant_id' => 'required|exists:tenants,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $user = Auth::user();

        if ($user->tenant_id != $validated['tenant_id'] && !$user->is_platform_user()) {
            return back()->with('error', "Vous n'avez pas le droit d'effectuer cet ajout")->withInput();
        }

        $tenant = Tenant::findOrFail($validated['tenant_id']);
        $roleName = $tenant->slug . '_' . strtolower(str_replace(' ', '_', $validated['name']));

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $roleName,
                'guard_name' => 'web',
                'tenant_id' => $tenant->id,
            ]);

            if (!empty($validated['permissions'])) {
                $role->permissions()->sync($validated['permissions']);
            }

            DB::commit();

            return redirect()->route('roles.index')->with('success', '✅ Rôle créé avec succès.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', '❌ Une erreur est survenue lors de la création du rôle.')->withInput();
        }
    }

    /**
     * Affiche un rôle (non utilisé pour le moment).
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Formulaire d’édition d’un rôle (à implémenter).
     */
    public function edit(Role $role)
    {
        $user = Auth::user();

        if (!$user->is_platform_user() && $role->tenant_id != $user->tenant_id) {
            return back()->with('error', "Vous n'avez pas le droit de modifier ce rôle.");
        }

        $tenants = $user->is_platform_user()
            ? Tenant::where('is_active', true)->get()
            : [$user->tenant];

        if ($user->is_platform_user()) {
            // L'utilisateur appartient à la plateforme => il peut accéder à toutes les permissions
            $permissions = Permission::all();
        } else {
            // L'utilisateur appartient à un tenant, on récupère les permissions via l'abonnement actif du rôle sélectionné
            $tenantId = $user->roles()->first()?->tenant_id ?? $user->tenant_id;

            $planPermissions = Subscription::where('tenant_id', $tenantId)
                ->where('is_active', true)
                ->first()?->plan?->permissions;

            $permissions = $planPermissions ?? collect();
        }

        return view('back.roles.edit', compact('role', 'tenants', 'permissions'));
    }

    /**
 * Met à jour un rôle.
 */
public function update(Request $request, string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $user = Auth::user();

        // Vérifier que l'utilisateur peut modifier le rôle
        if (!$user->is_platform_user() && $role->tenant_id != $user->tenant_id) {
            return back()->with('error', "Vous n'avez pas le droit de modifier ce rôle.");
        }

        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tenant_id' => 'required|exists:tenants,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Empêcher qu'un user non-platform change de tenant_id
        if (!$user->is_platform_user()) {
            $validated['tenant_id'] = $role->tenant_id;
        }

        // Nouveau nom du rôle formaté
        $tenant = Tenant::findOrFail($validated['tenant_id']);
        $roleName = $tenant->slug . '_' . strtolower(str_replace(' ', '_', $validated['name']));

        try {
            DB::beginTransaction();

            // Mise à jour du rôle
            $role->update([
                'name' => $roleName,
                'tenant_id' => $tenant->id,
            ]);

            // Mise à jour des permissions
            $role->permissions()->sync($validated['permissions'] ?? []);

            DB::commit();

            return redirect()->route('roles.index')->with('success', '✅ Rôle mis à jour avec succès.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', '❌ Une erreur est survenue lors de la mise à jour du rôle.')->withInput();
        }
    }


    /**
     * Supprime un rôle.
     */
    public function destroy(Role $role)
    {
        $user = Auth::user();

        if ($user->tenant_id !== $role->tenant_id && !$user->is_platform_user()) {
            return back()->with('error', "Vous n'avez pas le droit de supprimer ce rôle.");
        }

        try {
            $role->delete();
            return back()->with('success', "✅ Le rôle \"{$role->name}\" a bien été supprimé.");
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', '❌ Une erreur est survenue lors de la suppression.');
        }
    }
}
