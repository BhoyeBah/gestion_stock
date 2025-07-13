<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // System
            ['name' => 'manage_roles', 'description' => 'Gerer les roles'],

            // Utilisateurs
            ['name' => 'view_users', 'description' => 'Voir la liste des utilisateurs'],
            ['name' => 'create_users', 'description' => 'Créer des utilisateurs'],
            ['name' => 'edit_users', 'description' => 'Modifier des utilisateurs'],
            ['name' => 'delete_users', 'description' => 'Supprimer des utilisateurs'],

            // Produits
            ['name' => 'view_products', 'description' => 'Voir les produits'],
            ['name' => 'create_products', 'description' => 'Ajouter des produits'],
            ['name' => 'edit_products', 'description' => 'Modifier des produits'],
            ['name' => 'delete_products', 'description' => 'Supprimer des produits'],

            // Ventes
            ['name' => 'view_sales', 'description' => 'Voir les ventes'],
            ['name' => 'create_sales', 'description' => 'Créer des ventes'],
            ['name' => 'edit_sales', 'description' => 'Modifier des ventes'],
            ['name' => 'delete_sales', 'description' => 'Annuler ou supprimer des ventes'],

            // Abonnements
            ['name' => 'manage_subscriptions', 'description' => 'Gérer les abonnements'],

            // Paramètres généraux
            ['name' => 'manage_settings', 'description' => 'Gérer les paramètres de l\'entreprise'],

            // Dashboard
            ['name' => 'access_dashboard', 'description' => 'Accéder au tableau de bord'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                ['description' => $perm['description']]
            );
        }
    }
}
