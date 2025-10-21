<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\Permission;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\LogsActivity;

class RoleController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $user = Auth::user();

        $roles = Role::with(['permissions', 'tenant'])
            ->when(!$user->is_platform_user(), fn ($query) => $query->where('tenant_id', $user->tenant_id))
            ->latest()
            ->paginate(50);

        return view('back.roles.index', compact('roles'));
    }

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
            $permissions = $active_subscription?->plan?->permissions ?? collect();
        }

        return view('back.roles.add', compact('tenants', 'permissions'));
    }

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

            // 🔹 Log activité
            $this->saveActivity(
                "Création d'un rôle",
                "Rôle: {$role->name}",
                ['tenant_id' => $tenant->id]
            );

            return redirect()->route('roles.index')->with('success', '✅ Rôle créé avec succès.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', '❌ Une erreur est survenue lors de la création du rôle.')->withInput();
        }
    }

    public function show(string $id)
    {
        abort(404);
    }

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
            $permissions = Permission::all();
        } else {
            $tenantId = $user->roles()->first()?->tenant_id ?? $user->tenant_id;

            $planPermissions = Subscription::where('tenant_id', $tenantId)
                ->where('is_active', true)
                ->first()?->plan?->permissions;

            $permissions = $planPermissions ?? collect();
        }

        return view('back.roles.edit', compact('role', 'tenants', 'permissions'));
    }

    public function update(Request $request, string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $user = Auth::user();

        if (!$user->is_platform_user() && $role->tenant_id != $user->tenant_id) {
            return back()->with('error', "Vous n'avez pas le droit de modifier ce rôle.");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tenant_id' => 'required|exists:tenants,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if (!$user->is_platform_user()) {
            $validated['tenant_id'] = $role->tenant_id;
        }

        $tenant = Tenant::findOrFail($validated['tenant_id']);
        $roleName = $tenant->slug . '_' . strtolower(str_replace(' ', '_', $validated['name']));

        try {
            DB::beginTransaction();

            $role->update([
                'name' => $roleName,
                'tenant_id' => $tenant->id,
            ]);

            $role->permissions()->sync($validated['permissions'] ?? []);

            DB::commit();

            // 🔹 Log activité
            $this->saveActivity(
                "Mise à jour d'un rôle",
                "Rôle: {$role->name}",
                ['tenant_id' => $tenant->id]
            );

            return redirect()->route('roles.index')->with('success', '✅ Rôle mis à jour avec succès.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', '❌ Une erreur est survenue lors de la mise à jour du rôle.')->withInput();
        }
    }

    public function destroy(Role $role)
    {
        $user = Auth::user();

        if ($user->tenant_id !== $role->tenant_id && !$user->is_platform_user()) {
            return back()->with('error', "Vous n'avez pas le droit de supprimer ce rôle.");
        }

        try {
            $roleName = $role->name;
            $tenantId = $role->tenant_id;
            $role->delete();

            // 🔹 Log activité
            $this->saveActivity(
                "Suppression d'un rôle",
                "Rôle: {$roleName}",
                ['tenant_id' => $tenantId]
            );

            return back()->with('success', "✅ Le rôle \"{$roleName}\" a bien été supprimé.");
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', '❌ Une erreur est survenue lors de la suppression.');
        }
    }
}
