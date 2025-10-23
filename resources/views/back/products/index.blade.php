@extends('back.layouts.admin')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tags"></i> Produits
        </h1>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addproductModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau produit
        </button>
    </div>

    <div class="card shadow border-left-primary">
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-list-ul"></i> Liste des produits disponibles</h6>
        </div>

        <div class="card-body">
            @if ($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="thead-light text-uppercase text-secondary small">
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Prix de vente</th>
                                <th>Seuil d'alerte</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong class="text-dark">{{ $product->name }}</strong></td>
                                    <td><strong class="text-dark">{{ $product->category->name }}</strong></td>
                                    <td><strong class="text-dark">{{ number_format($product->price, 0, ',', ' ') }} CFA</strong></td>
                                    <td><strong class="text-dark">{{ $product->seuil_alert }}</strong></td>

                                    <td>
                                        @if ($product->is_active)
                                            <span class="badge badge-success">Activé</span>
                                        @else
                                            <span class="badge badge-danger">Désactivé</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <!-- Bouton activer/désactiver -->
                                        <form action="{{ route('products.toggle', $product->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Voulez-vous {{ $product->is_active ? 'désactiver' : 'activer' }} ce produit ?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $product->is_active ? 'btn-success' : 'btn-danger' }}"
                                                title="{{ $product->is_active ? 'Désactiver' : 'Activer' }}">
                                                <i class="fas fa-toggle-{{ $product->is_active ? 'off' : 'on' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Bouton modifier -->
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning"
                                            title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                         <!-- Bouton modifier -->
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-info"
                                            title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Bouton supprimer -->
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Confirmer la suppression de ce produit ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Aucun produit disponible pour le moment.
                    <br>
                    <small>Créez-en un en cliquant sur le bouton ci-dessus.</small>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal ajout d'un produit -->
    <div class="modal fade" id="addproductModal" tabindex="-1" role="dialog" aria-labelledby="addproductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                @include('back.products._form', [
                    'route' => route('products.store'),
                    'method' => 'POST',
                    'product' => new \App\Models\Product(),
                    'categories' => \App\Models\Category::all(),
                    'units' => \App\Models\Units::all(),
                ])
            </div>
        </div>
    </div>

@endsection
