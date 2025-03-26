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
