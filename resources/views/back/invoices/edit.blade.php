@extends('back.layouts.admin')

@section('content')
<div class="container">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-invoice"></i>
            {{ $invoice->exists ? 'Modifier la facture' : 'Nouvelle facture' }}
        </h1>
        <a href="{{ route('invoices.index', $type) }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow border-left-primary">
        <div class="card-header bg-primary text-white py-3">
            <h6 class="m-0 font-weight-bold">
                {{ $invoice->exists ? 'Modification' : 'Formulaire de création' }} de la facture
            </h6>
        </div>

        <div class="card-body">
            @include('back.invoices._form', [
                'route' => route('invoices.update',[ $type, $invoice->id]),
                'method' => 'PUT',
                'invoice' => $invoice,
                'products' => $products,
                'contacts' => $contacts,
                'warehouses' => $warehouses,
                'type' => $type
            ])
        </div>
    </div>
</div>
@endsection
