@extends('back.layouts.admin')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-balance-scale"></i> Unités de mesure
    </h1>
    <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addUnitModal">
        <i class="fas fa-plus fa-sm text-white-50"></i> Nouvelle unité
    </button>
</div>

<div class="card shadow border-left-primary">
    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-list-ul"></i> Liste des unités disponibles</h6>
    </div>

<div class="card-body">
    @if($units->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="thead-light text-uppercase text-secondary small">
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Code</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($units as $unit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong class="text-dark">{{ $unit->name }}</strong>
                            </td>
                            <td>
                                <span class="badge badge-pill badge-info px-3 py-2">{{ $unit->code }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.units.edit', $unit->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression de cette unité ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Aucune unité disponible pour le moment.
            <br>
            <small>Créez-en une en cliquant sur le bouton ci-dessus.</small>
        </div>
    @endif
</div>


</div>

<!-- Modal ajout d'une unité -->

<div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog" aria-labelledby="addUnitModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
        @include("back.admin.units._form", [
            "route" => route("admin.units.store"),
            "method" => "POST",
            "unit" => new \App\Models\Units()
        ])
    </div>
  </div>
</div>
@endsection
