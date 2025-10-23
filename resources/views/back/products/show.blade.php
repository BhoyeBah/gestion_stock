@extends('back.layouts.admin')

@section('content')
    <div class="container">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-box-open"></i> Détails du produit
            </h1>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <div class="card shadow border-left-primary mb-4">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle"></i> Informations sur le produit</h6>
                @if ($product->is_active)
                    <span class="badge badge-success">Activé</span>
                @else
                    <span class="badge badge-danger">Désactivé</span>
                @endif
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Image du produit -->
                    <div class="col-md-4 text-center mb-3">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Image du produit"
                                class="img-fluid rounded shadow-sm" style="max-height: 250px;">
                        @else
                            <img src="{{ asset('images/default-product.png') }}" alt="Image par défaut"
                                class="img-fluid rounded shadow-sm" style="max-height: 250px;">
                        @endif
                    </div>

                    <!-- Détails du produit -->
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Nom :</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Catégorie :</th>
                                    <td>{{ $product->category->name ?? 'Non défini' }}</td>
                                </tr>
                                <tr>
                                    <th>Unité de mesure :</th>
                                    <td>{{ $product->unit->name ?? 'Non défini' }}</td>
                                </tr>
                                <tr>
                                    <th>Prix de vente :</th>
                                    <td>{{ number_format($product->price, 0, ',', ' ') }} CFA</td>
                                </tr>
                                <tr>
                                    <th>Seuil d'alerte :</th>
                                    <td>{{ $product->seuil_alert }}</td>
                                </tr>
                                <tr>
                                    <th>Description :</th>
                                    <td>{{ $product->description ?? 'Aucune description fournie.' }}</td>
                                </tr>
                                <tr>
                                    <th>Date de création :</th>
                                    <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Dernière modification :</th>
                                    <td>{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning mr-2">
                                <i class="fas fa-edit"></i> Modifier
                            </a>

                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                onsubmit="return confirm('Confirmer la suppression de ce produit ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
