@extends('modules.opti-hr.pages.base')
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
                        <h3>Soumettre Une Demande De Document</h3>
                    </div>
                    <form id="modelAddForm" data-model-add-url="{{ route('documents.save') }}">
                        @csrf
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label required" for="documentTypeSelect">Choisir </label>
                                <select class="form-select" id="documentTypeSelect" name="document_type">

                                    @foreach ($documentTypes as $documentType)
                                        <option value="{{ $documentType->id }}">{{ $documentType->label }}</option>
                                    @endforeach

                                </select>
                            </div>


                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="documentStartDate" class="form-label"> Du</label>
                                    <input type="date" class="form-control" id="documentStartDate" name="start_date">
                                </div>
                                <div class="col-sm-6">
                                    <label for="documentEndDate" class="form-label">Au</label>
                                    <input type="date" class="form-control" id="documentEndDate" name="end_date">
                                </div>
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
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
@endpush
