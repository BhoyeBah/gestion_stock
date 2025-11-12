<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Units;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->hasPermission('read_products');


        $query = Product::select('products.*')
            ->addSelect([
                'stock_total' => \DB::table('batches')
                    ->selectRaw('SUM(remaining)')
                    ->whereColumn('batches.product_id', 'products.id'),
            ])
            ->with(['category', 'unit']);

        if ($search_name = $request->input('search_name')) {
            $query->where('name', 'like', "%{$search_name}%");
        }

        if ($category_id = $request->input('category_id')) {
            $query->where('category_id', $category_id);
        }

        if ($status = $request->input('status')) {
            $query->where('is_active', $status === 'active');
        }

        $products = $query->paginate(10);

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
        // dd($request);

        $this->hasPermission('create_products');

        $data = $request->validated(); // Récupère les données validées
        $data['is_perishable'] = $request->has('is_perishable') ? true : false;

        // Vérifie s'il y a une image
        if ($request->hasFile('image')) {
            // Stocke l'image dans storage/app/public/products et récupère le chemin
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Création du produit
        $product = Product::create($data);

        return back()->with('success', "Le produit « {$product->name} » a été ajouté avec succès !");
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        // Charger le produit avec ses relations utiles
        $product = Product::with([
            'batches.warehouse',
            'invoices.contact',
            'movement.invoice.contact',
            'invoiceItems.invoice.contact', // pour stats factures / ventes
        ])->findOrFail($id);

        // Calcul des stats rapides
        $totalIn = $product->invoiceItems->where('type', 'in')->sum('quantity');
        $totalOut = $product->invoiceItems->where('type', 'out')->sum('quantity');
        $totalValueSold = $product->invoiceItems->where('type', 'out')->sum(fn ($item) => $item->quantity * $item->unit_price - $item->discount);
        $totalValueIn = $product->invoiceItems->where('type', 'in')->sum(fn ($item) => $item->quantity * $item->unit_price - $item->discount);
        $averagePriceOut = $product->invoiceItems->where('type', 'out')->avg('unit_price');
        $averagePriceIn = $product->invoiceItems->where('type', 'in')->avg('unit_price');
        $expiredQuantity = $product->batches->where('expiration_date', '<', now())->sum('quantity');
        $totalDiscount = $product->invoiceItems->where('type', 'out')->sum('discount');

        return view('back.products.show', compact('product', 'totalIn', 'totalOut', 'totalValueSold'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // 1. Vérification des permissions de l'utilisateur
        $this->hasPermission('update_product');

        // 2. Récupération des données nécessaires pour les listes déroulantes du formulaire.
        //    Le tri par nom ('orderBy') garantit un affichage alphabétique pratique pour l'utilisateur.
        $categories = Category::orderBy('name')->get();
        $units = Units::orderBy('name')->get();

        // 3. Retour de la vue 'edit' en lui passant le produit à modifier
        //    ainsi que les collections de catégories et d'unités.
        return view('back.products.edit', compact(
            'product',
            'categories',
            'units'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {

        $this->hasPermission('update_product');

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
        $this->hasPermission('delete_product');

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
        $this->hasPermission('toggle_product');
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

    private function hasPermission(string $permission)
    {

        if (! auth()->user()->can($permission)) {
            abort(403, "Vous n'avez pas l'autorisation d'effectuer cette action");
        }

    }
}
