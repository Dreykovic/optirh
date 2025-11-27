<div class="modal fade" id="absenceComment{{ $absence->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modelUpdateFormContainer"
        id="absenceCommentForm{{ $absence->id }}">
        <form data-model-update-url="{{ route('absences.comment', $absence->id) }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="icofont-comment me-2 text-primary"></i>Ajouter un commentaire
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Informations sur la demande -->
                    <div class="bg-light rounded-3 p-3 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px">
                                <i class="icofont-user"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $absence->duty->employee->first_name }} {{ $absence->duty->employee->last_name }}</div>
                                <div class="text-muted small">{{ $absence->absence_type->label }} - {{ $absence->requested_days }} jour(s)</div>
                            </div>
                        </div>
                    </div>

                    <!-- Commentaire -->
                    <div class="mb-3">
                        <label for="comment{{ $absence->id }}" class="form-label fw-bold">Commentaire</label>
                        <textarea name="comment" class="form-control" id="comment{{ $absence->id }}" rows="4"
                            placeholder="Ajoutez un commentaire pour cette absence...">{{ $absence->comment }}</textarea>
                        <div class="form-text">
                            <i class="icofont-info-circle me-1"></i>
                            Ce commentaire sera visible par le demandeur et les responsables RH.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="icofont-close me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary modelUpdateBtn">
                        <span class="normal-status">
                            <i class="icofont-save me-1"></i>Enregistrer
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Enregistrement...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
