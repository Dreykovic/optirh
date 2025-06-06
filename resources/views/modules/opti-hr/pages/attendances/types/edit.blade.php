<!-- Edit Department-->
<div class="modal fade" id="absenceTypeUpdate{{ $absenceType->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable modelUpdateFormContainer"
        id="absenceTypeUpdateForm{{ $absenceType->id }}">
        <form data-model-update-url="{{ route('absenceTypes.update', $absenceType->id) }}">
            {{-- @csrf --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="absenceTypeLabel">Modifier Type Absence</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="libelle" class="form-label required">Libellé</label>
                        <input type="text" class="form-control" id="libelle" name="libelle"
                            value="{{ $absenceType->label }}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description" cols="30" rows="3">{{ trim($absenceType->description) }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label required">Qualification</label>
                        <br>
                        <label class="fancy-radio">
                            <input type="radio" name="type" value="EXCEPTIONAL"
                                {{ $absenceType->type === 'EXCEPTIONAL' ? 'checked' : '' }}>
                            <span><i></i>Exceptionnelle</span>
                        </label>
                        <label class="fancy-radio">
                            <input type="radio" name="type" value="NORMAL"
                                {{ $absenceType->type === 'NORMAL' ? 'checked' : '' }}>
                            <span><i></i>Normal</span>
                        </label>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Déductible</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_deductible{{ $absenceType->id }}" 
                                name="is_deductible" value="1" {{ $absenceType->is_deductible ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_deductible{{ $absenceType->id }}">
                                Cette absence est déductible 
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary modelUpdateBtn" atl="Modifier Absence Type"
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