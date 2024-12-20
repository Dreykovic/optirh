<div class="accordion-item  mb-3">
    @include('pages.admin.attendances.absences.request.header')
    <div id="collapseAbsenceRequestLine{{ $absence->id }}" class="accordion-collapse collapse "
        aria-labelledby="absenceRequestLine{{ $absence->id }}" data-bs-parent="#absenceRequestsList">
        <div class="card">
            @include('pages.admin.attendances.absences.request.body')
        </div>
    </div>
    <div class="card">

        @include('pages.admin.attendances.absences.request.footer')
    </div>
</div>
<!-- Add Comment-->
<div class="modal fade" id="absenceCommentAdd{{ $absence->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable modelUpdateFormContainer"
        id="absenceCommentUpdateForm{{ $absence->id }}">
        <form data-model-update-url="{{ route('absences.comment', $absence->id) }}">
            {{-- @csrf --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title  fw-bold" id="absenceTypeLabel">Commentaire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <div class="mb-3">
                        <label for="comment" class="form-label">Commentaire</label>
                        <textarea name="comment" class="form-control" id="comment" cols="30" rows="3">  {{ $absence->comment }}</textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary  modelUpdateBtn" atl="Modifier Absence Type"
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
