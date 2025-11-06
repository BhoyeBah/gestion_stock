@php
    $current_user = auth()->user();

    // helper pour marquer l'item actif selon les patterns de route
    $isActive = function($patterns) {
        foreach ((array) $patterns as $p) {
            if (request()->routeIs($p) || request()->is($p)) {
                return 'active';
            }
        }
        return '';
    };
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">NG SAAS</div>
    </a>

    <hr class="sidebar-divider my-0">

    {{-- Subscription badge --}}
    @if (isset($current_user->tenant) && isset($current_user->tenant->subscriptions))
        @php
            $active_subscription = $current_user->tenant->subscriptions
                ->where('is_active', true)
                ->where('ends_at', '>=', now())
                ->sortByDesc('ends_at')
                ->first();
        @endphp

        @if ($active_subscription)
            <li class="nav-item px-3 m-2">
                <span class="badge badge-success text-center">
                    <i class="fas fa-star"></i> {{ $active_subscription->plan->name }}
                </span>
            </li>
        @endif
    @endif

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item {{ $isActive(['home','dashboard','/']) }}">
        <a class="nav-link" href="{{ url('/') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    {{-- CATALOGUE --}}
    <div class="sidebar-heading">Catalogue</div>

    @can('manage_categories')
    <li class="nav-item {{ $isActive(['categories.*']) }}">
        <a class="nav-link" href="{{ route('categories.index') }}">
            <i class="fas fa-fw fa-layer-group"></i>
            <span>Catégories</span>
        </a>
    </li>
    @endcan

    @canAny(['manage_products','read_products'])
    <li class="nav-item {{ $isActive(['products.*']) }}">
        <a class="nav-link" href="{{ route('products.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>Produits</span>
        </a>
    </li>
    @endcan

    @can('read_suppliers')
    <li class="nav-item {{ $isActive(['suppliers.*']) }}">
        <a class="nav-link" href="{{ route('suppliers.index') }}">
            <i class="fas fa-fw fa-truck-loading"></i>
            <span>Fournisseurs</span>
        </a>
    </li>
    @endcan

    {{-- Garde les liens existants "Clients" et "Fournisseurs" (routes inchangées) --}}
    <li class="nav-item {{ $isActive(['clients.*']) }}">
        <a class="nav-link" href="{{ route('clients.index') }}">
            <i class="fas fa-fw fa-user-friends"></i>
            <span>Clients</span>
        </a>
    </li>


    <hr class="sidebar-divider">

    {{-- STOCK --}}
    @canAny(['manage_warehouses','manage_inventory'])
    <div class="sidebar-heading">Stock</div>

    @can('manage_warehouses')
    <li class="nav-item {{ $isActive(['warehouses.*']) }}">
        <a class="nav-link" href="{{ route('warehouses.index') }}">
            <i class="fas fa-fw fa-warehouse"></i>
            <span>Entrepôts</span>
        </a>
    </li>
    @endcan

    @can('manage_inventory')
    <li class="nav-item {{ $isActive(['inventory.*']) }}">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Inventaires</span>
        </a>
    </li>
    @endcan

    <hr class="sidebar-divider">
    @endcanAny

    {{-- VENTES --}}
    <div class="sidebar-heading">Ventes</div>

    <li class="nav-item {{ $isActive(['invoices.*']) }}">
        <a class="nav-link" href="{{ route('invoices.index', ['type' => 'clients']) }}">
            <i class="fas fa-fw fa-file-invoice"></i>
            <span>Factures Clients</span>
        </a>
    </li>

    <li class="nav-item {{ $isActive(['payments.*']) }}">
        <a class="nav-link" href="{{ route('payments.index', ['type' => 'clients']) }}">
            <i class="fas fa-fw fa-credit-card"></i>
            <span>Paiement clients</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    {{-- ACHATS --}}
    <div class="sidebar-heading">Achats</div>

    <li class="nav-item {{ $isActive(['invoices.*']) }}">
        <a class="nav-link" href="{{ route('invoices.index', ['type' => 'suppliers']) }}">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Factures Fournisseurs</span>
        </a>
    </li>

    <li class="nav-item {{ $isActive(['payments.*']) }}">
        <a class="nav-link" href="{{ route('payments.index', ['type' => 'suppliers']) }}">
            <i class="fas fa-fw fa-money-check-alt"></i>
            <span>Paiement Fournisseurs</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    {{-- FINANCE --}}
    <div class="sidebar-heading">Finance</div>

    <li class="nav-item {{ $isActive(['expenses.*']) }}">
        <a class="nav-link" href="{{ route('expenses.index') }}">
            <i class="fas fa-fw fa-receipt"></i>
            <span>Dépenses</span>
        </a>
    </li>

    <li class="nav-item {{ $isActive(['reports.*']) }}">
        <a class="nav-link" href="{{ route('reports.index') }}">
            <i class="fas fa-fw fa-chart-pie"></i>
            <span>Rapports</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    {{-- ADMINISTRATION (plateforme) --}}
    @if ($current_user->is_platform_user())
        <div class="sidebar-heading">Administration</div>

        @can('manage_permissions')
        <li class="nav-item {{ $isActive(['admin.permissions.*']) }}">
            <a class="nav-link" href="{{ route('admin.permissions.index') }}">
                <i class="fas fa-fw fa-key"></i>
                <span>Permissions</span>
            </a>
        </li>
        @endcan

        @can('manage_plans')
        <li class="nav-item {{ $isActive(['admin.plans.*']) }}">
            <a class="nav-link" href="{{ route('admin.plans.index') }}">
                <i class="fas fa-fw fa-tags"></i>
                <span>Plans</span>
            </a>
        </li>
        @endcan

        @can('manage_tenants')
        <li class="nav-item {{ $isActive(['admin.tenants.*']) }}">
            <a class="nav-link" href="{{ route('admin.tenants.index') }}">
                <i class="fas fa-fw fa-building"></i>
                <span>Entreprises</span>
            </a>
        </li>
        @endcan

        @can('manage_subscriptions')
        <li class="nav-item {{ $isActive(['admin.subscriptions.*']) }}">
            <a class="nav-link" href="{{ route('admin.subscriptions.index') }}">
                <i class="fas fa-fw fa-receipt"></i>
                <span>Souscriptions</span>
            </a>
        </li>
        @endcan

        <hr class="sidebar-divider">
    @endif

    {{-- GESTION --}}
    <div class="sidebar-heading">Gestion</div>

    @can('manage_roles')
    <li class="nav-item {{ $isActive(['roles.*']) }}">
        <a class="nav-link" href="{{ route('roles.index') }}">
            <i class="fas fa-fw fa-user-shield"></i>
            <span>Rôles</span>
        </a>
    </li>
    @endcan

    @can('manage_users')
    <li class="nav-item {{ $isActive(['users.*']) }}">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Utilisateurs</span>
        </a>
    </li>
    @endcan

    @can('manage_invoices')
    <li class="nav-item {{ $isActive(['tenant.subscriptions.*']) }}">
        <a class="nav-link" href="{{ route('tenant.subscriptions.index') }}">
            <i class="fas fa-fw fa-receipt"></i>
            <span>Mes souscriptions</span>
        </a>
    </li>
    @endcan

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
