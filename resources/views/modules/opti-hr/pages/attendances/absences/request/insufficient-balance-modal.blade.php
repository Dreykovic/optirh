<!-- Modal de solde insuffisant (GRH) -->
<div class="modal fade" id="insufficientBalanceModal" tabindex="-1" aria-labelledby="insufficientBalanceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning bg-opacity-25 border-0">
                <h5 class="modal-title" id="insufficientBalanceModalLabel">
                    <i class="icofont-warning text-warning me-2"></i>
                    Solde de congés insuffisant
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning border-warning mb-4">
                    <div class="d-flex align-items-start">
                        <i class="icofont-exclamation-tringle fs-4 me-3 text-warning"></i>
                        <div>
                            <strong>Attention !</strong>
                            <p class="mb-0 mt-1">
                                <span id="insufficientEmployeeName" class="fw-bold"></span> dispose de
                                <span id="insufficientCurrentBalance" class="badge bg-danger"></span> jour(s) de solde
                                mais demande <span id="insufficientRequestedDays" class="badge bg-primary"></span> jour(s).
                            </p>
                        </div>
                    </div>
                </div>

                <p class="fw-bold text-dark mb-3">Choisissez une option pour continuer :</p>

                <div class="d-grid gap-3">
                    <!-- Option 1: Passer en non-déductible -->
                    <button class="btn btn-outline-success text-start p-3 insufficient-option-btn"
                            data-option="make_non_deductible">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="icofont-check-circled fs-3 text-success"></i>
                            </div>
                            <div>
                                <strong class="d-block">Passer en non-déductible</strong>
                                <small class="text-muted">
                                    L'absence sera approuvée sans impact sur le solde de congés.
                                    Le solde restera intact.
                                </small>
                            </div>
                        </div>
                    </button>

                    <!-- Option 2: Déduire le solde disponible uniquement -->
                    <button class="btn btn-outline-primary text-start p-3 insufficient-option-btn"
                            data-option="deduct_available">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="icofont-minus-circle fs-3 text-primary"></i>
                            </div>
                            <div>
                                <strong class="d-block">Déduire le solde disponible uniquement</strong>
                                <small class="text-muted">
                                    Seuls <span id="insufficientAvailableDays" class="fw-bold"></span> jour(s) seront déduits.
                                    Le solde passera à 0.
                                </small>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="icofont-close me-1"></i>Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    #insufficientBalanceModal .insufficient-option-btn {
        transition: all 0.3s ease;
        border-width: 2px;
    }

    #insufficientBalanceModal .insufficient-option-btn:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    #insufficientBalanceModal .insufficient-option-btn.btn-outline-success:hover {
        background-color: rgba(25, 135, 84, 0.1);
    }

    #insufficientBalanceModal .insufficient-option-btn.btn-outline-primary:hover {
        background-color: rgba(13, 110, 253, 0.1);
    }

    #insufficientBalanceModal .insufficient-option-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    #insufficientBalanceModal .insufficient-option-btn .spinner-border {
        width: 1.2rem;
        height: 1.2rem;
    }
</style>
