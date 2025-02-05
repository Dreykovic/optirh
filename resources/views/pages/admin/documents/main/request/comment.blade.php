<div class="modal fade" id="documentRequestCommentAdd{{ $documentRequest->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable modelUpdateFormContainer"
        id="documentRequestCommentUpdateForm{{ $documentRequest->id }}">
        <form data-model-update-url="{{ route('documents.comment', $documentRequest->id) }}">
            {{-- @csrf --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title  fw-bold" id="documentRequestTypeLabel">Commentaire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <div class="mb-3">
                        <label for="comment" class="form-label">Commentaire</label>
                        <textarea name="comment" class="form-control" id="comment" cols="30" rows="3">  {{ $documentRequest->comment }}</textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary  modelUpdateBtn" atl="Add Comment to Document Request"
                        data-bs-dismiss="modal">
                        <span class="normal-status">
                            Enregistrer
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Un Instant...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
