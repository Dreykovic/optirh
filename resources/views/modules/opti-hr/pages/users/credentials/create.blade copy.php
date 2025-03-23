<!-- Modal Modal Centered Scrollable-->
<div class="modal fade" id="credentialAddModal" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">


        <form id="modelAddForm" class="modal-content" data-model-add-url="{{ route('credentials.save') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title  fw-bold" id="absenceTypeLabel">Créer Accès Utilisateurs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    Le nom d'utilisateur du membre du personnel sera généré automatiquement et envoyé par e-mail.
                    Celui-ci devra modifier son mot de passe lors de sa première connexion.
                </div>




                <!--begin::Input group-->
                <div class="mb-3">
                    <label class="form-label required" for="employeeId">Choisir </label>
                    <select class="form-select" id="employeeId" name="employee">

                        @foreach ($employeesWithoutUser as $employee)
                            <option value="{{ $employee->id }}">
                                {{ $employee->last_name . ' ' . $employee->first_name }}
                            </option>
                        @endforeach

                    </select>
                </div>
                <!--end::Input group-->




                <!--begin::Input group-->
                <div class="mb-3">
                    <!--begin::Label-->
                    <label class="required fw-semibold fs-6 mb-5">Role</label>
                    <!--end::Label-->

                    <!--begin::Roles-->
                    @foreach ($roles as $role)
                        <!--begin::Input row-->
                        <div class="d-flex ">
                            <!--begin::Radio-->
                            <div class="form-check form-check-custom form-check-solid">
                                <!--begin::Input-->
                                <input class="form-check-input me-3" name="role" type="radio"
                                    value="{{ $role->name }}" id="kt_modal_update_role_option_{{ $role->id }}">
                                <!--end::Input-->

                                <!--begin::Label-->
                                <label class="form-check-label" for="kt_modal_update_role_option_{{ $role->id }}">
                                    <div class="fw-bold text-gray-800">
                                        {{ $role->name }}</div>

                                </label>
                                <!--end::Label-->
                            </div>
                            <!--end::Radio-->
                        </div>
                        <!--end::Input row-->

                        <div class='separator separator-dashed my-5'>
                        </div>
                    @endforeach

                    <!--end::Roles-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-3">
                    <!--begin::Label-->
                    <label class="required fw-semibold fs-6 mb-5">Accès</label>
                    <!--end::Label-->

                    <!--begin::DashBoardPermission-->
                    <!--begin::Input row-->
                    <div class="d-flex ">
                        <!--begin::Radio-->
                        <div class="form-check form-check-custom form-check-solid">
                            <!--begin::Input-->
                            <input class="form-check-input me-3" name="role" type="radio"
                                value="{{ $role->name }}" id="kt_modal_update_role_option_{{ $role->id }}">
                            <!--end::Input-->

                            <!--begin::Label-->
                            <label class="form-check-label" for="kt_modal_update_role_option_{{ $role->id }}">
                                <div class="fw-bold text-gray-800">
                                    {{ $role->name }}</div>

                            </label>
                            <!--end::Label-->
                        </div>
                        <!--end::Radio-->
                    </div>
                    <!--end::Input row-->

                    <div class='separator separator-dashed my-5'>
                    </div>

                    <!--end::DashBoardPermission-->
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
