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
                <h3 class="fw-bold mb-0">Catégories Transactions</h3>
                <div class="col-auto d-flex w-sm-100">
                    {{-- <button type="button" class="btn btn-dark btn-set-task w-sm-100" data-bs-toggle="modal"
                        data-bs-target="#createAccountType"><i class="icofont-plus-circle me-2 fs-6"></i>Ajouter</button> --}}
                </div>
            </div>
        </div>
    </div> <!-- Row end  -->
    <div class="row clearfix g-3">
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body">
                    @if (!empty($transactionTypes) && $transactionTypes->isNotEmpty())
                        <table id="transTypesTable" class="table table-hover align-middle mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nom</th>


                                    <th>Relatif à</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactionTypes as $transactionType)
                                    <tr>
                                        <td>
                                            <a href="#" class="fw-bold text-primary">{{ $transactionType->name }}</a>
                                        </td>
                                        <td>
                                            {{ $transactionType->related_to }}
                                        </td>


                                        <td>
                                            @if ($transactionType->status === 'ACTIVE')
                                                <span class="badge bg-success">ACTIVE</span>
                                            @else
                                                <span class="badge bg-warning">INACTIVE</span>
                                            @endif

                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic outlined example">

                                                <button type="button" class="btn btn-dark btn-set-task w-sm-100"
                                                    disabled>Pas
                                                    D'action</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center p-5">
                            <img src="{{ asset('assets/images/no-data.svg') }}" class="img-fluid mx-size" alt="No Data">
                            <div class="mt-4 mb-2">
                                <span class="text-muted">No data to show</span>
                            </div>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#createAccountType"
                                class="btn btn-primary border lift mt-1">Créer</button>

                        </div>
                    @endif

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
    <script src="{{ asset('app-js/transaction/types.js') }}"></script>
@endpush
