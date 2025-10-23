<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $this->hasPermission('read_products');

        $products = Product::all();

        return view('back.products.index', compact('products'));

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
    public function store(ProductRequest $request)
    {

        $this->hasPermission('create_product');

        $data = $request->validated(); // Récupère les données validées

        // Vérifie s'il y a une image
        if ($request->hasFile('image')) {
            // Stocke l'image dans storage/app/public/products et récupère le chemin
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Création du produit
        $product = Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', "Le produit « {$product->name} » a été ajouté avec succès !");
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
        return view('back.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $this->hasPermission("edit_product");
        return view('back.products.edit', compact('product'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {

        $this->hasPermission("update_product");

        // Récupère les données validées
        $data = $request->validated();

        // Vérifie s'il y a une nouvelle image
        if ($request->hasFile('image')) {
            // Supprime l'ancienne image si elle existe
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Stocke la nouvelle image
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Met à jour le produit
        $product->update($data);

        return redirect()->route('products.index')
                        ->with('success', "Le produit « {$product->name} » a été modifié avec succès !");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->hasPermission("delete_product");

        // Vérifie si le produit est désactivé
        if ($product->is_active) {
            return back()->with('error', "Impossible de supprimer un produit actif. Désactivez-le d'abord.");
        }

        // Supprime l'image si elle existe
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Supprime le produit
        $product->delete();

        return back()->with('success', 'Le produit a été supprimé avec succès !');
    }

    public function toggleActive(string $id)
    {
        $this->hasPermission("toggle_product");
        // Inversion du statut
        $product = Product::findOrFail($id);
        $product->is_active = ! $product->is_active;
        $product->save();

        // Message de succès
        $message = $product->is_active
            ? 'Le produit a été activé avec succès.'
            : 'Le produit a été désactivé avec succès.';

        // Redirection vers la liste
        return redirect()->back()->with('success', $message);
    }

    private function hasPermission(String $permission) {

        if(!auth()->user()->can($permission)) {
            abort(403, "Vous n'avez pas l'autorisation d'effectuer cette action");
        }

    }


}
