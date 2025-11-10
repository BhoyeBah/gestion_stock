<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'access_dashboard', 'description' => 'Accéder au tableau de bord'],

            // Users & Roles
            ['name' => 'manage_users', 'description' => 'Gérer les utilisateurs'],
            ['name' => 'manage_roles', 'description' => 'Gérer les rôles'],

            // Products
            ['name' => 'read_products', 'description' => 'Voir les produits'],
            ['name' => 'toggle_products', 'description' => 'Activer / Désactiver des produits'],

            // Clients
            ['name' => 'read_clients', 'description' => 'Voir la liste des clients'],
            ['name' => 'read_client', 'description' => 'Voir un client'],
            ['name' => 'create_clients', 'description' => 'Créer des clients'],
            ['name' => 'update_clients', 'description' => 'Modifier des clients'],
            ['name' => 'delete_clients', 'description' => 'Supprimer des clients'],
            ['name' => 'toggle_clients', 'description' => 'Activer / Désactiver des clients'],

            // Suppliers
            ['name' => 'read_suppliers', 'description' => 'Voir la liste des fournisseurs'],
            ['name' => 'read_supplier', 'description' => 'Voir un fournisseur'],
            ['name' => 'create_suppliers', 'description' => 'Créer des fournisseurs'],
            ['name' => 'update_suppliers', 'description' => 'Modifier des fournisseurs'],
            ['name' => 'delete_suppliers', 'description' => 'Supprimer des fournisseurs'],
            ['name' => 'toggle_suppliers', 'description' => 'Activer / Désactiver des fournisseurs'],

            // Invoices
            ['name' => 'manage_invoices', 'description' => 'Gérer les factures'],
            ['name' => 'validate_invoices', 'description' => 'Valider les factures'],
            ['name' => 'pay_invoices', 'description' => 'Effectuer le paiement des factures'],

            // Expenses
            ['name' => 'manage_expenses', 'description' => 'Gérer les dépenses'],

            // Warehouses
            ['name' => 'manage_warehouses', 'description' => 'Gérer les entrepôts'],
            ['name' => 'toggle_warehouses', 'description' => 'Activer / Désactiver un entrepôt'],

            // Categories & Units
            ['name' => 'manage_categories', 'description' => 'Gérer les catégories'],
            ['name' => 'manage_units', 'description' => 'Gérer les unités'],

            // Subscriptions & Plans & Tenants
            ['name' => 'manage_subscriptions', 'description' => 'Gérer les abonnements'],
            ['name' => 'manage_plans', 'description' => 'Gérer les plans'],
            ['name' => 'manage_tenants', 'description' => 'Gérer les entreprises (tenants)'],

            // Settings
            ['name' => 'manage_settings', 'description' => 'Gérer les paramètres'],

            // Notifications
            ['name' => 'manage_notifications', 'description' => 'Gérer les notifications'],

            // Reports
            ['name' => 'read_reports', 'description' => 'Voir les rapports'],

            // Activities
            ['name' => 'read_activities', 'description' => 'Voir les activités'],
            // Pour Inventaires (Stock & Logistique)
            ['name' => 'manage_inventory', 'description' => 'Gérer les inventaires'],

            // Pour Paiements Clients / Fournisseurs
            ['name' => 'read_payments', 'description' => 'Voir les paiements'],

            // Pour l’Administration Plateforme - Permissions Globales
            ['name' => 'manage_permissions', 'description' => 'Gérer les permissions globales'],

        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                [
                    'id' => (string) Str::uuid(),
                    'description' => $perm['description'],
                ]
            );
        }
    }
}
