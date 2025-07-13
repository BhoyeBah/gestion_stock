<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('tenant', 'plan')->latest()->paginate(50);
        return view('back.admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $tenants = Tenant::orderBy('name')->get();
        $plans = Plan::orderBy('price')->get();

        return view('back.admin.subscriptions.create', compact('tenants', 'plans'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active')
        ]);

        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'plan_id' => 'required|exists:plans,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);

        try {
            Subscription::create([
                'tenant_id' => $request->tenant_id,
                'plan_id' => $request->plan_id,
                'amount_paid' => $request->amount_paid,
                'payment_method' => $request->payment_method,
                'starts_at' => $request->starts_at,
                'ends_at' => $request->ends_at,
                
            ]);

            return redirect()->route('admin.subscriptions.index')->with('success', 'Souscription enregistrée avec succès.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Erreur lors de l’enregistrement.')->withInput();
        }
    }

    public function edit(Subscription $subscription)
    {
        $tenants = Tenant::orderBy('name')->get();
        $plans = Plan::orderBy('price')->get();

        return view('back.admin.subscriptions.edit', compact('subscription', 'tenants', 'plans'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        if ($subscription->ends_at < now()) {
            return back()->with('error', 'Impossible de modifier une souscription expirée.');
        }

        $request->merge([
            'is_active' => $request->has('is_active')
        ]);

        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'plan_id' => 'required|exists:plans,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after_or_equal:starts_at',
            
        ]);

        try {
            $subscription->update([
                'tenant_id' => $request->tenant_id,
                'plan_id' => $request->plan_id,
                'amount_paid' => $request->amount_paid,
                'payment_method' => $request->payment_method,
                'starts_at' => $request->starts_at,
                'ends_at' => $request->ends_at,
                'is_active' => $request->is_active,
            ]);

            return redirect()->route('admin.subscriptions.index')->with('success', 'Souscription mise à jour avec succès.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Erreur lors de la mise à jour.')->withInput();
        }
    }

    public function toggleActive(Subscription $subscription)
    {
        if ($subscription->ends_at < now()) {
            $subscription->is_active = false;
            $subscription->save();
            return back()->with('error', 'Impossible d’activer une souscription expirée.');
        }

        DB::beginTransaction();

        try {
            if (!$subscription->is_active) {
                $hasActive = Subscription::where('tenant_id', $subscription->tenant_id)
                    ->where('id', '!=', $subscription->id)
                    ->where('is_active', true)
                    ->where('ends_at', '>=', now())
                    ->exists();

                if ($hasActive) {
                    return back()->with('error', 'Ce tenant a déjà une souscription active.');
                }
            }

            $subscription->is_active = !$subscription->is_active;
            $subscription->save();

            DB::commit();

            return redirect()->route('admin.subscriptions.index')
                ->with('success', 'Statut de la souscription mis à jour avec succès.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Erreur lors de l’activation/désactivation de la souscription.');
        }
    }

    public function show(Subscription $subscription)
    {
        return view('back.admin.subscriptions.show', compact('subscription'));
    }


    public function destroy(Subscription $subscription)
    {
        if ($subscription->ends_at < now()) {
            return back()->with('error', 'Impossible de supprimer une souscription expirée.');
        }

        try {
            $subscription->delete();
            return redirect()->route('admin.subscriptions.index')->with('success', 'Souscription supprimée.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Erreur lors de la suppression.');
        }
    }
}
