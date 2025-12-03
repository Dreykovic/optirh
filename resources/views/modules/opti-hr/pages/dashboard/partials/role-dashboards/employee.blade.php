{{-- Dashboard EMPLOYEE: Vue personnelle --}}

{{-- Statistiques personnelles --}}
@if(isset($personalStats))
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card dashboard-card stat-card h-100">
            <div class="card-body">
                <div class="stat-content">
                    <h6 class="stat-label">Solde de conges</h6>
                    <h2 class="stat-value">{{ $personalStats['absence_balance'] }} <small>jours</small></h2>
                </div>
                <div class="stat-icon bg-primary-subtle">
                    <i class="icofont-calendar text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card dashboard-card stat-card h-100">
            <div class="card-body">
                <div class="stat-content">
                    <h6 class="stat-label">Conges pris ({{ now()->year }})</h6>
                    <h2 class="stat-value">{{ $personalStats['absences_used'] }} <small>jours</small></h2>
                </div>
                <div class="stat-icon bg-success-subtle">
                    <i class="icofont-check-circled text-success"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card dashboard-card stat-card h-100">
            <div class="card-body">
                <div class="stat-content">
                    <h6 class="stat-label">Absences en attente</h6>
                    <h2 class="stat-value">{{ $personalStats['pending_absences'] }}</h2>
                </div>
                <div class="stat-icon bg-warning-subtle">
                    <i class="icofont-clock-time text-warning"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card dashboard-card stat-card h-100">
            <div class="card-body">
                <div class="stat-content">
                    <h6 class="stat-label">Documents en attente</h6>
                    <h2 class="stat-value">{{ $personalStats['pending_documents'] }}</h2>
                </div>
                <div class="stat-icon bg-info-subtle">
                    <i class="icofont-file-document text-info"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Actions rapides personnelles --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-4 col-6">
                        <a href="{{ route('absences.create') }}" class="btn btn-primary quick-action-btn">
                            <i class="icofont-calendar"></i>
                            <span>Demander un conge</span>
                        </a>
                    </div>
                    <div class="col-md-4 col-6">
                        <a href="{{ route('documents.create') }}" class="btn btn-info quick-action-btn text-white">
                            <i class="icofont-file-document"></i>
                            <span>Demander un document</span>
                        </a>
                    </div>
                    <div class="col-md-4 col-12">
                        <a href="{{ route('absences.requests') }}" class="btn btn-outline-primary quick-action-btn">
                            <i class="icofont-listing-box"></i>
                            <span>Mes demandes</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Mes absences et anniversaires --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        {{-- Mes absences recentes --}}
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="icofont-calendar text-info"></i>
                    Mes demandes d'absence
                </h5>
            </div>
            <div class="card-body p-0">
                @if(isset($myRecentAbsences) && $myRecentAbsences->count() > 0)
                    <div class="table-responsive">
                        <table class="table dashboard-table mb-0">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Periode</th>
                                    <th>Jours</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myRecentAbsences as $absence)
                                    <tr>
                                        <td>{{ $absence->absence_type->label ?? 'N/A' }}</td>
                                        <td>
                                            <small>{{ $absence->start_date->format('d/m') }} - {{ $absence->end_date->format('d/m/Y') }}</small>
                                        </td>
                                        <td><strong>{{ $absence->requested_days }}</strong></td>
                                        <td>
                                            @switch($absence->stage)
                                                @case('PENDING')
                                                    <span class="badge-status pending">En attente</span>
                                                    @break
                                                @case('APPROVED')
                                                    <span class="badge-status approved">Approuvee</span>
                                                    @break
                                                @case('REJECTED')
                                                    <span class="badge-status rejected">Rejetee</span>
                                                    @break
                                                @case('CANCELLED')
                                                    <span class="badge bg-secondary">Annulee</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $absence->stage }}</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="icofont-calendar d-block"></i>
                        <div class="message">Aucune demande d'absence</div>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('absences.requests') }}" class="btn btn-sm btn-primary">Voir tout</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.birthdays')
    </div>
</div>

{{-- Publications recentes --}}
<div class="row g-3">
    <div class="col-12">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.recent-publications')
    </div>
</div>
