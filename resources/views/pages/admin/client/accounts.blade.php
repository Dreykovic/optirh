<div class="row align-items-center">
    <div class="border-0 mb-4">
        <div
            class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
            <h3 class="fw-bold mb-0">Comptes</h3>
            <div class="col-auto d-flex w-sm-100">
                <button type="button" class="btn btn-dark btn-set-task w-sm-100" data-bs-toggle="modal"
                    data-bs-target="#addClientAccount"><i class="icofont-plus-circle me-2 fs-6"></i>Nouveau
                    compte</button>
            </div>
        </div>
    </div>
</div> <!-- Row end  -->
<div class="row clearfix g-3">
    <div class="col-sm-12">
        <div class="card mb-3">
            <div class="card-body">
                @if (!empty($client->accounts) && $client->accounts->isNotEmpty())
                    @php
                        $accounts = $client->accounts;
                    @endphp
                    {{-- @include('components.account.account-list') --}}
                    <x-account.list :accounts="$accounts" />
                @else
                    <div class="text-center p-5">
                        <img src="{{ asset('assets/images/no-data.svg') }}" class="img-fluid mx-size" alt="No Data">
                        <div class="mt-4 mb-2">
                            <span class="text-muted">No data to show</span>
                        </div>
                        <button type="button" class="btn btn-primary border lift mt-1" data-bs-toggle="modal"
                            data-bs-target="#addClientAccount"><i
                                class="icofont-plus-circle me-2 fs-6"></i>Créer</button>

                    </div>
                @endif

            </div>
        </div>
    </div>
</div><!-- Row End -->


<!-- Add Account-->
@include('pages.admin.client.add-account')
