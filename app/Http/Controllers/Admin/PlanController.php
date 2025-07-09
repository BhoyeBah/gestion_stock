<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;



use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with('permissions')->orderBy('price')->get();
        $permissions = \Spatie\Permission\Models\Permission::orderBy('name')->get();

        return view('back.admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('back.admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active')
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:plans',
            'price' => 'required|integer|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_users' => 'nullable|integer|min:1',
            'max_storage_mb' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Plan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan ajouté avec succès.');
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        $permissions = \Spatie\Permission\Models\Permission::orderBy('name')->get();

        return view('back.admin.plans.edit', compact('plan', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $request->merge([
            'is_active' => $request->has('is_active')
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:plans,slug,' . $plan->id,
            'price' => 'required|integer|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_users' => 'nullable|integer|min:1',
            'max_storage_mb' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $plan->update($validated);
        $plan->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.plans.index')->with('success', 'Plan modifié avec succès.');
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plan supprimé avec succès.');
    }
}
