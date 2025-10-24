@php
    $current_user = auth()->user();
@endphp
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">NG SAAS</div>
    </a>


    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    @if(isset($current_user->tenant) && isset($current_user->tenant->subscriptions))
        @php
            $active_subscription = $current_user->tenant->subscriptions
                ->where('is_active', true)
                ->where('ends_at', '>=', now())
                ->sortByDesc('ends_at')
                ->first();
        @endphp

        @if($active_subscription)
            <li class="nav-item px-3 m-2">
                <span class="badge badge-success text-center">
                    {{ $active_subscription->plan->name }}
                </span>
            </li>
        @endif
    @endif
    <hr class="sidebar-divider my-0">


    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    @if($current_user->can('manage_categories'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('categories.index') }}">
            <i class="fas fa-receipt"></i>
            <span>Catégories</span>
        </a>
    </li>
    @endif

    @if($current_user->can('read_products'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('products.index') }}">
            <i class="fas fa-tags"></i>
            <span>Produits</span>
        </a>
    </li>
    @endif

    @if($current_user->can('read_suppliers'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('suppliers.index') }}">
            <i class="fas fa-users"></i>
            <span>Fournisseurs</span>
        </a>
    </li>
    @endif

    @if($current_user->can('manage_warehouses'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('warehouses.index') }}">
            <i class="fas fa-warehouse"></i>
            <span>Entrêpots</span>
        </a>
    </li>
    @endif

    {{-- Section visible uniquement pour l'admin plateforme --}}
    @if($current_user->is_platform_user())
        <hr class="sidebar-divider">
        <div class="sidebar-heading">
            Administration
        </div>

        @if($current_user->can('manage_permissions'))
        <!-- Permissions -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.permissions.index') }}">
                <i class="fas fa-fw fa-key"></i>
                <span>Permissions</span>
            </a>
        </li>
        @endif

        @if($current_user->can('manage_plans'))
        <!-- Plans -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.plans.index') }}">
                <i class="fas fa-fw fa-tags"></i>
                <span>Plans</span>
            </a>
        </li>
        @endif

        @if($current_user->can('manage_tenants'))
        <!-- Tenants -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.tenants.index') }}">
                <i class="fas fa-fw fa-building"></i>
                <span>Entreprises</span>
            </a>
        </li>
        @endif

        @if($current_user->can('manage_subscriptions'))
        <!-- Subscriptions -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.subscriptions.index') }}">
                <i class="fas fa-fw fa-receipt"></i>
                <span>Souscriptions</span>
            </a>
        </li>
        @endif
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <div class="sidebar-heading">
        Gestion
    </div>

    @if($current_user->can('manage_roles'))
    <!-- Rôles -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('roles.index') }}">
            <i class="fas fa-fw fa-user-shield"></i>
            <span>Rôles</span>
        </a>
    </li>
    @endif


    @if($current_user->can('manage_users'))
    <!-- Utilisateurs -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Utilisateurs</span>
        </a>
    </li>
    @endif

    @if($current_user->can('manage_invoices'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('tenant.subscriptions.index') }}">
            <i class="fas fa-receipt"></i>
            <span>Mes souscriptions</span>
        </a>
    </li>
    @endif






    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
