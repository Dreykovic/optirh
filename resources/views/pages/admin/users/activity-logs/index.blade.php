@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@section('admin-content')
    <div class="row clearfix g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Journal d'activités</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ route('activity-logs.index') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label for="action" class="form-label">Action</label>
                                    <select name="action" id="action" class="form-select">
                                        <option value="">Toutes les actions</option>
                                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>
                                            Création</option>
                                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>
                                            Modification</option>
                                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>
                                            Suppression</option>
                                        <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>
                                            Connexion</option>
                                        <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>
                                            Déconnexion</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="date_from" class="form-label">Date de début</label>
                                    <input type="text" class="form-control datepicker" id="date_from" name="date_from"
                                        value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="date_to" class="form-label">Date de fin</label>
                                    <input type="text" class="form-control datepicker" id="date_to" name="date_to"
                                        value="{{ request('date_to') }}">
                                </div>
                                @if (auth()->user()->hasRole('super-admin'))
                                    <div class="col-md-3">
                                        <label for="user_id" class="form-label">Utilisateur</label>
                                        <select name="user_id" id="user_id" class="form-select">
                                            <option value="">Tous les utilisateurs</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Filtrer</button>
                                    <a href="{{ route('activity-logs.index') }}"
                                        class="btn btn-outline-secondary">Réinitialiser</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date et heure</th>
                                    <th>Utilisateur</th>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th>Adresse IP</th>
                                    <th>Détails</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            @if ($log->user)
                                                {{ $log->user->name }}
                                            @else
                                                Utilisateur supprimé
                                            @endif
                                        </td>
                                        <td>
                                            @switch($log->action)
                                                @case('created')
                                                    <span class="badge bg-success">Création</span>
                                                @break

                                                @case('updated')
                                                    <span class="badge bg-info">Modification</span>
                                                @break

                                                @case('deleted')
                                                    <span class="badge bg-danger">Suppression</span>
                                                @break

                                                @case('login')
                                                    <span class="badge bg-primary">Connexion</span>
                                                @break

                                                @case('logout')
                                                    <span class="badge bg-secondary">Déconnexion</span>
                                                @break

                                                @default
                                                    <span class="badge bg-dark">{{ $log->action }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $log->description }}</td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td>
                                            @if ($log->old_values || $log->new_values)
                                                <button type="button" class="btn btn-sm btn-outline-info view-details"
                                                    data-bs-toggle="modal" data-bs-target="#logDetailsModal"
                                                    data-log-id="{{ $log->id }}"
                                                    data-old-values="{{ json_encode($log->old_values) }}"
                                                    data-new-values="{{ json_encode($log->new_values) }}">
                                                    Voir les modifications
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Aucune activité trouvée</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $logs->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal pour afficher les détails -->
        <div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logDetailsModalLabel">Détails des modifications</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Valeurs précédentes</h6>
                                <pre id="oldValues" class="bg-light p-3 rounded"></pre>
                            </div>
                            <div class="col-md-6">
                                <h6>Nouvelles valeurs</h6>
                                <pre id="newValues" class="bg-light p-3 rounded"></pre>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('plugins-js')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    @endpush

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialiser le datepicker
                flatpickr('.datepicker', {
                    locale: 'fr',
                    dateFormat: 'Y-m-d',
                    allowInput: true
                });

                // Gérer l'affichage des détails dans le modal
                document.querySelectorAll('.view-details').forEach(function(button) {
                    button.addEventListener('click', function() {
                        const oldValues = JSON.parse(this.getAttribute('data-old-values')) || {};
                        const newValues = JSON.parse(this.getAttribute('data-new-values')) || {};

                        document.getElementById('oldValues').textContent = JSON.stringify(oldValues,
                            null, 2);
                        document.getElementById('newValues').textContent = JSON.stringify(newValues,
                            null, 2);
                    });
                });
            });
        </script>
    @endpush
