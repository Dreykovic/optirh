<div class="modal fade" id="absenceCommentAdd{{ $absence->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable modelUpdateFormContainer"
        id="absenceCommentUpdateForm{{ $absence->id }}">
        <form data-model-update-url="{{ route('absences.comment', $absence->id) }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="icofont-edit me-2 text-primary"></i>Paramètres de l'absence
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Contrôle de déductibilité -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Déductibilité de l'absence</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" 
                                id="isDeductible{{ $absence->id }}" name="is_deductible" 
                                {{ $absence->is_deductible ? 'checked' : '' }}>
                            <label class="form-check-label ms-2" for="isDeductible{{ $absence->id }}">
                                <span class="badge bg-{{ $absence->is_deductible ? 'danger' : 'success' }}">
                                    {{ $absence->is_deductible ? 'Déductible' : 'Non déductible' }}
                                </span>
                            </label>
                        </div>
                        <div class="form-text mt-1">
                            {{ $absence->is_deductible 
                                ? "Cette absence sera déduite du solde de congés de l'employé." 
                                : "Cette absence ne sera pas déduite du solde de congés de l'employé." }}
                        </div>
                    </div>

                    <!-- Avertissement solde -->
                    <div class="alert alert-info d-flex">
                        <i class="bi bi-info-circle-fill me-2 fs-5 align-self-center"></i>
                        <div>
                            <strong>Info solde :</strong> L'employé dispose actuellement de 
                            <span class="badge bg-primary">{{ $absence->duty->absence_balance ?? 0 }} jours</span> 
                            et cette absence est de <span class="badge bg-secondary">{{ $absence->requested_days }} jours</span>.
                        </div>
                    </div>

                    <!-- Commentaire -->
                    <div class="mb-3">
                        <label for="comment" class="form-label fw-bold">Commentaire</label>
                        <textarea name="comment" class="form-control" id="comment" rows="4"
                            placeholder="Ajoutez un commentaire pour cette absence...">{{ $absence->comment }}</textarea>
                        <div class="form-text">
                            Ce commentaire sera visible par le demandeur et les responsables RH.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="icofont-close me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary modelUpdateBtn" data-bs-dismiss="modal">
                        <span class="normal-status">
                            <i class="icofont-save me-1"></i>Enregistrer les modifications
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Traitement en cours...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>