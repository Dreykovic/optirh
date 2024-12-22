    <!-- Edit Bank Personal Info-->
    <div class="modal fade" id="updateBankInfoModal" tabindex="-1"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                   
                    <div class="modal-body">
                        <div class="deadline-form modelUpdateFormContainer" id="updateBankInfoForm">
                            <form id="modelAddForm" data-model-update-url="{{ route('membres.update') }}">
                                <div class="modal-header">
                                    <h5 class="modal-title  fw-bold" id="edit2Label">Compte Bancaire</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col">
                                        <label for="bn" class="form-label">Nom Banque</label>
                                        <input type="text" class="form-control" id="bn" value="{{$employee->bank_name}}" name='bank_name'> 
                                    </div>
                                    <div class="col">
                                        <label for="rib" class="form-label">No. Compte</label>
                                        <input type="text" class="form-control" id="rib" value="{{$employee->rib}}" name='rib'> 
                                    </div>
                                </div>
                                <div class="row g-3 mb-3">
                                <div class="col">
                                    <label for="cb" class="form-label">Code Banque</label>
                                    <input type="text" class="form-control" id="cb" value="{{$employee->code_bank}}" name='code_bank'>
                                </div>
                                <div class="col">
                                    <label for="cg" class="form-label">Code Guichet</label>
                                    <input type="text" class="form-control" id="cg" value="{{$employee->code_guichet}}" name='code_guichet'>
                                </div>
                                </div> 
                                <!--  -->
                                <div class="row g-3 mb-3">
                                <div class="col">
                                    <label for="iban" class="form-label">IBAN</label>
                                    <input type="text" class="form-control" id="iban" value="{{$employee->iban}}" name='iban'>
                                </div>
                                <div class="col">
                                    <label for="cle_rib" class="form-label">Clé RIB</label>
                                    <input type="text" class="form-control" id="cle_rib" value="{{$employee->cle_rib}}" name='cle_rib'>
                                </div>
                                </div> 
                                <div class="row g-3 mb-3">
                                    <div class="col">
                                        <label for="swift" class="form-label">Swift</label>
                                        <input type="text" class="form-control" id="swift" value="{{$employee->swift}}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-lg btn-block lift text-uppercase btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-lg btn-block lift text-uppercase btn-primary modelUpdateBtn" atl="update emp" id="modelAddBtn"
                                        data-bs-dismiss="modal">
                                        <span class="normal-status">
                                            Modifier
                                        </span>
                                        <span class="indicateur d-none">
                                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                            Un Instant...
                                        </span>
                                    </button> 
                            </form>
                        </div>
                    </div>
                   
                </div>  
            </div>
        </div>