@extends('pages.admin.base')
@section('plugins-style')
@endsection
@section('admin-content')

<div class='style="height: 100vh;'>
    <!-- <h3 class='m-auto'>Nouveau Recours</h3> -->
    <form action="" method="POST" class='w-50 m-auto shadow-sm p-4 rounded'>
        @csrf
        
        <div class="mb-3 ">
            <label for="dac" class="form-label fs-5">Marché N° : </label>
            <div class='d-flex justify-content-between align-items-center'>
            <input class="form-control mx-2" list="dacOptions" id="dacDataList" placeholder="Rechercher..." name='dac_id'>
                <datalist id="dacOptions">
                @forelse($dacs as $dac)
                    <option value="{{$dac->id}}">{{$dac->reference}}</option>
                @empty
                    <option value="">Aucun dac trouvé</option>
                @endforelse
                </datalist>
                <!-- <input type="text" class="form-control mx-2" id="dac" name="dac"> -->
                <button type="button" class="btn btn-primary" id="addDac">+</button>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="type_recours" class="form-label fs-5">Recours Contre : </label>
            <!-- <input type="text" class="form-control" id="type_recours" name="type_recours"> -->
             <select name="type" id="type_recours" class="form-control">
                <option value="RESULTS" selected>Ses résultats Provisoirs</option>
                <option value="DAC">Son DAC</option>
                <option value="PROCESS">Sa Procédure/ Son déroulement</option>
                <option value="OTHERS">Autre</option>
             </select>
        </div>

        <div class="mb-3">
            <label for="date_depot" class="form-label fs-5">Date Heure Dépôt : </label>
            <input type="datetime-local" class="form-control" id="date_depot" value="{{ now()->format('Y-m-d\TH:i') }}" name="date_depot">
        </div>

        <div class="mb-3">
            <label for="objet" class="form-label fs-5">Objet : </label>
            <input type="text" class="form-control" id="objet" name="object">
        </div>

        <div class="mb-3">
            <label for="requérant" class="form-label fs-5">Requérant : </label>
            <div class='d-flex justify-content-between align-items-center'>

            <input class="form-control mx-2" list="applicantOptions" id="applicantDataList" placeholder="Rechercher..." name='applicant_id'>
                <datalist id="applicantOptions">
                    @forelse($applicants as $applicant)
                        <option value="{{$applicant->id}}">{{$applicant->name}}</option>
                    @empty
                        <option value="">Aucun requerant trouvé</option>
                    @endforelse                   
                </datalist>
                <!-- <input type="text" class="form-control mx-2" id="requérant" name="requérant"> -->
                <button type="button" class="btn btn-primary" id="addRequerant">+</button>
            </div>
        </div>
        <button type="submit" class="btn btn-success m-auto">Valider</button>
    </form>
</div>

<!-- Modal DAC -->
<div class="modal fade" id="modalDac" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modelUpdateFormContainer" id='DacForm'>
        <form data-model-update-url="{{ route('dac.store') }}">
            @csrf 
            <input type="hidden" name="_method" value="POST">

            <div class="modal-header">
                <h5 class="modal-title">Ajouter un DAC</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="ref">Référence</label>
                <input type="text" class="form-control" id="ref" name='reference'>
                <label for="objetDac">Objet</label>
                <input type="text" class="form-control" id="objetDac" name='object'>
                <label for="ac">AC</label>
                <input type="text" class="form-control" id="ac" name='ac'>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary  modelUpdateBtn" atl="Save dac"
                data-bs-dismiss="modal">
                <span class="normal-status">
                    Valider
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

<!-- Modal Requérant -->
<div class="modal fade" id="modalRequerant" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modelUpdateFormContainer" id='ApplicantForm'>
    <form data-model-update-url="{{ route('applicant.store') }}">
    @csrf 
    <input type="hidden" name="_method" value="POST">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Requérant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="nom">Dénomination</label>
                <input type="text" class="form-control" id="nom" name='name'>
                <label for="nif">NIF</label>
                <input type="text" class="form-control" id="nif" name='nif'>
                <label for="phone">Téléphone</label>
                <input type="text" class="form-control" id="phone" name='phone_number'>
                <label for="adresse">Adresse</label>
                <input type="text" class="form-control" id="adresse" name='address'>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary  modelUpdateBtn" atl="Save applicant"
                data-bs-dismiss="modal">
                <span class="normal-status">
                    Valider
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


@endsection
@push('plugins-js')
@endpush
@push('js')
<script src="{{ asset('app-js/crud/put.js') }}"></script>

<script>
    document.getElementById('addDac').addEventListener('click', function() {
        var modal = new bootstrap.Modal(document.getElementById('modalDac'));
        modal.show();
    });
    document.getElementById('addRequerant').addEventListener('click', function() {
        var modal = new bootstrap.Modal(document.getElementById('modalRequerant'));
        modal.show();
    });
</script>
@endpush