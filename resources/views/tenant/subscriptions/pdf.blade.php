<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture - Souscription {{ $subscription->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2, h3, h4, h5 {
            margin: 0 0 10px 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-height: 60px;
            margin-bottom: 10px;
        }
        .header h2 {
            color: #007bff;
            font-size: 22px;
        }
        .info-section p {
            margin: 4px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            color: #fff;
        }
        .status.success { background-color: #28a745; }
        .status.warning { background-color: #ffc107; color: #333; }
        .status.danger { background-color: #dc3545; }
        .status.secondary { background-color: #6c757d; }
        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(config('app.logo'))
                <img src="{{ public_path(config('app.logo')) }}" alt="Logo">
            @endif
            <h2>Facture de souscription</h2>
            <p>Référence : #{{ $subscription->id }}</p>
        </div>

        <div class="info-section">
            <p><strong>Entreprise :</strong> {{ $subscription->tenant->name }}</p>
            <p><strong>Plan :</strong> {{ $subscription->plan->name }}</p>
            <p><strong>Prix :</strong> {{ number_format($subscription->plan->price, 0, ',', ' ') }} FCFA</p>
            <p><strong>Durée :</strong> {{ $subscription->plan->duration_days }} jours</p>
            @if($subscription->plan->max_users)
                <p><strong>Utilisateurs max :</strong> {{ $subscription->plan->max_users }}</p>
            @endif
            @if($subscription->plan->max_storage_mb)
                <p><strong>Stockage max :</strong> {{ $subscription->plan->max_storage_mb }} Mo</p>
            @endif
            @if($subscription->plan->description)
                <p><strong>Description :</strong><br>{!! nl2br(e($subscription->plan->description)) !!}</p>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>Montant payé</th>
                    <th>Méthode</th>
                    <th>Date de souscription</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $status = 'Inactive';
                    $statusClass = 'danger';
                    if ($subscription->ends_at < now()) {
                        $status = 'Expirée';
                        $statusClass = 'secondary';
                    } elseif ($subscription->is_active) {
                        $status = 'Active';
                        $statusClass = 'success';
                    } elseif ($subscription->ends_at > now() && !$subscription->is_active) {
                        $status = 'Bientôt active';
                        $statusClass = 'warning';
                    }
                @endphp
                <tr>
                    <td>{{ number_format($subscription->amount_paid, 0, ',', ' ') }} FCFA</td>
                    <td>{{ $subscription->payment_method ?? '-' }}</td>
                    <td>{{ $subscription->created_at->format('d/m/Y') }}</td>
                    <td><span class="status {{ $statusClass }}">{{ $status }}</span></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            Généré le {{ now()->format('d/m/Y à H:i') }} - {{ config('app.name') }}
        </div>
    </div>
</body>
</html>
