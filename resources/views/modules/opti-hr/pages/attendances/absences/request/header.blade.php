<div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
    <div class="d-flex align-items-center">
        <x-employee-icon :employee="$employee" class="employee-avatar" />
        <div class="ms-3">
            <h6 class="mb-1 fw-bold">{{ $employee->last_name . ' ' . $employee->first_name }}</h6>
            <div class="text-muted small">{{ $absence->duty->job->title }}</div>
            <div class="text-muted small">{{ $absence->duty->job->department->name }}</div>
        </div>
    </div>
    <div class="text-end d-none d-md-block">
        <h6 class="mb-2 badge bg-light text-dark p-2">
            {{ !$absence_type ? 'Non défini' : $absence_type->label }}
        </h6>
        <div class="d-flex align-items-center justify-content-end">
            <span class="me-2">Status:</span>
            @switch($absence->stage)
                @case('APPROVED')
                    <span class="badge bg-success">Approuvé</span>
                @break

                @case('REJECTED')
                    <span class="badge bg-danger">Rejeté</span>
                @break

                @case('CANCELLED')
                    <span class="badge bg-secondary">Annulé</span>
                @break

                @case('IN_PROGRESS')
                    <span class="badge bg-warning">En traitement</span>
                @break

                @case('COMPLETED')
                    <span class="badge bg-info">Complété</span>
                @break

                @default
                    <span class="badge bg-warning text-dark">En attente</span>
            @endswitch
        </div>
    </div>
</div>
