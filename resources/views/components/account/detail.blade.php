<!-- Edit Bank Personal Info-->
<div class="modal fade" id="account{{ $account->account_no }}Detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title  fw-bold" id="edit2Label"> Détail Compte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled mb-0">
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold">Numéro De Compte</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $account->account_no }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold">Propriétaire</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $account->client->name }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold">Type Compte</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $account->account_type->name }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-5">
                        <div class="col-6">
                            <span class="fw-bold">Solde</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted montant">{{ $account->current_balance }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-5">
                        <div class="col-6">
                            <span class="fw-bold">Frais</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted montant">{{ $account->fees }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-5">
                        <div class="col-6">
                            <span class="fw-bold">Statut</span>
                        </div>
                        <div class="col-6">
                            @if ($account->state === 'ACTIVE')
                                <span class="badge bg-success">ACTIVE</span>
                            @else
                                <span class="badge bg-warning">INACTIVE</span>
                            @endif

                        </div>
                    </li>
                    @if ($account->employee)
                        <li class="row flex-wrap">
                            <div class="col-6">
                                <span class="fw-bold">Créer par</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted">{{ $account->employee->username }}</span>
                            </div>
                        </li>
                    @endif

                    @if ($account->assistant)
                        <li class="row flex-wrap">
                            <div class="col-6">
                                <span class="fw-bold">Assistant</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted">{{ $account->assistant->username }}</span>
                            </div>
                        </li>
                    @endif
                    <li class="row flex-wrap">
                        <div class="col-6">
                            <span class="fw-bold">Date De Création</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">@formatDate($account->creation_date)</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
