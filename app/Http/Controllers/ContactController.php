<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $type)
    {
        //
        $this->validateType($type);

        $contacts = Contact::type(rtrim($type, 's'))->get();
        // Définir un titre dynamique

        $contactType = $type === 'clients' ? 'Clients' : 'Fournisseurs';

        return view('back.contacts.index', compact('contacts', 'type', 'contactType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request)
    {
        //
        Contact::create($request->validated());
        $contactType = $request->type === 'client' ? 'Client' : 'Fournisseur';

        return back()->with('success', "Le $contactType a été enrégistré avec success");

    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact, string $type)
    {
        //
        $this->checkAuthorization($contact, $type);
        return view("back.contacts.show", compact("contact", "type"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact, string $type)
    {
        //
        $this->checkAuthorization($contact, $type);

        return view('back.contacts.edit', compact('contact', 'type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $request, Contact $contact, string $type)
    {

        $this->checkAuthorization($contact, $type);

        $data = $request->validated();
        $contact->update($data);

        // Redirige avec un message de succès
        return redirect()->route($type.'.index')
            ->with('success', 'Contact mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact, string $type)
    {
        //
        $this->checkAuthorization($contact, $type);

         if ($contact->is_active) {
            return back()->with('error', 'Impossible de supprimer un contact actif. Veuillez le désactiver d’abord.');
        }
        $contact->delete();
        return back()->with("success", "Contact supprimé avec succès");
    }

    public function validateType(string $type): void
    {
        if (! in_array($type, ['clients', 'suppliers'])) {
            abort(404, 'Page inexistante');
        }
    }

    /**
     * Active / désactive un client.
     */
    public function toggleActive(string $id, string $type)
    {
        $contactType = $type === 'clients' ? 'Client' : 'Fournisseur';

        $client = Contact::findOrFail($id);
        $client->is_active = ! $client->is_active;
        $client->save();

        $message = $client->is_active
            ? "Le $contactType a été activé avec succès."
            : "Le $contactType a été désactivé avec succès.";

        return redirect()->back()->with('success', $message);
    }

    public function checkAuthorization(Contact $contact, string $type)
    {
        if ($contact->type !== rtrim($type, 's')) {
            abort(403, "Vous n'êtes pas autorisé à effectuer cette opération.");
        }

    }
}
