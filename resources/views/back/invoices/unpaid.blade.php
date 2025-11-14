@extends("back.layouts.admin")

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Factures Clients non payées</h1>
        <a href="#" target="_blank" class="btn btn-primary">
            <i class="fas fa-print"></i> Imprimer la liste
        </a>
    </div>

    @include("back.invoices._table", ['invoices' => $invoices])
</div>
@endsection
