<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SubscriptionController extends Controller
{
    /**
     * Liste des souscriptions du tenant connecté
     */
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;

        $subscriptions = Subscription::where('tenant_id', $tenant->id)
            ->orderByDesc('starts_at')
            ->paginate(9);

        return view('tenant.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Affiche le détail d'une souscription
     */
    public function show(Subscription $subscription, Request $request)
    {
        // Vérifier que la souscription appartient au tenant
        abort_unless($subscription->tenant_id === $request->user()->tenant_id, 403);

        return view('tenant.subscriptions.show', compact('subscription'));
    }

    /**
     * Génère et télécharge le PDF de la souscription
     */
    public function pdf(Subscription $subscription, Request $request)
    {
        // Vérification de l'accès
        if(!Auth::user()->is_platform_user()){
            abort_unless($subscription->tenant_id === $request->user()->tenant_id, 403);
        }

        $pdf = Pdf::loadView('tenant.subscriptions.pdf', compact('subscription'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('Souscription-' . $subscription->id . '.pdf');
    }
}
