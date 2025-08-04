<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;


class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::latest()->paginate(100);
        return view('back.admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.admin.tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tenant.name' => 'required|string|max:255',
            'tenant.slug' => 'required|string|alpha_dash|unique:tenants,slug',
            'tenant.email' => 'nullable|email|required',
            'tenant.phone' => 'nullable|string|max:20',
            'tenant.logo' => 'nullable|image|max:2048',
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|confirmed|min:8',
            'user.phone' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();

        try {
            // 1. Création du tenant
            $tenantData = $request->input('tenant');
            $tenant = new Tenant([
                'name' => $tenantData['name'],
                'slug' => $tenantData['slug'],
                'email' => $tenantData['email'] ?? null,
                'phone' => $tenantData['phone'] ?? null,
            ]);

            // 2. Upload du logo
            if ($request->hasFile('tenant.logo')) {
                $tenant->logo = $request->file('tenant.logo')->store('logos', 'public');
            }

            $tenant->save();

            // 3. Création du rôle admin lié au tenant
            $adminRole = Role::create([
                'name' =>  $roleName = $tenant->slug . "_Admin",
                'guard_name' => 'web',
                'tenant_id' => $tenant->id,
            ]);

            $adminRole->givePermissionTo(['manage_roles']);


            // 4. Création du premier utilisateur
            $userData = $request->input('user');

            $user = new User([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'] ?? null,
                'password' => Hash::make($userData['password']),
                'tenant_id' => $tenant->id,
                'is_owner' => true,
                'is_active' => true,
            
            ]);
            $user->save();

            // 5. Assignation du rôle
            $user->assignRole($adminRole);

            DB::commit();

            return redirect()->route('admin.tenants.index')->with('success', 'Entreprise créée avec succès.');

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Une erreur est survenue lors de la création.')->withInput();
        }
    }

    public function show(Tenant $tenant)
    {
        //
    }

    public function edit(Tenant $tenant)
    {
        return view('back.admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->merge([
            'is_active' => $request->has('is_active'),
        ]);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|alpha_dash|unique:tenants,slug,' . $tenant->id,
            'email' => 'nullable|email|required',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            // Mise à jour des champs
            $tenant->name = $request->name;
            if ($tenant->slug !== "platform"){
                $tenant->slug = $request->slug;
            }
            $tenant->email = $request->email ?? null;
            $tenant->phone = $request->phone ?? null;
            $tenant->is_active = $request->boolean('is_active');


            // Nouveau logo
            if ($request->hasFile('logo')) {
                // Supprimer l'ancien logo si présent
                if ($tenant->logo && Storage::disk('public')->exists($tenant->logo)) {
                    Storage::disk('public')->delete($tenant->logo);
                }

                $tenant->logo = $request->file('logo')->store('logos', 'public');
            }

            $tenant->save();

            return redirect()->route('admin.tenants.index')->with('success', 'Entreprise mise à jour avec succès.');
        } catch (\Throwable $e) {

            report($e);
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour.')->withInput();
        }
    
    }

    public function destroy(Tenant $tenant)
    {
        if ($tenant->slug === 'plateform') {
            return redirect()->back()->with('error', 'Ce tenant ne peut pas être supprimé car il est réservé à la plateforme.');
        }

        try {
            $tenant->delete();

            return redirect()
                ->route('admin.tenants.index')
                ->with('success', 'Entreprise supprimée avec succès.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }
}
