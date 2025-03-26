@extends('modules.opti-hr.pages.base')
@section('admin-content')
    <div class="body d-flex py-lg-3 py-md-2">
        <div class="container-xxl">
            <!-- Page Title -->
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h1 class="fw-bold mb-0">Tableau de bord</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
                        </ol>
                    </nav>
                </div>
                <div class="col">
                    <button id="refreshDashboard" class="btn btn-sm btn-outline-primary float-end" data-bs-toggle="tooltip"
                        title="Actualiser le tableau de bord">
                        <i class="fas fa-sync-alt"></i> Actualiser
                    </button>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Actions rapides</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <a href="{{ route('membres.pages') }}"
                                        class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                        <i class="fas fa-user-plus"></i> Ajouter un employé
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('directions') }}"
                                        class="btn btn-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                                        <i class="fas fa-building"></i> Ajouter un département
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('absences.create') }}"
                                        class="btn btn-info w-100 d-flex align-items-center justify-content-center gap-2 text-white">
                                        <i class="fas fa-calendar-plus"></i> Enregistrer une absence
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('publications.config.index') }}"
                                        class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2">
                                        <i class="fas fa-file-alt"></i> Nouvelle publication
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <span class="avatar rounded-circle bg-primary bg-opacity-10 p-3">
                                        <i class="fas fa-users text-primary"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Total des employés</h6>
                                    <h2 class="mb-0">{{ $totalEmployees }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <span class="avatar rounded-circle bg-success bg-opacity-10 p-3">
                                        <i class="fas fa-building text-success"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Départements</h6>
                                    <h2 class="mb-0">{{ $totalDepartments }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <span class="avatar rounded-circle bg-warning bg-opacity-10 p-3">
                                        <i class="fas fa-calendar-times text-warning"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Absences en attente</h6>
                                    <h2 class="mb-0">{{ $pendingAbsences }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <span class="avatar rounded-circle bg-info bg-opacity-10 p-3">
                                        <i class="fas fa-file-alt text-info"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Demandes de documents</h6>
                                    <h2 class="mb-0">{{ $pendingDocuments }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Répartition des employés</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="departmentChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Répartition par genre</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="genderChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Absences -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Demandes d'absence récentes</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Employé</th>
                                            <th>Type</th>
                                            <th>Du</th>
                                            <th>Au</th>
                                            <th>Jours</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentAbsences as $absence)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">


                                                        <x-employee-icon :employee="$absence->duty->employee" />
                                                        <div>{{ $absence->duty->employee->first_name }}
                                                            {{ $absence->duty->employee->last_name }}</div>
                                                    </div>
                                                </td>
                                                <td>{{ $absence->absence_type->label ?? 'N/A' }}</td>
                                                <td>{{ $absence->start_date->format('d M Y') }}</td>
                                                <td>{{ $absence->end_date->format('d M Y') }}</td>
                                                <td>{{ $absence->requested_days }}</td>
                                                <td>
                                                    @if ($absence->stage == 'PENDING')
                                                        <span class="badge bg-warning">En attente</span>
                                                    @elseif($absence->stage == 'APPROVED')
                                                        <span class="badge bg-success">Approuvée</span>
                                                    @elseif($absence->stage == 'REJECTED')
                                                        <span class="badge bg-danger">Rejetée</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $absence->stage }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @include('modules.opti-hr.pages.attendances.absences.actions')


                                                </td>
                                                @include('modules.opti-hr.pages.attendances.absences.request.comment')
                                                @include('modules.opti-hr.pages.attendances.absences.details')
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune demande d'absence récente
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('absences.requests') }}" class="btn btn-sm btn-primary">Voir toutes
                                les absences</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Absence Calendar and Documents -->
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Calendrier des absences</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="showAllAbsences">
                                <label class="form-check-label" for="showAllAbsences">Afficher tout</label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="absenceCalendar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Documents récents</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @forelse($recentDocuments as $document)
                                    <a href="{{ route('documents.requests', ['status' => 'all', 'search', $document->duty->employee->last_mane]) }}"
                                        class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $document->document_type->label ?? 'N/A' }}</h6>
                                            <small>{{ $document->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">{{ $document->duty->employee->first_name }}
                                            {{ $document->duty->employee->last_name }}</p>
                                        <small class="text-muted">
                                            @if ($document->stage == 'PENDING')
                                                <span class="badge bg-secondary">En attente</span>
                                            @elseif($document->stage == 'APPROVED')
                                                <span class="badge bg-success">Approuvé</span>
                                            @elseif($document->stage == 'REJECTED')
                                                <span class="badge bg-danger">Rejeté</span>
                                            @elseif($document->stage == 'IN_PROGRESS')
                                                <span class="badge bg-warning">En cours de traitement</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $document->stage }}</span>
                                            @endif
                                        </small>
                                    </a>
                                @empty
                                    <div class="text-center py-3">
                                        <p class="mb-0">Aucune demande de document récente</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('documents.requests', 'ALL') }}" class="btn btn-sm btn-primary">Voir
                                tous les documents</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Publications -->
            <div class="row g-3 mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Publications récentes</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @forelse($recentPublications as $publication)
                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $publication->title }}</h5>
                                                <p class="card-text">{{ Str::limit($publication->content, 100) }}</p>
                                            </div>
                                            <div class="card-footer">
                                                <small class="text-muted">Publié
                                                    {{ $publication->published_at->diffForHumans() }} par
                                                    {{ $publication->author->username }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <p class="mb-0">Aucune publication récente</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('publications.config.index') }}" class="btn btn-sm btn-primary">Voir
                                toutes les publications</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Toast Notification -->
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="refreshToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Tableau de bord</strong>
                        <small>À l'instant</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fermer"></button>
                    </div>
                    <div class="toast-body">
                        Les données du tableau de bord ont été actualisées.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugins-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet">
@endpush

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialisation du graphique de répartition par département
            const departmentChart = document.getElementById('departmentChart');
            if (departmentChart) {
                new Chart(departmentChart, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($departmentLabels) !!},
                        datasets: [{
                            data: {!! json_encode($departmentData) !!},
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)',
                                'rgba(255, 159, 64, 0.7)',
                                'rgba(199, 199, 199, 0.7)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12
                                }
                            }
                        }
                    }
                });
            }

            // Initialisation du graphique de répartition par genre
            const genderChart = document.getElementById('genderChart');
            if (genderChart) {
                new Chart(genderChart, {
                    type: 'pie',
                    data: {
                        labels: ['Femme', 'Homme'],
                        datasets: [{
                            data: [{{ $femaleCount ?? 0 }}, {{ $maleCount ?? 0 }}],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12
                                }
                            }
                        }
                    }
                });
            }

            // Initialisation du calendrier des absences
            const calendarEl = document.getElementById('absenceCalendar');
            let calendar;

            if (calendarEl) {
                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'fr',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    buttonText: {
                        today: "Aujourd'hui",
                        month: 'Mois',
                        week: 'Semaine',
                        list: 'Liste'
                    },
                    events: {!! json_encode($calendarEvents) !!},
                    eventClick: function(info) {
                        window.location.href = `attendances/absences/requests`;
                    },
                    eventDidMount: function(info) {
                        $(info.el).tooltip({
                            title: info.event.extendedProps.description,
                            placement: 'top',
                            trigger: 'hover',
                            container: 'body'
                        });
                    }
                });
                calendar.render();

                // Basculement pour afficher toutes les absences
                document.getElementById('showAllAbsences').addEventListener('change', function() {
                    if (this.checked) {
                        // Afficher toutes les absences
                        calendar.removeAllEvents();
                        calendar.addEventSource({!! json_encode($allCalendarEvents) !!});
                    } else {
                        // Afficher uniquement les absences approuvées
                        calendar.removeAllEvents();
                        calendar.addEventSource({!! json_encode($calendarEvents) !!});
                    }
                });
            }

            // Fonction pour actualiser les données du calendrier
            function refreshCalendarData(showAll = false) {
                $.ajax({
                    url: "{{ route('opti-hr.dashboard.absence-calendar') }}",
                    type: "GET",
                    data: {
                        showAll: showAll
                    },
                    success: function(response) {
                        // Supprimer tous les événements et ajouter les nouveaux
                        calendar.removeAllEvents();
                        calendar.addEventSource(response);
                    },
                    error: function(error) {
                        console.error("Erreur lors de la récupération des données du calendrier:",
                            error);
                    }
                });
            }

            // Fonction pour actualiser les statistiques des employés
            function refreshEmployeeStats() {
                $.ajax({
                    url: "{{ route('opti-hr.dashboard.employee-stats') }}",
                    type: "GET",
                    success: function(response) {
                        // Mettre à jour les graphiques avec les nouvelles données
                        updateDepartmentChart(response.departmentDistribution);
                        updateGenderChart(response.genderDistribution);
                    },
                    error: function(error) {
                        console.error("Erreur lors de la récupération des statistiques des employés:",
                            error);
                    }
                });
            }

            // Fonction pour mettre à jour le graphique des départements
            function updateDepartmentChart(data) {
                // Extraire les étiquettes et les valeurs
                const labels = data.map(item => item.name);
                const values = data.map(item => item.count);

                // Mettre à jour le graphique
                if (departmentChart && departmentChart.chart) {
                    departmentChart.chart.data.labels = labels;
                    departmentChart.chart.data.datasets[0].data = values;
                    departmentChart.chart.update();
                }
            }

            // Fonction pour mettre à jour le graphique de genre
            function updateGenderChart(data) {
                // Transformer les données pour le graphique
                const genderData = [
                    data.find(item => item.gender === 'FEMALE')?.count || 0,
                    data.find(item => item.gender === 'MALE')?.count || 0
                ];

                // Mettre à jour le graphique
                if (genderChart && genderChart.chart) {
                    genderChart.chart.data.datasets[0].data = genderData;
                    genderChart.chart.update();
                }
            }

            // Bouton d'actualisation du tableau de bord
            $('#refreshDashboard').on('click', function() {
                refreshCalendarData($('#showAllAbsences').is(':checked'));
                refreshEmployeeStats();

                // Afficher une notification toast
                const toast = new bootstrap.Toast($('#refreshToast'));
                toast.show();
            });

            // Activer les tooltips Bootstrap
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
                tooltipTriggerEl));
        });
    </script>
@endpush
