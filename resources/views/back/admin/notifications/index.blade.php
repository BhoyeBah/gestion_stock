@extends('back.layouts.admin')

@section('content')
<div class="container">
    <h2>Historique des Notifications</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Message</th>
                <th>Lien</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notifications as $notif)
                <tr>
                    <td>{{ $notif->data['title'] ?? '' }}</td>
                    <td>{{ $notif->data['message'] ?? '' }}</td>
                    <td>
                        @if(!empty($notif->data['url']))
                            <a href="{{ $notif->data['url'] }}" target="_blank">Ouvrir</a>
                        @endif
                    </td>
                    <td>{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Aucune notification</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $notifications->links() }}
</div>
@endsection
