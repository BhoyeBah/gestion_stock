@extends('back.layouts.admin')

@section('content')
<div class="container">
    <h2>Envoyer une Notification</h2>

    <form action="{{ route('admin.notifications.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Titre</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Message</label>
            <textarea name="message" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Lien (URL)</label>
            <input type="url" name="url" class="form-control">
        </div>

        <div class="mb-3">
            <label>Cible</label>
            <select name="target_type" id="target_type" class="form-control" required>
                <option value="global">Tous les tenants</option>
                <option value="tenant">Tenant spécifique</option>
                <option value="user">Utilisateur spécifique</option>
            </select>
        </div>

        <div class="mb-3" id="tenant_select" style="display:none;">
            <label>Tenant</label>
            <select name="target_id" class="form-control">
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3" id="user_select" style="display:none;">
            <label>Utilisateur</label>
            <select name="target_id" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Envoyer</button>
    </form>
</div>

<script>
document.getElementById('target_type').addEventListener('change', function () {
    document.getElementById('tenant_select').style.display = this.value === 'tenant' ? 'block' : 'none';
    document.getElementById('user_select').style.display = this.value === 'user' ? 'block' : 'none';
});
</script>
@endsection
