@extends('back.layouts.admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Créer une nouvelle entreprise</h1>
    <a href="{{ route('admin.tenants.index') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<form action="{{ route('admin.tenants.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- 1. Informations du Tenant -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">
            <strong>🧾 Informations de l’entreprise</strong>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Nom *</label>
                <input type="text" name="tenant[name]" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Identifiant (slug) *</label>
                <input type="text" name="tenant[slug]" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="tenant[email]" class="form-control">
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="text" name="tenant[phone]" class="form-control">
            </div>

             <div class="form-group">
                <label>Adresse</label>
                <input type="text" name="tenant[address]" class="form-control">
            </div>

            <div class="form-group">
                <label>Ninea</label>
                <input type="text" name="tenant[ninea]" class="form-control">
            </div>

            <div class="form-group">
                <label>Rc</label>
                <input type="text" name="tenant[rc]" class="form-control">
            </div>
            <div class="form-group">
                <label>Logo</label>
                <input type="file" name="tenant[logo]" class="form-control-file">
            </div>
        </div>
    </div>

    <!-- 2. Premier utilisateur -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-success text-white">
            <strong>👨‍💼 Premier utilisateur (propriétaire)</strong>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Nom *</label>
                <input type="text" name="user[name]" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="user[email]" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="text" name="user[phone]" class="form-control">
            </div>
            <div class="form-group">
                <label>Mot de passe *</label>
                <div class="input-group">
                    <input type="password" name="user[password]" id="password" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" id="generatePassword">
                            Générer
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Confirmer mot de passe *</label>
                <input type="password" name="user[password_confirmation]" id="password_confirmation" class="form-control" required>
            </div>

        </div>
    </div>

    <!-- Bouton -->
    <div class="text-right">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Enregistrer l’entreprise
        </button>
    </div>
</form>
@endsection


@push('scripts')
<script>
    document.getElementById('generatePassword').addEventListener('click', function () {
        const length = 12;
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$_";
        let password = "";
        for (let i = 0, n = charset.length; i < length; ++i) {
            password += charset.charAt(Math.floor(Math.random() * n));
        }

        document.getElementById('password').value = password;
        document.getElementById('password_confirmation').value = password;
    });
</script>
@endpush
