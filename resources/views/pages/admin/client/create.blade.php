<div class="modal fade" id="createClient" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title  fw-bold" id="createprojectlLabel"> Enregistrer Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="modelAddForm" data-model-add-url="{{ route('clients.save') }}" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="nomClient" class="form-label required">Nom</label>
                        <input type="text" class="form-control" id="nomClient" placeholder="Nom complet du client"
                            name="nom_client">
                    </div>



                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo Du client</label>
                        <input class="form-control" type="file" id="photo" name="photo">
                    </div>




                    <div class="row g-3 mb-3">

                        <div class="col">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" class="form-control" id="ville" placeholder="Ville Du client"
                                name="ville" required>
                        </div>
                        <div class="col">
                            <label for="quartier" class="form-label">QUartier</label>
                            <input type="text" class="form-control" id="quartier" placeholder="Quartier Du Client"
                                name="quartier" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="phone" class="form-label col-sm-3 col-input-label required">Phone</label>

                        <div class="col-sm-9">
                            <input type="tel" class="form-control" id="phone"
                                placeholder="N° Téléphone Du Client" name="telephone" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        {{-- <label for="pays" class="form-label">Pays</label> --}}
                        <input type="text" class="form-control" id="location" placeholder="Pays Du Client"
                            name="location" hidden>
                    </div>
                    <div class="row form-group mb-3">
                        <label for="job" class="col-sm-3 col-form-label input-label required">Profession
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="job" placeholder="Profession du client"
                                name="profession">
                        </div>
                    </div>

                    <div class="row form-group mb-3">
                        <label for="email" class="col-sm-3 col-form-label input-label required">Email
                        </label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" placeholder="Email Du Client"
                                name="email">
                        </div>
                    </div>

                    <div class="row form-group mb-3">
                        <label for="birthdate" class="col-sm-3 col-form-label input-label required">Date de
                            naissance
                        </label>
                        <div class="col-sm-9">

                            <input class="form-control" type="date" name="date" required>


                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label input-label required">Sexe</label>
                        <div class="col-md-9">

                            <label>
                                <input type="radio" name="sexe" value="Féminin"> Féminin
                            </label>

                            <label>
                                <input type="radio" name="sexe" value="Masculin">Masculin
                            </label>


                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" atl="addTransaction" id="modelAddBtn"
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
</div>
