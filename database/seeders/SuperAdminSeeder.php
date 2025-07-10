<?php



namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate([
            'slug' => 'platform',
        ], [
            'name' => 'Plateforme SaaS',
            'email' => 'contact@platform.local',
            'is_active' => true,
        ]);

        User::firstOrCreate([
            'email' => 'admin@platform.local',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('admin1234'),
            'tenant_id' => $tenant->id,
            'is_owner' => true,
            'is_active' => true,
            'is_superadmin' => true,
        ]);
    }
}
