<div class="tab-pane fade show {{ $className ?? '' }}" id="{{ $tabId }}" role="tabpanel">
    <div class="row g-3 gy-5 py-3 row-deck">
        @foreach ($transactions as $transaction)
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mt-5">
                            <div class="lesson_name">
                                {{-- <div class="project-block light-info-bg">
                                    <i class="icofont-dashboard-web"></i>
                                </div> --}}
                                <span
                                    class="small text-muted project_name fw-bold">{{ $transaction->type == 'WITHDRAWAL' ? 'Retrait' : ($transaction->type == 'DEPOSIT' ? 'Dépôt' : ($transaction->type == 'CONTRIBUTION' ? 'Cotisation' : ($transaction->type == 'REFUND' ? 'Remboursement' : ($transaction->type == 'INFLOW' ? 'Entrée' : 'Sortie')))) }}
                                </span>
                                <h6 class="mb-0 fw-bold fs-6 mb-2"><i class="icofont-bill-alt"></i>
                                    {{ $transaction->amount }}</h6>
                            </div>
                            <div class="btn-group" role="group" aria-label="Basic outlined example">
                                <button type="button" class="btn btn-outline-secondary editTransactionButton"
                                    data-bs-toggle="modal" data-bs-target="#editTransactionModal"
                                    data-transaction='@json($transaction)'>
                                    <i class="icofont-edit text-success"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                    data-bs-target="#deleteproject">
                                    <i class="icofont-ui-delete text-danger"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row g-2 pt-4">
                            <div class="col-12">
                                <div class="d-flex align-items-center">
                                    <i class="icofont-bank-alt"></i>
                                    <span class="ms-2">Compte :
                                        {{ $transaction->account_type == 'CREDIT' ? 'Crédit' : ($transaction->account_type == 'SAVINGS' ? 'Épargne' : ($transaction->account_type == 'TONTINE' ? 'Tontine' : 'Principal')) }}
                                    </span>
                                </div>
                                <div class="col-12 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="icofont-sand-clock"></i>
                                        <span
                                            class="ms-2 badge {{ $transaction->status == 'COMPLETED' ? 'bg-success' : '' }}">
                                            {{ $transaction->status }}
                                        </span>
                                    </div>
                                </div>
                                @if ($transaction->assistant)
                                    <div class="col-12 py-2">
                                        <div class="d-flex align-items-center">
                                            <i class="icofont-businessman"></i>
                                            <span class="ms-2">
                                                Assistant : {{ $transaction->assistant->firstname }}
                                                {{ $transaction->assistant->lastname }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-12 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="icofont-money-bag"></i>
                                        <span class="ms-2">Type de transaction :
                                            {{ $transaction->type == 'WITHDRAWAL' ? 'Retrait' : ($transaction->type == 'DEPOSIT' ? 'Dépôt' : ($transaction->type == 'CONTRIBUTION' ? 'Cotisation' : ($transaction->type == 'REFUND' ? 'Remboursement' : ($transaction->type == 'INFLOW' ? 'Entrée' : 'Sortie')))) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
