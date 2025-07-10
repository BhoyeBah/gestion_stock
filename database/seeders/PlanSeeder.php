<?php



namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Plan Gratuit
        Plan::updateOrCreate(
            ['slug' => 'gratuit'],
            [
                'name' => 'Gratuit',
                'price' => 0,
                'duration_days' => 30,
                'max_users' => 3,
                'max_storage_mb' => 100,
                'is_active' => true,
                'description' => 'Plan gratuit avec 3 utilisateurs max.',
            ]
        );

        // Plan Standard
        Plan::updateOrCreate(
            ['slug' => 'standard'],
            [
                'name' => 'Standard',
                'price' => 10000,
                'duration_days' => 30,
                'max_users' => 10,
                'max_storage_mb' => 1000,
                'is_active' => true,
                'description' => 'Plan intermédiaire pour les PME.',
            ]
        );

        // Plan Premium
        Plan::updateOrCreate(
            ['slug' => 'premium'],
            [
                'name' => 'Premium',
                'price' => 25000,
                'duration_days' => 30,
                'max_users' => 30,
                'max_storage_mb' => 5000,
                'is_active' => true,
                'description' => 'Accès complet avec stockage et utilisateurs élargis.',
            ]
        );

        // Plan Admin (non visible pour les autres)
        Plan::updateOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Admin',
                'price' => 0,
                'duration_days' => 36500, // ~100 ans
                'max_users' => 9999,
                'max_storage_mb' => 999999,
                'is_active' => false, // désactivé pour l'affichage public
                'description' => 'Plan réservé au propriétaire du SaaS.',
            ]
        );
    }
}
