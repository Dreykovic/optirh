<table id="accountsTable" class="table table-hover align-middle mb-0" style="width:100%">
    <thead>
        <tr>
            <th>N° Compte</th>
            <th>Type</th>
            <th>Solde</th>
            <th> Date De création</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($accounts as $account)
            <tr>
                <td>
                    <a href="#" class="fw-bold text-secondary">{{ $account->account_no }}</a>
                </td>
                <td>
                    {{ $account->account_type->name }}
                </td>
                <td>
                    <span class="montant"> {{ $account->current_balance }}</span>
                </td>
                <td>
                    @formatDate($account->creation_date)
                </td>
                <td>
                    @if ($account->state === 'ACTIVE')
                        <span class="badge bg-success">ACTIVE</span>
                    @else
                        <span class="badge bg-warning">INACTIVE</span>
                    @endif

                </td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic outlined example">

                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#account{{ $account->account_no }}Detail">
                            <i class="icofont-eye text-success"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary deleterow">
                            <i class="icofont-ui-delete text-danger"></i>
                        </button>
                        <a type="button" href="{{ route('accounts.cotisations', $account->id) }}"
                            class="btn btn-outline-secondary deleterow">
                            <i class="icofont-eye text-indo"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @include('components.account.detail')
        @endforeach
    </tbody>
</table>
