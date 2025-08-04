<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Traits\LogsActivity;

class PermissionController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $permissions = Permission::orderBy('name')->paginate(10);
        return view('back.admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('back.admin.permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:permissions,name',
            'description' => 'nullable|string|max:255',
        ]);

        $validated['guard_name'] = 'web';

        Permission::create($validated);
        $this->saveActivity("Ajout d'une permission", "Permission {$request->name}.");
        return redirect()->route('admin.permissions.index')->with('success', 'Permission créée avec succès.');
    }

    public function edit(Permission $permission)
    {
        return view('back.admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string|max:255',
        ]);

        $permission->update($validated);
        $this->saveActivity("Mise à jour de la permission", "Permission {$request->name}.");

        return redirect()->route('admin.permissions.index')->with('success', 'Permission modifiée avec succès.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        $this->saveActivity("Suppression d'une permission", "Permission {$permission->name}.");

        return redirect()->route('admin.permissions.index')->with('success', 'Permission supprimée.');
    }
}
