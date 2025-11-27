{{--
    Composant Workflow Stepper pour les demandes d'absence
    Affiche visuellement l'état d'avancement de la validation

    Variables requises:
    - $absence : L'objet Absence avec ses relations
--}}

@php
    // Définir les étapes du workflow
    $steps = [
        [
            'level' => 'ZERO',
            'label' => 'Demande',
            'shortLabel' => 'Demande',
            'icon' => 'bi-person-fill',
            'description' => 'Demande créée par l\'employé'
        ],
        [
            'level' => 'ONE',
            'label' => 'Chef Direct',
            'shortLabel' => 'N+1',
            'icon' => 'bi-person-badge-fill',
            'description' => $absence->duty->job->n_plus_one_job->label ?? 'Responsable hiérarchique'
        ],
        [
            'level' => 'TWO',
            'label' => 'GRH',
            'shortLabel' => 'GRH',
            'icon' => 'bi-people-fill',
            'description' => 'Gestionnaire des Ressources Humaines'
        ],
        [
            'level' => 'THREE',
            'label' => 'Direction',
            'shortLabel' => 'DG',
            'icon' => 'bi-building',
            'description' => 'Direction Générale'
        ],
    ];

    // Mapping niveau vers index
    $levelToIndex = ['ZERO' => 0, 'ONE' => 1, 'TWO' => 2, 'THREE' => 3];
    $currentLevelIndex = $levelToIndex[$absence->level] ?? 0;

    // État de la demande
    $isApproved = $absence->stage === 'APPROVED';
    $isRejected = $absence->stage === 'REJECTED';
    $isCancelled = $absence->stage === 'CANCELLED';
@endphp

<div class="workflow-stepper-container">
    <div class="workflow-stepper d-flex align-items-start justify-content-between">
        @foreach($steps as $index => $step)
            @php
                // Déterminer l'état de chaque étape
                if ($isApproved) {
                    // Si approuvée, toutes les étapes sont complétées
                    $stepState = 'completed';
                } elseif ($isRejected) {
                    // Si rejetée, les étapes avant le niveau actuel sont complétées, l'actuelle est rejetée
                    if ($index < $currentLevelIndex) {
                        $stepState = 'completed';
                    } elseif ($index === $currentLevelIndex) {
                        $stepState = 'rejected';
                    } else {
                        $stepState = 'pending';
                    }
                } elseif ($isCancelled) {
                    // Si annulée, toutes les étapes sont en attente
                    $stepState = $index === 0 ? 'cancelled' : 'pending';
                } else {
                    // En cours de traitement
                    if ($index < $currentLevelIndex) {
                        $stepState = 'completed';
                    } elseif ($index === $currentLevelIndex) {
                        $stepState = 'current';
                    } else {
                        $stepState = 'pending';
                    }
                }
            @endphp

            <div class="step {{ $stepState }}" title="{{ $step['description'] }}">
                <div class="step-icon">
                    @if($stepState === 'completed')
                        <i class="bi bi-check-lg"></i>
                    @elseif($stepState === 'rejected')
                        <i class="bi bi-x-lg"></i>
                    @elseif($stepState === 'cancelled')
                        <i class="bi bi-slash-circle"></i>
                    @else
                        <i class="bi {{ $step['icon'] }}"></i>
                    @endif
                </div>
                <div class="step-content">
                    <div class="step-label d-none d-sm-block">{{ $step['label'] }}</div>
                    <div class="step-label d-block d-sm-none">{{ $step['shortLabel'] }}</div>
                    @if($stepState === 'current')
                        <div class="step-status">En attente</div>
                    @elseif($stepState === 'rejected')
                        <div class="step-status text-danger">Rejetée</div>
                    @elseif($stepState === 'completed' && $index === count($steps) - 1 && $isApproved)
                        <div class="step-status text-success">Approuvée</div>
                    @endif
                </div>
            </div>

            {{-- Connecteur entre les étapes --}}
            @if($index < count($steps) - 1)
                @php
                    $connectorState = 'pending';
                    if ($isApproved || $index < $currentLevelIndex - 1 || ($index < $currentLevelIndex && !$isRejected)) {
                        $connectorState = 'completed';
                    }
                @endphp
                <div class="step-connector {{ $connectorState }}"></div>
            @endif
        @endforeach
    </div>

    {{-- Indicateur textuel du statut actuel --}}
    <div class="workflow-status-text text-center mt-2">
        @if($isApproved)
            <span class="badge bg-success">
                <i class="bi bi-check-circle me-1"></i>
                Approuvée le {{ $absence->date_of_approval?->format('d/m/Y') ?? '-' }}
            </span>
        @elseif($isRejected)
            <span class="badge bg-danger">
                <i class="bi bi-x-circle me-1"></i>
                Rejetée
            </span>
        @elseif($isCancelled)
            <span class="badge bg-secondary">
                <i class="bi bi-slash-circle me-1"></i>
                Annulée
            </span>
        @else
            <span class="badge bg-warning text-dark">
                <i class="bi bi-hourglass-split me-1"></i>
                En attente de validation - {{ $steps[$currentLevelIndex]['label'] ?? 'N/A' }}
            </span>
        @endif
    </div>
</div>
