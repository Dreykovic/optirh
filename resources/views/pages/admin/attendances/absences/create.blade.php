@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}>
    <link href={{ asset('assets/plugins/select2/css/select2.min.css') }} rel="stylesheet">
@endsection

@section('admin-content')
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-12">
            <div class="card p-xl-5 p-lg-4 p-0">
                <div class="deadline-form">
                    <div class="card-header">
                        <h3>Soumettre Une Demande d'Absence</h3>
                    </div>
                    <form id="modelAddForm" data-model-add-url="{{ route('absences.save') }}">
                        @csrf
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label required" for="transactionTypeSelect">Choisir </label>
                                <select class="form-select" id="transactionTypeSelect" name="absence_type">

                                    @foreach ($absenceTypes as $absenceType)
                                        <option value="{{ $absenceType->id }}">{{ $absenceType->label }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="absenceAddress" class="form-label">Adresse De Congé</label>
                                <input type="text" class="form-control" id="absenceAddress" name="address"
                                    value="{{ auth()->user()->employee->address1 }}">
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="absenceStartDate" class="form-label">Absence Du</label>
                                    <input type="date" class="form-control" id="absenceStartDate" name="start_date">
                                </div>
                                <div class="col-sm-6">
                                    <label for="absenceEndDate" class="form-label">Au</label>
                                    <input type="date" class="form-control" id="absenceEndDate" name="end_date">
                                </div>
                            </div>
                            <div>
                                <span class=""> Soit: <strong><span class="days-requested"></span></strong></span>
                            </div>

                            <div class="mb-3">
                                <label for="absenceReason" class="form-label">Raisons de l’absence</label>
                                <textarea class="form-control" id="absenceReason" rows="3" name="reasons"></textarea>
                            </div>
                            <div class="alert alert-info" role="alert">
                                Vous devez fournir un justificatif pour les permissions exceptionnelles, au plus tard huit
                                jours
                                après la date d’absence.
                            </div>
                        </div>
                        <div class="card-footer modal-footer ">
                            <button type="reset" class="btn btn-secondary">Annuler</button>
                            <button type="submit" class="btn btn-primary" atl="Ajouter Absence Requête" id="modelAddBtn">
                                <span class="normal-status">
                                    Soumettre
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
    </div> <!-- Row end  -->
@endsection
@push('plugins-js')
    <script src={{ asset('assets/plugins/select2/js/select2.min.js') }}></script>
@endpush
@push('js')
    <script src="{{ asset('app-js/attendances/absences/create.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
@endpush
