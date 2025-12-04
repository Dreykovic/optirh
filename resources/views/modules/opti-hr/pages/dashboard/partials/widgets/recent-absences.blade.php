{{-- Widget: Absences recentes --}}
<div class="card dashboard-card h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="icofont-calendar text-info"></i>
            Demandes d'absence recentes
        </h5>
        <span class="badge bg-primary">{{ $recentAbsences->count() ?? 0 }}</span>
    </div>
    <div class="card-body p-0">
        @if(isset($recentAbsences) && $recentAbsences->count() > 0)
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th>Employe</th>
                            <th>Type</th>
                            <th>Periode</th>
                            <th>Jours</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAbsences as $absence)
                            @php
                                $employee = $absence->duty->employee ?? null;
                                $isOwner = auth()->user()->employee_id === $absence->duty->employee_id;
                                $canView = false;

                                // Les absences annulees ne sont visibles que par le proprietaire
                                if ($absence->stage === 'CANCELLED') {
                                    $canView = $isOwner;
                                } else {
                                    // Logique de visibilite selon le role pour les autres statuts
                                    if ($isOwner) {
                                        $canView = true;
                                    } elseif (auth()->user()->hasAnyRole(['GRH', 'ADMIN'])) {
                                        $canView = true;
                                    } elseif (auth()->user()->hasRole('DSAF') && in_array($absence->level, ['ONE', 'TWO', 'THREE'])) {
                                        $canView = true;
                                    } elseif (auth()->user()->hasRole('DG') && in_array($absence->level, ['TWO', 'THREE'])) {
                                        $canView = true;
                                    }
                                }
                            @endphp

                            @if($canView && $employee)
                                <tr>
                                    <td>
                                        <div class="employee-info">
                                            <x-employee-icon :employee="$employee" />
                                            <div>
                                                <div class="name">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                                <div class="department">{{ $absence->duty->job->department->name ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $absence->absence_type->label ?? 'N/A' }}</td>
                                    <td>
                                        <small>{{ $absence->start_date->format('d/m') }} - {{ $absence->end_date->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $absence->requested_days }}</strong>
                                    </td>
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
                                    <td>
                                        <a href="{{ route('absences.requests') }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                            <i class="icofont-eye-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="icofont-calendar d-block"></i>
                <div class="message">Aucune demande d'absence recente</div>
            </div>
        @endif
    </div>
    <div class="card-footer">
        <a href="{{ route('absences.requests') }}" class="btn btn-sm btn-primary">
            Voir toutes les absences
        </a>
    </div>
</div>
