<div class="modal fade" id="documentReqDetails{{ $documentRequest->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3>

                    Détails Demande D' {{ $documentRequest->document_type->label }}
                </h3>
            </div>
            <div class="modal-body">
                <div>
                    @include('modules.opti-hr.pages.documents.main.request.body')
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary  " atl="Cacher Les détails" data-bs-dismiss="modal">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>
