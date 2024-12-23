@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}>
@endsection
@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div
                class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">Jours Fériés</h3>
                <div class="col-auto d-flex w-sm-100">
                    <button type="button" class="btn btn-dark btn-set-task w-sm-100" data-bs-toggle="modal"
                        data-bs-target="#addHolidayModal"><i class="icofont-plus-circle me-2 fs-6"></i>Ajouter</button>
                </div>
            </div>
        </div>
    </div> <!-- Row end  -->
    <div class="row clearfix g-3">
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body">
                    <table id="holidaysTable" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Jour Férié</th>
                                <th> Date</th>
                                <th>Nom</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($holidays as $index => $holiday)
                                @php
                                    $tdClass =
                                        Carbon\Carbon::parse($holiday->date)->isPast() &&
                                        !Carbon\Carbon::parse($holiday->date)->isToday()
                                            ? 'text-danger'
                                            : (Carbon\Carbon::parse($holiday->date)->isToday()
                                                ? 'text-success'
                                                : '');
                                @endphp

                                <tr class="parent">
                                    <td class="{{ $tdClass }}">{{ $index }}</td>
                                    <td class="{{ $tdClass }}">@dayOfWeek($holiday->date)</td>
                                    <td class="{{ $tdClass }}">@formatDateOnly($holiday->date)</td>
                                    <td class="{{ $tdClass }} model-value">{{ $holiday->name }}</td>

                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#updateHolidayModal{{ $holiday->id }}"><i
                                                    class="icofont-edit text-success"></i>
                                            </button>

                                            <button type="button" class="btn btn-outline-secondary modelDeleteBtn"
                                                data-model-action="delete"
                                                data-model-delete-url={{ route('holidays.destroy', $holiday->id) }}
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
                                        @include('pages.admin.attendances.holidays.edit')
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="5">

                                        <x-no-data color="warning" text="Aucun Jour Férié Enregistré" />
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- Row End -->
    @include('pages.admin.attendances.holidays.create')
@endsection
@push('plugins-js')
    <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
@endpush
@push('js')
    <script src="{{ asset('app-js/attendances/holidays/table.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
@endpush
