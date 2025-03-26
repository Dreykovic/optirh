<div class="modal fade" id="addDecisionModal" tabindex="-1" aria-labelledby="addDecisionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <form class="modal-content" id="modelAddForm" data-model-add-url="{{ route('decisions.save') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addDecisionModalLabel">
                        {{ 'Créer une nouvelle décision' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="number" class="form-label">Numéro de décision*</label>
                        <input type="text" class="form-control" id="number" name="number"
                            value="{{ old('number') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="year" class="form-label">Année*</label>
                        <input type="text" class="form-control" id="year" name="year"
                            value="{{ date('Y') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="reference" class="form-label">Référence</label>
                        <input type="text" class="form-control" id="reference" name="reference"
                            value="{{ old('reference') }}">
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Date*</label>
                        <input type="date" class="form-control" id="date" name="date"
                            value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="pdf" class="form-label">Document PDF</label>
                        <input type="file" class="form-control" id="pdf" name="pdf" accept=".pdf">
                    </div>

                    <input type="hidden" name="state" value="current">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>

                    <button type="submit" class="btn btn-primary " atl="Ajouter Décision" id="modelAddBtn"
                        data-bs-dismiss="modal">
                        <span class="normal-status">
                            {{ 'Enregistrer' }}
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
