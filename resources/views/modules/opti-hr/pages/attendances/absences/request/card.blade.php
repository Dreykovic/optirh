<!-- Improved Absence Request Item -->
<div class="absence-card mb-4 shadow-sm rounded-3 border-0 overflow-hidden">
    <!-- Accordion Header -->
    <div class="accordion-header absence-header" id="absenceRequestLine{{ $absence->id }}">
        <div class="card border-0">
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
        </div>
        <button class="accordion-button collapsed px-4 py-3 bg-light" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseAbsenceRequestLine{{ $absence->id }}" aria-expanded="false"
            aria-controls="collapseAbsenceRequestLine{{ $absence->id }}">
            <span class="btn-text">Voir les détails</span>
        </button>
    </div>

    <!-- Accordion Content -->
    <div id="collapseAbsenceRequestLine{{ $absence->id }}" class="accordion-collapse collapse"
        aria-labelledby="absenceRequestLine{{ $absence->id }}" data-bs-parent="#absenceRequestsList">
        <div class="card border-0">
            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="absence-info-card bg-light p-3 rounded-3 h-100">
                            <h6 class="border-bottom pb-2 mb-3">Informations de la demande</h6>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Période:</span>
                                <span class="fw-medium">
                                    <i class="icofont-calendar me-1 text-primary"></i>
                                    Du <strong>@formatDateOnly($absence->start_date)</strong>
                                    au <strong>@formatDateOnly($absence->end_date)</strong>
                                </span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Jours demandés:</span>
                                <span class="fw-medium">
                                    <i class="icofont-clock-time me-1 text-primary"></i>
                                    <strong>{{ $absence->requested_days }}</strong> jours
                                </span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Solde disponible:</span>
                                <span class="fw-medium">
                                    <i class="icofont-bank-alt me-1 text-primary"></i>
                                    <strong>{{ $absence->duty->absence_balance }}</strong> jours
                                </span>
                            </div>

                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Adresse durant l'absence:</span>
                                <span class="fw-medium text-truncate">
                                    <i class="icofont-location-pin me-1 text-primary"></i>
                                    {{ $absence->address ?: 'Non spécifiée' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="absence-manager-card bg-light p-3 rounded-3 mb-3">
                            <h6 class="border-bottom pb-2 mb-3">Supérieur hiérarchique</h6>
                            <div class="d-flex align-items-center">
                                <i class="icofont-user-suited fs-5 me-2 text-primary"></i>
                                <span class="fw-medium">
                                    {{ $absence->duty->job->n_plus_one_job
                                        ? $absence->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->last_name .
                                            ' ' .
                                            $absence->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->first_name .
                                            ' (' .
                                            $absence->duty->job->n_plus_one_job->title .
                                            ')'
                                        : 'Néant' }}
                                </span>
                            </div>
                        </div>

                        <div class="absence-reason-card bg-light p-3 rounded-3">
                            <h6 class="border-bottom pb-2 mb-3">Motif de l'absence</h6>
                            <p class="mb-0">{{ $absence->reasons ?: 'Aucun motif fourni' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Proof Document Section -->
                @if ($absence->proof)
                    <div class="mt-4 p-3 bg-light rounded-3">
                        <h6 class="border-bottom pb-2 mb-3">Pièce justificative</h6>
                        <div class="d-flex align-items-center">
                            <span
                                class="avatar bg-primary rounded-circle text-white d-flex align-items-center justify-content-center"
                                style="width: 45px; height: 45px">
                                <i class="icofont-file-pdf"></i>
                            </span>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-0">file1.pdf</h6>
                                <span class="text-muted small">Justificatif d'absence</span>
                            </div>
                            <button type="button" class="btn btn-outline-primary">
                                <i class="icofont-download me-1"></i>Télécharger
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Comments Section -->
                <div class="mt-4 p-3 bg-light rounded-3">
                    <h6 class="border-bottom pb-2 mb-3">Commentaire</h6>
                    <div class="d-flex">
                        <i class="icofont-comment fs-4 me-2 text-primary mt-1"></i>
                        <p class="mb-0">{{ $absence->comment ?: 'Aucun commentaire' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer with Action Buttons -->
    <div class="card border-0">
        <div class="card-footer bg-white d-flex justify-content-between align-items-center p-3">
            <div class="d-none d-md-block">
                <span class="text-muted">Soumis le:</span>
                <span class="ms-2 fw-medium">@formatDate($absence->date_of_application)</span>
            </div>

            <div class="d-flex">
                @if (
                    ($absence->level == 'ONE' && auth()->user()->hasRole('GRH')) ||
                        ($absence->level == 'TWO' && auth()->user()->hasRole('DG')) ||
                        ($absence->level == 'ZERO' &&
                            auth()->user()->employee->duties->firstWhere('evolution', 'ON_GOING')->job_id ===
                                $absence->duty->job->n_plus_one_job_id) ||
                        (in_array($absence->level, ['ZERO']) &&
                            auth()->user()->hasRole('GRH') &&
                            $absence->duty->job->n_plus_one_job_id === null))
                    <div class="modelUpdateFormContainer me-2" id="absenceApproveForm{{ $absence->id }}">
                        <form data-model-update-url="{{ route('absences.approve', $absence->id) }}">
                            <button type="submit" class="btn btn-success lift modelUpdateBtn">
                                <span class="normal-status">
                                    <i class="icofont-check me-1"></i>Approuver
                                </span>
                                <span class="indicateur d-none">
                                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    Un instant...
                                </span>
                            </button>
                        </form>
                    </div>

                    <div class="modelUpdateFormContainer me-2" id="absenceRejectForm{{ $absence->id }}">
                        <form data-model-update-url="{{ route('absences.reject', $absence->id) }}">
                            <button type="submit" class="btn btn-danger lift modelUpdateBtn">
                                <span class="normal-status">
                                    <i class="icofont-close me-1"></i>Rejeter
                                </span>
                                <span class="indicateur d-none">
                                    <span class="spinner-grow spinner-grow-sm" role="status"
                                        aria-hidden="true"></span>
                                    Un instant...
                                </span>
                            </button>
                        </form>
                    </div>

                    <button class="btn btn-outline-primary me-2 lift" data-bs-toggle="modal"
                        data-bs-target="#absenceCommentAdd{{ $absence->id }}">
                        <i class="icofont-comment me-1"></i>Commenter
                    </button>
                @endif

                @if (auth()->user()->employee_id === $absence->duty->employee_id && $absence->level === 'ZERO')
                    <div class="modelUpdateFormContainer" id="absenceCancelForm{{ $absence->id }}">
                        <form data-model-update-url="{{ route('absences.cancel', $absence->id) }}">
                            <button type="submit" class="btn btn-warning lift modelUpdateBtn">
                                <span class="normal-status">
                                    <i class="icofont-ban me-1"></i>Annuler
                                </span>
                                <span class="indicateur d-none">
                                    <span class="spinner-grow spinner-grow-sm" role="status"
                                        aria-hidden="true"></span>
                                    Un instant...
                                </span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Comment Modal -->
<div class="modal fade" id="absenceCommentAdd{{ $absence->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable modelUpdateFormContainer"
        id="absenceCommentUpdateForm{{ $absence->id }}">
        <form data-model-update-url="{{ route('absences.comment', $absence->id) }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="icofont-comment me-2 text-primary"></i>Ajouter un commentaire
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="comment" class="form-label">Votre commentaire</label>
                        <textarea name="comment" class="form-control" id="comment" rows="5"
                            placeholder="Saisissez votre commentaire ici...">{{ $absence->comment }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary modelUpdateBtn" data-bs-dismiss="modal">
                        <span class="normal-status">
                            <i class="icofont-save me-1"></i>Enregistrer
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Un instant...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Custom styles for the absence management UI */
    .absence-card {
        transition: all 0.3s ease;
    }

    .absence-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0, 0, 0, .125);
    }

    .accordion-button::after {
        transition: all 0.3s ease;
    }

    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
    }

    .lift {
        transition: all 0.25s ease;
        border-radius: 0.375rem;
    }

    .lift:hover,
    .lift:focus {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .employee-avatar {
        width: 50px;
        height: 50px;
    }

    .absence-info-card,
    .absence-manager-card,
    .absence-reason-card {
        transition: all 0.3s ease;
    }

    .absence-info-card:hover,
    .absence-manager-card:hover,
    .absence-reason-card:hover {
        background-color: #f8f9fa !important;
    }

    .btn-success,
    .btn-danger,
    .btn-warning,
    .btn-primary {
        color: white;
    }

    .btn-outline-primary:hover {
        color: white;
    }

    .fw-medium {
        font-weight: 500;
    }

    .avatar {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .absence-card .card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .absence-card .card-header .text-end {
            display: block !important;
            width: 100%;
            margin-top: 1rem;
            text-align: left !important;
        }

        .absence-card .card-header .text-end .d-flex {
            justify-content: flex-start !important;
        }

        .absence-card .card-footer {
            flex-direction: column;
            gap: 1rem;
        }

        .absence-card .card-footer .d-flex {
            width: 100%;
            justify-content: center !important;
        }
    }
</style>

<!-- Add JavaScript for improved interactions -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to accordions
        const accordionButtons = document.querySelectorAll('.accordion-button');
        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.querySelector('.btn-text').textContent = isExpanded ? 'Voir les détails' :
                    'Masquer les détails';
            });
        });

        // Enhance buttons with ripple effect
        const actionButtons = document.querySelectorAll('.btn');
        actionButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!this.classList.contains('btn-close') && !this.classList.contains(
                        'accordion-button')) {
                    const ripple = document.createElement('div');
                    const rect = this.getBoundingClientRect();

                    ripple.style.position = 'absolute';
                    ripple.style.width = '1px';
                    ripple.style.height = '1px';
                    ripple.style.left = e.clientX - rect.left + 'px';
                    ripple.style.top = e.clientY - rect.top + 'px';
                    ripple.style.background = 'rgba(255, 255, 255, 0.4)';
                    ripple.style.borderRadius = '50%';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    ripple.style.pointerEvents = 'none';

                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.style.transform = 'scale(100)';
                        ripple.style.opacity = '0';

                        setTimeout(() => {
                            ripple.remove();
                        }, 600);
                    }, 10);
                }
            });
        });
    });
</script>
