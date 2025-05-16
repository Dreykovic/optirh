@extends('modules.opti-hr.pages.base')
@section('plugins-style')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}>
@endsection
@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div
                class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">Types Absences</h3>
                @can('voir-un-all')
                    <div class="col-auto d-flex w-sm-100">
                        <button type="button" class="btn btn-dark btn-set-task w-sm-100" data-bs-toggle="modal"
                            data-bs-target="#absenceTypeAdd"><i class="icofont-plus-circle me-2 fs-6"></i>Ajouter</button>
                    </div>
                @endcan
            </div>
        </div>
    </div> <!-- Row end  -->
    <div class="row clearfix g-3">
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="table-responsive">

                        <table id="absenceTypesTable" class="table table-hover align-middle mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Libelle</th>
                                    <th>Description</th>
                                    <th>Déductible</th>
                                        <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($absenceTypes as $index => $absenceType)
                                    <tr class="parent">
                                        <td>
                                            <span class="fw-bold">{{ $index + 1 }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold ms-1 model-value">{{ $absenceType->label }}</span>
                                            <!-- Libellé du type d'absence -->
                                        </td>
                                        <td>
                                            {{ $absenceType->description }} <!-- Description du type d'absence -->
                                        </td>
                                        <td>
                                            @if($absenceType->is_deductible)
                                                <span class="badge bg-success">Oui</span>
                                            @else
                                                <span class="badge bg-danger">Non</span>
                                            @endif
                                        </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic outlined example">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#absenceTypeUpdate{{ $absenceType->id }}"><i
                                                            class="icofont-edit text-success"></i></button>

                                                    <button type="button" class="btn btn-outline-secondary modelDeleteBtn"
                                                        data-model-action="delete"
                                                        data-model-delete-url={{ route('absenceTypes.destroy', $absenceType->id) }}
                                                        data-model-parent-selector="tr.parent">
                                                        <span class="normal-status">
                                                            <i class="icofont-ui-delete text-danger"></i>
                                                        </span>
                                                        <span class="indicateur d-none">
                                                            <span class="spinner-grow spinner-grow-sm" role="status"
                                                                aria-hidden="true"></span>

                                                        </span>
                                                    </button>

                                                </div>
                                            </td>
                                    </tr>
                                        @include('modules.opti-hr.pages.attendances.types.edit')
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <x-no-data color="warning" text="Aucun Type Absence Enregistré" />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Row End -->
    @can('voir-un-all')
        @include('modules.opti-hr.pages.attendances.types.create')
    @endcan
@endsection
@push('plugins-js')
    <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
@endpush
@push('js')
    <script src="{{ asset('app-js/attendances/types/table.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
@endpush