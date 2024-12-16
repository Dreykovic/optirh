<div class="modal fade" id="createAccountType" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="createprojectlLabel">Enregistrer Type de Compte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <form id="modelAddForm" data-model-add-url="{{ route('accounts.types.save') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label required">Nom du type de compte</label>
                        <input type="text" class="form-control" id="name" placeholder="Nom du type de compte"
                            name="name" required>
                    </div>



                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label for="account_number_prefix" class="form-label">Préfixe du numéro de compte</label>
                            <input type="text" class="form-control" id="account_number_prefix" placeholder="Ex : ACC"
                                name="account_number_prefix">
                        </div>
                        <div class="col">
                            <label for="next_account_number" class="form-label required">Prochain numéro de
                                compte</label>
                            <input type="number" class="form-control" id="next_account_number"
                                name="next_account_number" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label for="starting_account_number" class="form-label required">Numéro de compte
                                initial</label>
                            <input type="number" class="form-control" id="starting_account_number"
                                name="starting_account_number" required>
                        </div>
                        <div class="col">
                            <label for="currency" class="form-label required">Devise</label>



                            <select class="form-select" id="currency" name="currency">
                                <option value="FCFA" selected>FCFA</option>

                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label for="interest_method" class="form-label">Méthode de calcul des intérêts</label>
                            <input type="text" class="form-control" id="interest_method" name="interest_method">
                        </div>
                        <div class="col">
                            <label for="interest_rate" class="form-label">Taux d'intérêt (%)</label>
                            <input type="number" class="form-control" id="interest_rate" step="0.01"
                                name="interest_rate">
                        </div>
                    </div>



                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label for="interest_period" class="form-label">Période d'intérêt</label>

                            <select class="form-select" id="interest_period" name="interest_period">
                                <option value="3m" selected>Chaque Trimestre (3 mois)</option>
                                <option value="6m" selected>Chaque Semestre
                                    (6 mois)</option>


                            </select>
                        </div>
                        <div class="col">
                            <label for="minimum_balance_for_interest" class="form-label">Solde minimum pour toucher des
                                intérêts</label>
                            <input type="number" class="form-control" id="minimum_balance_for_interest" step="0.01"
                                name="minimum_balance_for_interest">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label for="allow_withdraw" class="form-label">Autoriser le retrait</label>
                            <select class="form-select" id="allow_withdraw" name="allow_withdraw">
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="minimum_deposit_amount" class="form-label">Montant minimum de dépôt</label>
                            <input type="number" class="form-control" id="minimum_deposit_amount" step="0.01"
                                name="minimum_deposit_amount">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label for="minimum_account_balance" class="form-label">Solde minimum du compte</label>
                            <input type="number" class="form-control" id="minimum_account_balance" step="0.01"
                                name="minimum_account_balance">
                        </div>
                        <div class="col">
                            <label for="maintenance_fee" class="form-label">Frais de gestion</label>
                            <input type="number" class="form-control" id="maintenance_fee" step="0.01"
                                name="maintenance_fee">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label for="maintenance_fee_posting_month" class="form-label">Mois de facturation des
                                frais de
                                gestion</label>


                            <select class="form-select" id="maintenance_fee_posting_month"
                                name="maintenance_fee_posting_month">
                                <option value="01">Janvier</option>
                                <option value="02">Février</option>
                                <option value="03">Mars</option>
                                <option value="04">Avril</option>
                                <option value="05">Mai</option>
                                <option value="06">Juin</option>
                                <option value="07">Juillet</option>
                                <option value="08">Aoùt</option>
                                <option value="09">Septembre</option>
                                <option value="10">Octobre</option>
                                <option value="11">Novembre</option>
                                <option value="12">Décembre</option>

                            </select>
                        </div>
                        <div class="col">
                            <label for="status" class="form-label required">Statut</label>

                            <select class="form-select" id="status" name="status">
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="DESACTIVE">DESACTIVE</option>
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
</div>
