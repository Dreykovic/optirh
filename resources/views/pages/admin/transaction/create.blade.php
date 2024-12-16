<form id="modelAddForm" data-model-add-url="{{ route('transactions.store') }}" class="addTransactionForm">
    @csrf
    <input type="hidden" id="employee_id" name="employee_id" value="{{ auth()->id() }}">



    <div class="row g-3 mb-3">

        <div class="col-sm-12">
            <label for="client_id" class="form-label">Client</label>
            <select class="form-select" id="client_id" name="client_id" required>

                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-12">

            <label for="type" class="form-label">Compte</label>
            <select class="form-select" id="account_id" name="account_id" disabled required>

            </select>

        </div>
        <div class="col-sm-12">
            <label for="amount" class="form-label">Montant</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
        </div>
        <div class="col-sm-12">
            <label for="related_to" class="form-label">Debit/Credit</label>
            <select class="form-select" id="related_to" name="related_to">
                <option value="">Choisir </option>
                <option value="DEBIT">Débit </option>
                <option value="CREDIT">Crédit </option>
            </select>
        </div>
        <div class="col-sm-12">

            <label for="type" class="form-label">Type</label>
            <select class="form-select" id="transaction_type_id" name="transaction_type_id" disabled required>

            </select>

        </div>
    </div>

    <button type="submit" class="btn btn-primary" atl="addTransaction" id="modelAddBtn" data-bs-dismiss="modal">
        <span class="normal-status">
            Ajouter
        </span>
        <span class="indicateur d-none">
            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
            Un Instant...
        </span>
    </button>
</form>
