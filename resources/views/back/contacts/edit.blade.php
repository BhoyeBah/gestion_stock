@extends('back.layouts.admin')

@section('content')
<div class="container">

    @php
        // Déterminer le label lisible à partir du type
        $contactLabel = $type === 'clients' ? 'client' : 'fournisseur';
    @endphp

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit"></i> Modifier le {{ $contactLabel }}
        </h1>
        <a href="{{ route("$type.index") }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Retour à la liste des {{ $contactLabel }}s
        </a>
    </div>

    <div class="card shadow border-left-primary">
        <div class="card-header bg-primary text-white py-3">
            <h6 class="m-0 font-weight-bold">Formulaire de modification du {{ $contactLabel }}</h6>
        </div>

        <div class="card-body">
            @include('back.contacts._form', [
                'method' => 'PUT',
                'route' => route("$type.update", $contact->id),
                'contact' => $contact,
            ])
        </div>
    </div>
</div>
@endsection
