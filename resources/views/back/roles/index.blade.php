@extends('back.layouts.admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">🛡️ Gestion des rôles</h1>
    <a href="{{ route('roles.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau rôle
    </a>
</div>

<div class="card shadow border-left-primary">
    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Liste des rôles</h6>
    </div>

    <div class="card-body">
        @if($roles->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle text-sm">
                    <thead class="thead-light text-uppercase text-secondary">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Permissions</th>
                            @if(auth()->user()->is_platform_user())
                            <th>Tenant</th>
                            @endif
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ Str::after($role->name, '_') }}</strong></td>
                                <td>
                                    @forelse($role->permissions->take(3) as $permission)
                                        <span class="badge badge-info">{{ $permission->description }}</span>
                                    @empty
                                        <span class="text-muted">Aucune</span>
                                    @endforelse
                                    @if($role->permissions->count() > 3)
                                        <span class="badge badge-light">+{{ $role->permissions->count() - 3 }}</span>
                                    @endif
                                </td>
                                @if(auth()->user()->is_platform_user())
                                <td>
                                    <span class="badge badge-secondary">{{ $role->tenant->name }}</span>
                                </td>
                                @endif
                                <td class="text-center">
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(strtolower(Str::after($role->name, '_')) != "admin")
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce rôle ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $roles->links() }}
                </div>
            </div>
        @else
            <div class="alert alert-info">
                Aucun rôle n’a encore été défini. Cliquez sur "Nouveau rôle" pour commencer.
            </div>
        @endif
    </div>
</div>
@endsection
