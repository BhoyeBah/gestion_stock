<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Affiche la liste des clients.
     */
    public function index()
    {
        $clients = Client::all();
        return view('back.clients.index', compact('clients'));
    }

    /**
     * Formulaire pour créer un nouveau client.
     */
    public function create()
    {
        // Optionnel si modal utilisé
    }

    /**
     * Enregistre un nouveau client.
     */
    public function store(ClientRequest $request)
    {
        Client::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        return redirect()->route('clients.index')
            ->with('success', 'Client ajouté avec succès.');
    }

    /**
     * Affiche les détails d’un client.
     */
    public function show(Client $client)
    {
        return view('back.clients.show', compact('client'));
    }

    /**
     * Formulaire d’édition d’un client.
     */
    public function edit(Client $client)
    {
        return view('back.clients.edit', compact('client'));
    }

    /**
     * Met à jour un client existant.
     */
    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->validated());

        return redirect()->route('clients.index')
            ->with('success', "Les informations du client « {$client->full_name} » ont été mises à jour avec succès !");
    }

    /**
     * Supprime un client (uniquement si désactivé).
     */
    public function destroy(Client $client)
    {
        if ($client->is_active) {
            return back()->with('error', 'Impossible de supprimer un client actif. Veuillez le désactiver d’abord.');
        }

        $client->delete();

        return back()->with('success', 'Client supprimé avec succès.');
    }

    /**
     * Active / désactive un client.
     */
    public function toggleActive(string $id)
    {
        $client = Client::findOrFail($id);
        $client->is_active = !$client->is_active;
        $client->save();

        $message = $client->is_active
            ? 'Le client a été activé avec succès.'
            : 'Le client a été désactivé avec succès.';

        return redirect()->back()->with('success', $message);
    }
}
