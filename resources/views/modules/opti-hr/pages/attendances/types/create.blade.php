<!-- Add Department-->
<div class="modal fade" id="absenceTypeAdd" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
        <form id="modelAddForm" class="modal-content" data-model-add-url="{{ route('absenceTypes.save') }}">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="absenceTypeLabel">Ajout Type Absence</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label for="libelle" class="form-label required">Libellé</label>
                    <input type="text" class="form-control" id="libelle" name="libelle">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control" id="description" cols="30" rows="3"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Qualification</label>
                    <br>
                    <label class="fancy-radio">
                        <input type="radio" name="type" value="EXCEPTIONAL" required=""
                            data-parsley-errors-container="#error-radio" data-parsley-multiple="type">
                        <span><i></i>Exceptionnelle</span>
                    </label>
                    <label class="fancy-radio">
                        <input type="radio" name="type" value="NORMAL" data-parsley-multiple="type" checked>
                        <span><i></i>Normal</span>
                    </label>
                    <p id="error-radio"></p>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Déductible</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_deductible" name="is_deductible" value="1">
                        <label class="form-check-label" for="is_deductible">
                            Cette absence est déductible 
                        </label>
                    </div>
                </div>

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