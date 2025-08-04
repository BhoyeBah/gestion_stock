@php
    $user = auth()->user();
@endphp

@extends('back.layouts.admin')
@section('content')
<!-- En-tête de page -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📝 Journal d'activités</h1>
</div>

<!-- Liste des activités -->
<div class="card shadow border-left-info">
    <div class="card-header bg-info text-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Historique des activités</h6>
    </div>

    <div class="card-body">
        @if($activities->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle text-sm">
                    <thead class="thead-light text-uppercase text-secondary">
                        <tr>
                            <th>#</th>
                            <th>Action</th>
                            <th>Description</th>
                            @canany(['read_activities', 'read_all_activities'])
                            <th>Utilisateur</th>
                            @endcan
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach($activities as $activity)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $activity->action }}</strong></td>
                                <td>{{ $activity->description ?? '-' }}</td>

                                @canany(['read_activities', 'read_all_activities'])
                                <td>

                                        @if($activity->user->is_platform_user() && $activity->user->is_owner)
                                            {{ $activity->user->name }}
                                        @else
                                        <a href="{{ route('users.edit', $activity->user_id) }}" class="text-primary fw-bold">
                                            {{ $activity->user->name }}
                                        </a>
                                        @endif
                                    
                                    
                                </td>
                                @endcanany

                                <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $activities->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Aucune activité enregistrée pour le moment.
            </div>
        @endif
    </div>
</div>
@endsection
