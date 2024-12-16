<div class="modal fade" id="addAccount" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title  fw-bold" id="leaveaddLabel"> Ajouter un Compte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="modelAddForm" data-model-add-url="{{ route('accounts.save') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Type De Compte</label>
                        <select class="form-select" id="account_type" name="account_type" required>
                            <option value=""></option>

                            @foreach ($accountTypes as $accountType)
                                <option value="{{ $accountType->id }}">
                                    {{ $accountType->name . ' (Solde Initiale Min: ' . $accountType->minimum_account_balance . ')' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="date">Créé le</label>

                        <input type="date" class="form-control" placeholder="Créé le" aria-label="creation_date"
                            id="date" aria-describedby="basic-addon1" name='creation_date'
                            value="{{ $today }}" max="{{ $today }}">

                    </div>

                    <div class="mb-3">
                        <label for="initial_balance" class="form-label">Solde initiale</label>
                        <input type="number" step="0.01" class="form-control" id="initial_balance"
                            name="initial_balance" required>
                    </div>

                    <div class="mb-3">
                        <label for="fees" class="form-label">Frais</label>
                        <input type="number" step="0.01" class="form-control" id="fees" name="fees">
                    </div>


                    <input type="hidden" id="employee_id" name="employee_id" value="{{ auth()->id() }}">

                    <div class="mb-3">
                        <label for="client_id" class="form-label">Client</label>
                        <select class="form-select" id="client_id" name="client_id">
                            <option value="">Aucun</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="assistant_id" class="form-label">Assistant (Si Achat Carnet)</label>
                        <select class="form-select" id="assistant_id" name="assistant_id">
                            <option value="">Aucun</option>
                            @foreach ($assistants as $assistant)
                                <option value="{{ $assistant->id }}">{{ $assistant->username }}
                                </option>
                            @endforeach
                        </select>
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
