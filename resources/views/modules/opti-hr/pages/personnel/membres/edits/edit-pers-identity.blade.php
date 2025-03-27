  <!-- Edit Employee Identity Info-->
  <div class="modal fade" id="updateIdentityModal" tabindex="-1"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    
                    <div class="modal-body">
                        <div class="deadline-form modelUpdateFormContainer" id="updateIdentityForm{{ $employee->id }}">
                            <form data-model-update-url="{{ route('membres.updatePresIdentity',$employee->id) }}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="modal-header">
                                <h5 class="modal-title  fw-bold" id="edit1Label">Identité</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                                <div class="row g-3 mb-3">
                                    <div class="col">
                                        <label for="nom" class="form-label required">Nom</label>
                                        <input type="text" class="form-control" id="nom" value="{{$employee->first_name}}" name='first_name'> 
                                    </div>
                                    <div class="col">
                                        <label for="prenom" class="form-label required">Prénom</label>
                                        <input type="text" class="form-control" id="prenom" value="{{$employee->last_name}}" name='last_name'> 
                                    </div>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col">
                                        <label for="contact" class="form-label required">Contact</label>
                                        <input type="text" class="form-control" id="contact" value="{{$employee->phone_number}}" name='phone_number'>
                                    </div>
                                    <div class="col">
                                        <label for="mail" class="form-label required">Email</label>
                                        <input type="email" class="form-control" id="mail" value="{{$employee->email}}" name='email'>
                                    </div>
                                </div> 
                                <div class="row g-3 mb-3">
                                    <div class="col">
                                        <label for="day" class="form-label">Date Naiss.</label>
                                        <input type="date" class="form-control" id="day" value="{{$employee->birth_date}}" name='birth_date'>
                                    </div>
                                    <div class="col">
                                        <label for="ad" class="form-label">Adresse</label>
                                        <input type="text" class="form-control" id="ad" value="{{$employee->address1}}" name='address1'>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-lg btn-block lift text-uppercase btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-lg btn-block lift text-uppercase btn-primary modelUpdateBtn" atl="update emp" 
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