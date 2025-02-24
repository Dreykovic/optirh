<!-- Modal Modal Centered Scrollable-->
<div class="modal fade" id="publicationAddModal" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">


        <form id="modelAddForm" class="modal-content" data-model-add-url="{{ route('publications.config.save') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title  fw-bold" id="absenceTypeLabel">Créer une publication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">



                <div class="mb-3">
                    <label for="title" class="form-label required">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" value=""
                        placeholder="titre de la note ">
                </div>


                <div class="mb-3">
                    <label for="content" class="form-label">Message </label>
                    <textarea class="form-control" id="content" rows="3" name="content"></textarea>
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label">Fichier pdf associé</label>
                    <input type="file" class="form-control" id="file" name="file">
                </div>
                <div class="alert alert-info" role="alert">
                    Seul les notes marquées "visible" seront accessibles aux utilisateurs
                </div>
                <!--begin::Input group-->
                <div class="mb-3">
                    <!--begin::Label-->
                    <label class="required fw-semibold fs-6 mb-5">Statut</label>
                    <!--end::Label-->

                    <!--begin::Statut-->

                    <!--begin::Input row-->
                    <div class="d-flex ">
                        <!--begin::Radio-->
                        <div class="form-check form-check-custom form-check-solid">
                            <!--begin::Input-->
                            <input class="form-check-input me-3" name="role" type="radio" value="pending"
                                id="pending">
                            <!--end::Input-->

                            <!--begin::Label-->
                            <label class="form-check-label" for="pending">
                                <div class="fw-bold text-gray-800">
                                    À venir</div>

                            </label>
                            <!--end::Label-->
                        </div>
                        <!--end::Radio-->
                    </div>
                    <!--end::Input row-->
                    <!--begin::Input row-->
                    <div class="d-flex ">
                        <!--begin::Radio-->
                        <div class="form-check form-check-custom form-check-solid">
                            <!--begin::Input-->
                            <input class="form-check-input me-3" name="role" type="radio" value="published"
                                id="published">
                            <!--end::Input-->

                            <!--begin::Label-->
                            <label class="form-check-label" for="published">
                                <div class="fw-bold text-gray-800">
                                    Visible</div>

                            </label>
                            <!--end::Label-->
                        </div>
                        <!--end::Radio-->
                    </div>
                    <!--end::Input row-->


                    <div class='separator separator-dashed my-5'>
                    </div>


                    <!--end::Statut-->
                </div>
                <!--end::Input group-->

                <!--end::Scroll-->


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary" atl="Ajouter Absence Type" id="modelAddBtn"
                    data-bs-dismiss="modal">
                    <span class="normal-status">
                        Ajouter
                    </span>
                    <span class="indicateur d-none">
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Un Instant...
                    </span>
                </button>
            </div>
        </form>


    </div>
</div>
