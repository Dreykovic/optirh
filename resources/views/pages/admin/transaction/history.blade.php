@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
    <link href={{ asset('assets/plugins/select2/css/select2.min.css') }} rel="stylesheet">
@endsection
@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div
                class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold py-3 mb-0">Historiques des transactions</h3>

            </div>
        </div>
    </div> <!-- Row end  -->
    <div class="row clearfix g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header py-3 d-flex justify-content-between bg-transparent border-bottom-0">
                    <h6 class="mb-0 fw-bold ">Enregistrer Une Transaction</h6>
                </div>
                <div class="card-body">
                    @include('pages.admin.transaction.create')
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <table id="operationTable" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Compte</th>
                                <th>Etat</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td>
                                        <img class="avatar rounded-circle" src="{{ asset('assets/images/xs/avatar1.jpg') }}"
                                            alt="">
                                        <span class="fw-bold ms-1">{{ $transaction->account->client->name }}</span>
                                    </td>
                                    <td>
                                        @formatDateOnly($transaction->transaction_date)
                                    </td>
                                    <td class="montant text-right">{{ $transaction->amount }}</td>
                                    <td class="">{{ $transaction->account->account_no }}</td>
                                    <td class="">
                                        @if ($transaction->status === 'COMPLETED')
                                            <span class="badge bg-success">Succès</span>
                                        @else
                                            <span class="badge bg-danger">Echoué</span>
                                        @endif

                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#expedit"><i class="icofont-edit text-success"></i></button>
                                            <button type="button" class="btn btn-outline-secondary deleterow"><i
                                                    class="icofont-ui-delete text-danger"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                Aucune Opératon enregistrer
                            @endforelse


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- Row End -->
@endsection
@push('plugins-js')
    <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
    <script src={{ asset('assets/plugins/select2/js/select2.min.js') }}></script>
@endpush
@push('js')
    <script src={{ asset('app-js/transaction/history.js') }}></script>
    <script src={{ asset('app-js/crud/post.js') }}></script>
@endpush
