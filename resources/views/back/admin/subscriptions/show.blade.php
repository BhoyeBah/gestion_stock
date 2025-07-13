
@extends('back.layouts.admin')

@push('styles')
<style>
    @media print {
        .d-print-none {
            display: none !important;
        }

        .table th, .table td {
            font-size: 14px;
            color: #000 !important;
            padding: 8px;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        body {
            background: white !important;
            color: #000 !important;
        }

        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 20px;
        }

        .badge {
            color: #000 !important;
            background-color: #f0f0f0 !important;
            font-size: 12px;
        }
    }

    .print-header {
        display: none;
    }

    .table th {
        width: 30%;
        vertical-align: top;
    }

    .card-body {
        transition: all 0.3s ease;
    }

    .btn-copy {
        transition: background-color 0.2s;
    }

    .btn-copy:hover {
        background-color: #17a2b8;
        color: white;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 d-flex align-items-center animate__animated animate__fadeIn">
        <i class="fas fa-file-contract mr-2"></i> Détails de la souscription
    </h1>
    <div class="d-flex">
        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm btn-secondary mr-2 d-print-none" aria-label="Retour à la liste des souscriptions">
            <i class="fas fa-arrow-left mr-1"></i> Retour
        </a>
        <button onclick="copyDetails()" class="btn btn-sm btn-outline-info mr-2 d-print-none btn-copy" aria-label="Copier les détails">
            <i class="fas fa-copy mr-1"></i> Copier
        </button>
        <button onclick="window.print()" class="btn btn-sm btn-outline-primary d-print-none" aria-label="Imprimer la page">
            <i class="fas fa-print mr-1"></i> Imprimer
        </button>
    </div>
</div>

<div class="card shadow border-left-primary mb-4 animate__animated animate__fadeIn">
    <div class="card-body p-4">
        <div class="print-header">
            <h2>Détails de la souscription</h2>
            <p>Imprimé le {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        </div>

        <h5 class="text-primary font-weight-bold mb-3">Informations générales</h5>
        <table class="table table-borderless table-sm">
            <tr>
                <th scope="row">Entreprise :</th>
                <td><strong>{{ $subscription->tenant->name ?? 'Non spécifiée' }}</strong></td>
            </tr>
            <tr>
                <th scope="row">Plan sélectionné :</th>
                <td>
                    <span class="badge badge-info">
                        {{ $subscription->plan->name ?? 'N/A' }} - {{ $subscription->plan->price ? number_format($subscription->plan->price, 0, ',', ' ') : 'N/A' }} FCFA
                    </span>
                </td>
            </tr>
            <tr>
                <th scope="row">Montant payé :</th>
                <td>{{ $subscription->amount_paid ? number_format($subscription->amount_paid, 0, ',', ' ') : 'N/A' }} FCFA</td>
            </tr>
            <tr>
                <th scope="row">Méthode de paiement :</th>
                <td>{{ $subscription->payment_method ?? 'Non spécifiée' }}</td>
            </tr>
        </table>

        <h5 class="text-primary font-weight-bold mt-4 mb-3">Période de validité</h5>
        <table class="table table-borderless table-sm">
            <tr>
                <th scope="row">Date de début :</th>
                <td>{{ $subscription->starts_at ? \Carbon\Carbon::parse($subscription->starts_at)->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <th scope="row">Date d'expiration :</th>
                <td>{{ $subscription->ends_at ? \Carbon\Carbon::parse($subscription->ends_at)->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <th scope="row">Statut :</th>
                <td>
                    @if($subscription->ends_at && $subscription->ends_at->lt(now()))
                        <span class="badge badge-pill badge-secondary" aria-label="Statut de la souscription : Expirée">Expirée</span>
                    @elseif($subscription->is_active)
                        <span class="badge badge-pill badge-success" aria-label="Statut de la souscription : Active">Active</span>
                    @else
                        <span class="badge badge-pill badge-danger" aria-label="Statut de la souscription : Inactive">Inactive</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyDetails() {
        const details = `
Entreprise: {{ $subscription->tenant->name ?? 'Non spécifiée' }}
Plan: {{ $subscription->plan->name ?? 'N/A' }} - {{ $subscription->plan->price ? number_format($subscription->plan->price, 0, ',', ' ') : 'N/A' }} FCFA
Montant payé: {{ $subscription->amount_paid ? number_format($subscription->amount_paid, 0, ',', ' ') : 'N/A' }} FCFA
Méthode de paiement: {{ $subscription->payment_method ?? 'Non spécifiée' }}
Date de début: {{ $subscription->starts_at ? \Carbon\Carbon::parse($subscription->starts_at)->format('d/m/Y') : 'N/A' }}
Date d'expiration: {{ $subscription->ends_at ? \Carbon\Carbon::parse($subscription->ends_at)->format('d/m/Y') : 'N/A' }}
Statut: {{ $subscription->ends_at && $subscription->ends_at->lt(now()) ? 'Expirée' : ($subscription->is_active ? 'Active' : 'Inactive') }}
        `.trim();

        navigator.clipboard.writeText(details).then(() => {
            alert('Détails copiés dans le presse-papiers !');
        }).catch(err => {
            console.error('Erreur lors de la copie : ', err);
            alert('Erreur lors de la copie des détails.');
        });
    }
</script>
@endpush
