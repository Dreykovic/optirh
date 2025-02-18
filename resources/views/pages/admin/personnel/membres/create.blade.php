  <!-- Create Employee-->
  <div class="modal fade" id="addEmpModal" tabindex="-1"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                   
                    <div class="modal-body">
                        <div class="modal-header">
                            <h5 class="modal-title  fw-bold" id="createprojectlLabel"> Nouveau Employé</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="modelAddForm" data-model-add-url="{{ route('membres.store') }}">
                            @csrf
                            <fieldset class="border p-3 shadow-sm  border-dark mb-2">
                            <legende class="w-auto px-2 fs-6 shadow-4 text-muted fw-bold shadow"><span class='mb-4'>Identité & Adresse</span></legende>
                                <div class="row g-3 mb-3 mt-2">
                                    <div class="col-sm-6">
                                        <label for="last_name" class="form-label">Nom</label>
                                        <input type="text" class="form-control" id="last_name" name='last_name' placeholder="">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="first_name" class="form-label">Prénoms</label>
                                        <input type="text" class="form-control" id="first_name" name='first_name'>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="row g-3 mb-3">
                                    <div class="col-sm-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name='email' placeholder="">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="phone_number" class="form-label">Contact</label>
                                        <input type="text" class="form-control" id="phone_number" name='phone_number'>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="row g-3 mb-3">
                                    <div class="col-sm-6">
                                        <label for="address1" class="form-label">Adresse</label>
                                        <input type="address1" class="form-control" id="address1" name='address1' placeholder="">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="gender" class="form-label">Genre</label>
                                        <select class="form-select" aria-label="Default select Project Category" id="gender" name='gender'>
                                            <option selected value='MALE'>Homme</option>
                                            <option value="FEMALE">Femme</option>
                                        </select>
                                    </div>
                                </div>
                                <!--  -->
                            </fieldset>
                            <fieldset class="border p-3 shadow-sm  border-dark">
                                <legende class="w-auto px-2 fs-6 shadow-4 text-muted fw-bold shadow mb-3">Poste</legende>
                                 <!--  -->
                                 <div class="row g-3 mb-3 mt-2">
                                    <div class="col-sm-6">
                                        <label for="department" class="form-label">Direction</label>
                                        <select class="form-select" id="department" name="department_id" onchange="loadJobs(this.value)">
                                            <option selected>choisir</option>
                                            @forelse($departments as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                            @empty
                                                <option value="">aucune direction</option>
                                            @endforelse
                                        </select>

                                    </div>
                                    <div class="col-sm-6">
                                        <label for="job" class="form-label">Poste</label>
                                        <select class="form-select" id="job" name="job_id">
                                            <!-- <option selected>choisir</option> -->
                                        </select>

                                    </div>
                                </div>
                                <!--  -->
                                 <!--  -->
                                 <div class="row g-3 mb-3">
                                    <div class="col-sm-6">
                                        <label for="date" class="form-label">Date Embauche</label>
                                        <input type="date" class="form-control" id="date" name='begin_date' placeholder="">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="duration" class="form-label">Durée du contrat</label>
                                        <input type="text" class="form-control" id="duration" name='duration' placeholder="">
                                    </div>
                                </div>
                                <!-- absence_balance -->
                                 <div class='row g-3 mb-3'>
                                        <div class="col-sm-6">
                                            <label for="type" class="form-label">Type du contrat</label>
                                            <select id="type" name='type' class="form-select" aria-label="Default select Project Category">
                                                    <option value='CDI'>CDI</option>
                                                    <option value='CDD'>CDD</option>
                                                    <option value='interim'>interim</option>
                                                    <option value='stage'>stage</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="balance" class="form-label">Solde congé</label>
                                            <input type="text" class="form-control" id="balance" name='absence_balance' placeholder="">
                                        </div>
                                 </div>
                                

                            </fieldset>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-lg btn-block lift text-uppercase btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-lg btn-block lift text-uppercase btn-primary" atl="Ajouter Emp" id="modelAddBtn"
                                    data-bs-dismiss="modal">
                                    <span class="normal-status">
                                        Enregister
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