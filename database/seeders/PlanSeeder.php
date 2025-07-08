<?php

namespace Database\Seeders;

use App\Models\Plan;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Gratuit',
            'slug' => 'gratuit',
            'price' => 0,
            'duration_days' => 30,
            'max_users' => 3,
            'max_storage_mb' => 100,
            'is_active' => true,
            'description' => 'Plan gratuit avec 3 utilisateurs max.',
        ]);

        Plan::create([
            'name' => 'Standard',
            'slug' => 'standard',
            'price' => 10000,
            'duration_days' => 30,
            'max_users' => 10,
            'max_storage_mb' => 1000,
            'is_active' => true,
            'description' => 'Plan intermédiaire pour les PME.',
        ]);

        Plan::create([
            'name' => 'Premium',
            'slug' => 'premium',
            'price' => 25000,
            'duration_days' => 30,
            'max_users' => 30,
            'max_storage_mb' => 5000,
            'is_active' => true,
            'description' => 'Accès complet avec stockage et utilisateurs élargis.',
        ]);
    }
}
