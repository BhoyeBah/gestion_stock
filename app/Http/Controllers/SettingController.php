<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $tenantId = auth()->user()->tenant_id ?? null;
        $setting = Setting::where('tenant_id', $tenantId)->first();

        return view('back.settings.settings', compact('setting'));
    }

    /**
     * Création (ou mise à jour si déjà existant) — évite les doublons.
     */
    public function store(Request $request)
    {
        $tenantId = auth()->user()->tenant_id ?? null;

        $validated = $request->validate([
            'currency' => 'required|in:XOF,GNF,FCFA,GMD,LE',
            'tva' => 'required|numeric|min:0|max:100',
        ]);

        // updateOrCreate évite la création de plusieurs settings pour le même tenant
        Setting::updateOrCreate(
            ['tenant_id' => $tenantId],
            [
                'currency' => $validated['currency'],
                'tva' => $validated['tva'],
            ]
        );

        return redirect()->route('settings.index')->with('success', 'Configuration enregistrée avec succès.');
    }

    /**
     * Mise à jour : on vérifie que le setting appartient bien au tenant connecté.
     */
    public function update(Request $request, Setting $setting)
    {
        $tenantId = auth()->user()->tenant_id ?? null;

        // Sécurité : empêcher un tenant d'éditer le setting d'un autre tenant
        if ($setting->tenant_id !== $tenantId) {
            abort(403, "Action non autorisée.");
        }

        $validated = $request->validate([
            'currency' => 'required|in:XOF,GNF,FCFA,GMD,LE',
            'tva' => 'required|numeric|min:0|max:100',
        ]);

        $setting->update($validated);

        return redirect()->route('settings.index')->with('success', 'Paramètres mis à jour avec succès.');
    }
}
