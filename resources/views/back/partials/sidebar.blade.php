@php
    $current_user = auth()->user();

    // Helper pour marquer l'item actif selon les patterns de route ou d'URI
    $isActive = function ($patterns) {
        foreach ((array) $patterns as $p) {
            if (request()->routeIs($p) || request()->is($p)) {
                return true;
            }
        }
        return false;
    };

    // Trouver l'abonnement actif pour l'affichage du badge
    $active_subscription = null;
    if (isset($current_user->tenant) && isset($current_user->tenant->subscriptions)) {
        $active_subscription = $current_user->tenant->subscriptions
            ->where('is_active', true)
            ->where('ends_at', '>=', now())
            ->sortByDesc('ends_at')
            ->first();
    }

    // Détermine si un menu parent doit être ouvert/actif
    $isVentesActive =
        ($isActive(['invoices.*']) && request('type') == 'clients') ||
        ($isActive(['payments.*']) && request('type') == 'clients');
    $isAchatsActive =
        ($isActive(['invoices.*']) && request('type') == 'suppliers') ||
        ($isActive(['payments.*']) && request('type') == 'suppliers');
    $isGestionActive = $isActive(['roles.*', 'users.*', 'tenant.subscriptions.*']);

    $isVentesOpen = $isVentesActive ? 'show' : '';
    $isAchatsOpen = $isAchatsActive ? 'show' : '';
    $isGestionOpen = $isGestionActive ? 'show' : '';
@endphp

<style>
    /*
    * CSS Custom pour le dégradé de fond, la lisibilité et l'impact visuel
    */
    .sidebar {
        /* NOUVEAU : Application du dégradé de fond demandé */
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        /* Assure que la couleur de base est sombre pour les éléments .sidebar-dark */
        color: #ffffff;
    }

    .sidebar .nav-item .nav-link {
        padding: 0.9rem 1rem;
        font-size: 0.95rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.85);
        /* Texte légèrement transparent */
    }

    .sidebar .nav-item .nav-link:hover {
        color: #ffffff;
        /* Blanc pur au survol */
        background-color: rgba(255, 255, 255, 0.15);
        /* Léger fond au survol */
    }

    .sidebar .nav-item .nav-link i {
        font-size: 1.1rem;
        margin-right: 0.75rem;
        color: #ffffff;
        /* Icônes en blanc pur */
    }

    /* Style pour l'élément actif */
    .sidebar .nav-item.active .nav-link {
        font-weight: 700;
        color: #ffffff;
        background-color: rgba(0, 0, 0, 0.2);
        /* Fond plus sombre pour l'actif */
        border-left: 4px solid #f9f7a7;
        /* Liseré couleur d'accent (jaune très clair pour contraste) */
    }

    .sidebar .nav-item.active .nav-link i {
        color: #f9f7a7;
        /* Icône de la section active en couleur d'accent */
    }

    /* Style pour le titre de section */
    .sidebar .sidebar-heading {
        padding: 0.8rem 1rem 0.5rem;
        font-size: 0.75rem;
        color: #e0e0e0;
        /* Couleur claire pour les titres */
    }

    /* Brand (Logo et Nom du SaaS) */
    .sidebar .sidebar-brand-icon {
        color: #f9f7a7 !important;
        /* Accentuation du logo */
    }

    .sidebar .sidebar-brand-text {
        color: #ffffff;
    }

    /* Styles des sous-menus (doivent être clairs sur le fond blanc de Bootstrap) */
    .sidebar .collapse .collapse-inner {
        padding: 0;
        /* Réinitialiser le padding Bootstrap */
        background-color: #ffffff;
        color: #333333;
    }

    .sidebar .collapse .collapse-inner a.collapse-item {
        padding: 0.4rem 1.5rem;
        font-size: 0.85rem;
        color: #333333;
    }

    .sidebar .collapse .collapse-inner a.collapse-item:hover {
        background-color: #f0f0f0;
    }

    .sidebar .collapse .collapse-inner a.collapse-item.active {
        color: #764ba2;
        /* Couleur du lien actif dans le sous-menu */
        background-color: #f0f0f0;
        font-weight: 600;
    }
</style>

<!-- Sidebar -->
<!-- Suppression de la classe 'bg-gradient-primary' pour laisser le CSS personnalisé prendre le relais -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand (Logo et Nom du SaaS) -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-rocket"></i>
        </div>
        <div class="sidebar-brand-text mx-3">NG SAAS</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    {{-- Badge d'abonnement --}}
    @if ($active_subscription)
        <li class="nav-item py-2 px-3 text-center">
            {{-- Le badge doit rester lisible, j'utilise donc une classe Bootstrap standard --}}
            <span class="badge badge-success text-uppercase shadow-sm">
                <i class="fas fa-crown mr-1"></i> {{ $active_subscription->plan->name }}
            </span>
        </li>
        <hr class="sidebar-divider">
    @endif

    <!-- 1. Dashboard -->
    <li class="nav-item {{ $isActive(['home', 'dashboard', '/']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/') }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    {{-- 2. CATALOGUE & PARTENAIRES --}}
    <div class="sidebar-heading">
        Catalogue & Partenaires
    </div>

    @can('manage_categories')
        <li class="nav-item {{ $isActive(['categories.*']) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('categories.index') }}">
                <i class="fas fa-th-large"></i>
                <span>Catégories</span>
            </a>
        </li>
    @endcan

    @canAny(['manage_products', 'read_products'])
        <li class="nav-item {{ $isActive(['products.*']) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('products.index') }}">
                <i class="fas fa-box-open"></i>
                <span>Produits</span>
            </a>
        </li>
    @endcan

    <li class="nav-item {{ $isActive(['clients.*']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('clients.index') }}">
            <i class="fas fa-user-tie"></i>
            <span>Clients</span>
        </a>
    </li>

    @can('read_suppliers')
        <li class="nav-item {{ $isActive(['suppliers.*']) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('suppliers.index') }}">
                <i class="fas fa-shipping-fast"></i>
                <span>Fournisseurs</span>
            </a>
        </li>
    @endcan

    <!-- Divider -->
    <hr class="sidebar-divider">

    {{-- 3. STOCK & LOGISTIQUE --}}
    @canAny(['manage_warehouses', 'manage_inventory'])
        <div class="sidebar-heading">
            Stock & Logistique
        </div>

        @can('manage_warehouses')
            <li class="nav-item {{ $isActive(['warehouses.*']) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('warehouses.index') }}">
                    <i class="fas fa-warehouse"></i>
                    <span>Entrepôts</span>
                </a>
            </li>
        @endcan

        @can('manage_inventory')
            <li class="nav-item {{ $isActive(['inventory.*']) ? 'active' : '' }}">
                <a class="nav-link" href="#">
                    <i class="fas fa-boxes"></i>
                    <span>Inventaires</span>
                </a>
            </li>
        @endcan

        <!-- Divider -->
        <hr class="sidebar-divider">
    @endcanAny

    {{-- 4. TRANSACTIONS (Ventes & Achats) --}}
    <div class="sidebar-heading">
        Transactions
    </div>

    {{-- VENTES COLLAPSIBLE --}}
    <li class="nav-item {{ $isVentesActive ? 'active' : '' }}">
        <a class="nav-link {{ $isVentesActive ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#collapseVentes" aria-expanded="{{ $isVentesActive ? 'true' : 'false' }}"
            aria-controls="collapseVentes">
            <i class="fas fa-handshake"></i>
            <span>Ventes</span>
        </a>
        <div id="collapseVentes" class="collapse {{ $isVentesOpen }}" aria-labelledby="headingVentes"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ $isActive(['invoices.*']) && request('type') == 'clients' ? 'active d-sm-block' : 'd-sm-block' }}"
                    href="{{ route('invoices.index', ['type' => 'clients']) }}">Factures Clients</a>
                <a class="collapse-item {{ $isActive(['payments.*']) && request('type') == 'clients' ? 'active d-sm-block' : 'd-sm-block' }}"
                    href="{{ route('payments.index', ['type' => 'clients']) }}">Paiements Clients</a>
            </div>
        </div>
    </li>

    {{-- ACHATS COLLAPSIBLE --}}
    <li class="nav-item {{ $isAchatsActive ? 'active' : '' }}">
        <a class="nav-link {{ $isAchatsActive ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#collapseAchats" aria-expanded="{{ $isAchatsActive ? 'true' : 'false' }}"
            aria-controls="collapseAchats">
            <i class="fas fa-shopping-basket"></i>
            <span>Achats</span>
        </a>
        <div id="collapseAchats" class="collapse {{ $isAchatsOpen }}" aria-labelledby="headingAchats"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ $isActive(['invoices.*']) && request('type') == 'suppliers' ? 'active d-sm-block' : 'd-sm-block' }}"
                    href="{{ route('invoices.index', ['type' => 'suppliers']) }}">Factures Fournisseurs</a>
                <a class="collapse-item {{ $isActive(['payments.*']) && request('type') == 'suppliers' ? 'active d-sm-block' : 'd-sm-block' }}"
                    href="{{ route('payments.index', ['type' => 'suppliers']) }}">Paiements Fournisseurs</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    {{-- 5. FINANCE & RAPPORTS --}}
    <div class="sidebar-heading">
        Finance & Rapports
    </div>

    <li class="nav-item {{ $isActive(['expenses.*']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('expenses.index') }}">
            <i class="fas fa-money-bill-wave"></i>
            <span>Dépenses</span>
        </a>
    </li>

    <li class="nav-item {{ $isActive(['reports.*']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('reports.index') }}">
            <i class="fas fa-chart-line"></i>
            <span>Rapports Financiers</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    {{-- 6. GESTION DU SYSTÈME --}}
    <div class="sidebar-heading">
        Gestion du Système
    </div>

    {{-- GESTION COLLAPSIBLE --}}
    <li class="nav-item {{ $isGestionActive ? 'active' : '' }}">
        <a class="nav-link {{ $isGestionActive ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#collapseGestion" aria-expanded="{{ $isGestionActive ? 'true' : 'false' }}"
            aria-controls="collapseGestion">
            <i class="fas fa-users-cog"></i>
            <span>Utilisateurs & Accès</span>
        </a>
        <div id="collapseGestion" class="collapse {{ $isGestionOpen }}" aria-labelledby="headingGestion"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @can('manage_roles')
                    <a class="collapse-item {{ $isActive(['roles.*']) ? 'active d-sm-block' : 'd-sm-block' }}"
                        href="{{ route('roles.index') }}">Rôles</a>
                @endcan
                @can('manage_users')
                    <a class="collapse-item {{ $isActive(['users.*']) ? 'active d-sm-block' : 'd-sm-block' }}"
                        href="{{ route('users.index') }}">Utilisateurs</a>
                @endcan
                @can('manage_invoices')
                    <a class="collapse-item {{ $isActive(['tenant.subscriptions.*']) ? 'active d-sm-block' : 'd-sm-block' }}"
                        href="{{ route('tenant.subscriptions.index') }}">Mes Souscriptions</a>
                @endcan
            </div>
        </div>
    </li>

    {{-- ADMINISTRATION PLATEFORME --}}
    @if ($current_user->is_platform_user())
        <!-- Divider -->
        <hr class="sidebar-divider">
        <div class="sidebar-heading">
            Administration Plateforme
        </div>

        @can('manage_permissions')
            <li class="nav-item {{ $isActive(['admin.permissions.*']) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.permissions.index') }}">
                    <i class="fas fa-shield-alt"></i>
                    <span>Permissions Globales</span>
                </a>
            </li>
        @endcan

        @can('manage_plans')
            <li class="nav-item {{ $isActive(['admin.plans.*']) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.plans.index') }}">
                    <i class="fas fa-gem"></i>
                    <span>Plans d'Abonnement</span>
                </a>
            </li>
        @endcan

        @can('manage_tenants')
            <li class="nav-item {{ $isActive(['admin.tenants.*']) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.tenants.index') }}">
                    <i class="fas fa-city"></i>
                    <span>Entreprises (Tenants)</span>
                </a>
            </li>
        @endcan

        @can('manage_subscriptions')
            <li class="nav-item {{ $isActive(['admin.subscriptions.*']) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.subscriptions.index') }}">
                    <i class="fas fa-receipt"></i>
                    <span>Souscriptions Globales</span>
                </a>
            </li>
        @endcan
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (conserver pour la fonctionnalité) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
