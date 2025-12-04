{{-- Widget: File d'approbation pour managers --}}
<div class="card dashboard-card h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="icofont-check-circled text-warning"></i>
            Approbations en attente
        </h5>
        @if(isset($approvalQueue))
            <span class="badge bg-warning text-dark">{{ $approvalQueue->count() }}</span>
        @endif
    </div>
    <div class="card-body">
        @if(isset($approvalQueue) && $approvalQueue->count() > 0)
            @foreach($approvalQueue->take(5) as $absence)
                @php
                    $employee = $absence->duty->employee ?? null;
                @endphp
                @if($employee)
                    <div class="approval-item">
                        <div class="header">
                            <div>
                                <div class="employee">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                <div class="type">{{ $absence->absence_type->label ?? 'Absence' }}</div>
                            </div>
                            <span class="badge-status pending">En attente</span>
                        </div>
                        <div class="dates">
                            <i class="icofont-calendar"></i>
                            {{ $absence->start_date->format('d/m') }} - {{ $absence->end_date->format('d/m/Y') }}
                            ({{ $absence->requested_days }} jours)
                        </div>
                        <div class="actions">
                            <a href="{{ route('absences.requests') }}" class="btn btn-sm btn-outline-primary">
                                <i class="icofont-eye-alt"></i> Voir details
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="empty-state">
                <i class="icofont-check-circled d-block"></i>
                <div class="message">Aucune approbation en attente</div>
            </div>
        @endif
    </div>
    @if(isset($approvalQueue) && $approvalQueue->count() > 5)
        <div class="card-footer text-center">
            <a href="{{ route('absences.requests') }}" class="btn btn-sm btn-warning">
                Voir les {{ $approvalQueue->count() }} demandes
            </a>
        </div>
    @endif
</div>
