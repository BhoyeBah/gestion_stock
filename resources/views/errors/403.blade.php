@extends('back.layouts.admin')

@section('title', 'Accès refusé')

@section('content')
<div class="vh-100 d-flex align-items-center justify-content-center bg-light-gradient">
  <div class="text-center w-100" style="max-width:640px;">
    <div class="card border-0 shadow-sm rounded-lg p-4">
      <div class="d-flex align-items-center justify-content-center mb-3">
        <div class="badge-icon mr-3">
          <!-- simple icon -->
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="11" width="18" height="10" rx="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            <line x1="3" y1="3" x2="21" y2="21"></line>
          </svg>
        </div>
        <div class="text-left">
          <h1 class="h1 mb-0 font-weight-bold text-warning">403</h1>
          <div class="text-muted small">Accès refusé</div>
        </div>
      </div>

      <p class="mb-4 lead text-muted">
        {{ $exception->getMessage() ?: "Vous n'avez pas la permission d'accéder à cette page." }}
      </p>

      <div class="d-flex justify-content-center">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mr-2">← Retour</a>
        <a href="{{ url('/') }}" class="btn btn-primary">Accueil</a>
      </div>

      <div class="mt-3 small text-muted">Besoin d'aide ? <a href="mailto:admin@example.com">Contactez l'administrateur</a>.</div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
/* fond discret et propre */
.bg-light-gradient {
  background: linear-gradient(180deg, #f7fbff 0%, #ffffff 100%);
}

/* badge icon */
.badge-icon {
  width: 64px;
  height: 64px;
  border-radius: 12px;
  display:flex;
  align-items:center;
  justify-content:center;
  background: linear-gradient(180deg, #fff8ef, #fff3e6);
  box-shadow: 0 6px 18px rgba(20,30,60,0.06);
  color: #f59e0b;
}

/* card */
.card {
  background: #ffffff;
  border-radius: 12px;
  padding: 28px;
}

/* titres */
.h1 {
  font-size: 2.6rem;
  line-height: 1;
}
@media (max-width:576px) {
  .h1 { font-size: 2rem; }
}

/* boutons */
.btn-primary {
  border-radius: 8px;
  padding: .5rem 1rem;
}
.btn-outline-secondary {
  border-radius: 8px;
  padding: .5rem 1rem;
}

/* texte */
.lead { font-size: 1rem; color: #6b7280; }

/* légère animation au survol */
.card:hover { transform: translateY(-4px); transition: transform .28s ease; }
</style>
@endpush
