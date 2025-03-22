<!-- resources/views/pages/admin/activity-logs/index.blade.php -->
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
                                <!-- Filtre par groupe d'action -->
                                <div class="col-md-3">
                                    <label for="action_group" class="form-label">Type d'action</label>
                                    <select name="action_group" id="action_group" class="form-select">
                                        @foreach (App\Config\ActivityLogActions::getAllGroups() as $groupCode => $group)
                                            <option value="{{ $groupCode }}"
                                                {{ request('action_group') == $groupCode ? 'selected' : '' }}>
                                                {{ $group['display'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filtre par action spécifique (s'affiche en fonction du groupe sélectionné) -->
                                <div class="col-md-3">
                                    <label for="action" class="form-label">Action spécifique</label>
                                    <select name="action" id="action" class="form-select">
                                        <option value="">Toutes les actions</option>
                                        @foreach (App\Config\ActivityLogActions::ACTIONS as $actionCode => $action)
                                            <option value="{{ $actionCode }}" data-group="{{ $action['group'] }}"
                                                {{ request('action') == $actionCode ? 'selected' : '' }}>
                                                {{ $action['display'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filtre par utilisateur (pour les admin) -->
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

                                <!-- Filtre par date -->
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

                                <!-- Filtre par modèle/entité -->
                                <div class="col-md-3">
                                    <label for="model_type" class="form-label">Type d'entité</label>
                                    <select name="model_type" id="model_type" class="form-select">
                                        <option value="">Toutes les entités</option>
                                        @foreach ($modelTypes as $modelType)
                                            <option value="{{ $modelType }}"
                                                {{ request('model_type') == $modelType ? 'selected' : '' }}>
                                                {{ class_basename($modelType) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Boutons de filtre -->
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
                                    <th width="160">Date et heure</th>
                                    <th width="180">Utilisateur</th>
                                    <th width="130">Action</th>
                                    <th>Description</th>
                                    <th width="120">Entité</th>
                                    <th width="120">ID</th>
                                    <th width="100">Adresse IP</th>
                                    <th width="80">Détails</th>
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
                                            <x-activity-log-badge :action="$log->action" />
                                        </td>
                                        <td>{{ $log->description }}</td>
                                        <td>
                                            @if ($log->model_type)
                                                {{ class_basename($log->model_type) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $log->model_id ?? '-' }}</td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td>
                                            @if ($log->additional_data)
                                                <button type="button" class="btn btn-sm btn-outline-info view-details"
                                                    data-bs-toggle="modal" data-bs-target="#logDetailsModal"
                                                    data-log-id="{{ $log->id }}"
                                                    data-additional-data="{{ $log->additional_data }}">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Aucune activité trouvée</td>
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
                    <h5 class="modal-title" id="logDetailsModalLabel">Détails de l'activité</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h6>Données additionnelles</h6>
                            <pre id="additionalData" class="bg-light p-3 rounded"></pre>
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
                    const additionalData = JSON.parse(this.getAttribute('data-additional-data') ||
                        '{}');
                    document.getElementById('additionalData').textContent = JSON.stringify(
                        additionalData, null, 2);
                });
            });

            // Filtrer les actions en fonction du groupe sélectionné
            const actionGroupSelect = document.getElementById('action_group');
            const actionSelect = document.getElementById('action');

            if (actionGroupSelect && actionSelect) {
                actionGroupSelect.addEventListener('change', function() {
                    const selectedGroup = this.value;

                    // Cacher toutes les options d'action
                    Array.from(actionSelect.options).forEach(option => {
                        if (option.value === '') {
                            // Toujours afficher l'option "Toutes les actions"
                            option.style.display = '';
                        } else {
                            const actionGroup = option.getAttribute('data-group');
                            option.style.display = (selectedGroup === 'all' || actionGroup ===
                                selectedGroup) ? '' : 'none';
                        }
                    });

                    // Réinitialiser la sélection si l'option actuelle n'est plus visible
                    const currentOption = actionSelect.options[actionSelect.selectedIndex];
                    if (currentOption.style.display === 'none') {
                        actionSelect.value = '';
                    }
                });

                // Déclencher l'événement au chargement de la page
                actionGroupSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endpush
