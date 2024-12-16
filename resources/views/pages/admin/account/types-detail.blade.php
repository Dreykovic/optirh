<!-- Edit Bank Personal Info-->
<div class="modal fade" id="accountType{{ $accountType->id }}Detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title  fw-bold" id="edit2Label"> Détail Type Compte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled mb-0">
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold">Nom</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $accountType->name }}</span>
                        </div>
                    </li>



                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold">Préfixe du numéro de compte</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $accountType->account_number_prefix }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold">Numéro de compte initial</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $accountType->starting_account_number }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold">Prochain numéro de compte</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $accountType->next_account_number }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold">Devise</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $accountType->currency }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold"> Taux d’intérêt</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $accountType->interest_rate }}</span>
                        </div>
                    </li>


                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold"> Méthode de calcul des intérêts</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $accountType->interest_method }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold"> Période des intérêts</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $accountType->interest_period }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold"> Solde minimum pour toucher des intérêts</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $accountType->minimum_balance_for_interest }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold">Autoriser le retrait</span>
                        </div>
                        <div class="col-6">
                            @if ($accountType->allow_withdraw == true)
                                <span class="badge bg-success">Oui</span>
                            @else
                                <span class="badge bg-warning">Non</span>
                            @endif
                        </div>
                    </li>

                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold">Montant minimum de dépôt</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $accountType->minimum_deposit_amount }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold">Frais de gestion</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $accountType->maintenance_fee }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold">Mois de facturation des frais de gestion</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $accountType->maintenance_fee_posting_month }}</span>
                        </div>
                    </li>
                    <li class="row flex-wrap mb-3">
                        <div class="col-6">
                            <span class="fw-bold">Solde minimum du compte</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">{{ $accountType->minimum_account_balance }}</span>
                        </div>
                    </li>

                    <li class="row flex-wrap mb-5">
                        <div class="col-6">
                            <span class="fw-bold">Statut</span>
                        </div>
                        <div class="col-6">
                            @if ($accountType->status === 'ACTIVE')
                                <span class="badge bg-success">ACTIVE</span>
                            @else
                                <span class="badge bg-warning">INACTIVE</span>
                            @endif

                        </div>
                    </li>

                    <li class="row flex-wrap">
                        <div class="col-6">
                            <span class="fw-bold">Date De Création</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">@formatDate($accountType->created_at)</span>
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
