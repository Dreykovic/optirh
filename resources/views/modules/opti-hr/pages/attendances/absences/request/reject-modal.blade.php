<div class="modal fade" id="absenceReject{{ $absence->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modelUpdateFormContainer"
        id="absenceRejectModalForm{{ $absence->id }}">
        <form data-model-update-url="{{ route('absences.reject', $absence->id) }}">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="icofont-close-circled me-2"></i>Rejeter la demande
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Alerte de confirmation -->
                    <div class="alert alert-warning d-flex align-items-center mb-4">
                        <i class="icofont-warning-alt fs-4 me-3"></i>
                        <div>
                            <strong>Attention !</strong><br>
                            Vous êtes sur le point de rejeter cette demande d'absence de
                            <strong>{{ $absence->duty->employee->first_name }} {{ $absence->duty->employee->last_name }}</strong>.
                        </div>
                    </div>

                    <!-- Résumé de la demande -->
                    <div class="bg-light rounded-3 p-3 mb-4">
                        <div class="row g-2 small">
                            <div class="col-6">
                                <span class="text-muted">Type:</span>
                                <span class="fw-bold ms-1">{{ $absence->absence_type->label }}</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted">Durée:</span>
                                <span class="fw-bold ms-1">{{ $absence->requested_days }} jour(s)</span>
                            </div>
                            <div class="col-12">
                                <span class="text-muted">Période:</span>
                                <span class="fw-bold ms-1">@formatDateOnly($absence->start_date) - @formatDateOnly($absence->end_date)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Motif du rejet (obligatoire) -->
                    <div class="mb-3">
                        <label for="rejectComment{{ $absence->id }}" class="form-label fw-bold">
                            Motif du rejet <span class="text-danger">*</span>
                        </label>
                        <textarea name="comment" class="form-control" id="rejectComment{{ $absence->id }}" rows="4"
                            placeholder="Expliquez la raison du rejet..." required minlength="10"></textarea>
                        <div class="form-text">
                            <i class="icofont-info-circle me-1"></i>
                            Minimum 10 caractères. Ce motif sera visible par le demandeur.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="icofont-close me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-danger modelUpdateBtn">
                        <span class="normal-status">
                            <i class="icofont-not-allowed me-1"></i>Confirmer le rejet
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Traitement...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
