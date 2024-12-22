  <!-- Edit Employee Personal Info-->
  <div class="modal fade" id="updatePersInfoModal" tabindex="-1"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    
                    <div class="modal-body">
                        <div class="deadline-form modelUpdateFormContainer" id="updatePersInfoForm">
                            <form id="modelAddForm" data-model-update-url="{{ route('membres.update') }}">
                              
                            <div class="modal-header">
                                <h5 class="modal-title  fw-bold" id="edit1Label">Informations Personnelles</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                                <div class="row g-3 mb-3">
                                    <div class="col">
                                        <label for="nat" class="form-label">Nationalité</label>
                                        <input type="text" class="form-control" id="nat" value="{{$employee->nationality}}" name='nationalitys'> 
                                    </div>
                                    <div class="col">
                                        <label for="religion" class="form-label">Religion</label>
                                        <input type="text" class="form-control" id="religion" value="{{$employee->religion}}" name='religion'> 
                                    </div>
                                </div>
                                <div class="row g-3 mb-3">
                                <div class="col">
                                    <label for="sm" class="form-label">Situation Matri.</label>
                                    <select class="form-select" aria-label="Default select Project Category" id="sm" name='marital_status'>
                                            <option selected value='{{$employee->marital_status}}'></option>
                                            <option value="Single">Célibataire</option>
                                            <option value="Married">Marié.e</option>
                                            <option value="Divorced">Divorcé.e</option>
                                            <option value="Widowed">Veuf.ve</option>
                                        </select>
                                </div>
                                <div class="col">
                                    <label for="cu" class="form-label">Contact urgence</label>
                                    <input type="text" class="form-control" id="cu" value="{{$employee->emergency_contact}}" name='emergency_contact'>
                                </div>
                                </div> 
                                <div class="row g-3 mb-3">
                                    <div class="col">
                                        <label for="ville" class="form-label">Ville</label>
                                        <input type="text" class="form-control" id="ville" value="{{$employee->city}}" name='city'>
                                    </div>
                                    <div class="col">
                                        <label for="qt" class="form-label">Qyartier</label>
                                        <input type="text" class="form-control" id="qt" value="{{$employee->state}}" name='state'>
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
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </div>  
            </div>
        </div>