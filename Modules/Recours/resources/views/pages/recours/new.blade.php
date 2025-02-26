@extends('pages.admin.base')
@section('plugins-style')
@endsection
@section('admin-content')

<div class='style="height: 100vh;' id="modalAppeal">
    <div class='modelUpdateFormContainer'  id='AppealForm' class=''>
        <h3 class='text-center'>Nouveau Dépôt</h3>
        <form data-model-update-url="{{ route('recours.store') }}" class='w-50 m-auto shadow-sm p-4 rounded'>
            @csrf
            <input type="hidden" name="_method" value="POST">
            
            <div class="mb-3 ">
                <label for="dac" class="form-label fs-5">Marché N° : </label>
                <div class='d-flex justify-content-between align-items-center'>
                <input class="form-control mx-2" list="dacOptions" id="dacDataList" placeholder="Rechercher..." autocomplete="off">
                <input type="hidden" name="dac_id" id="selectedDacId">

                <datalist id="dacOptions">
                    @forelse($dacs as $dac)
                        <option value="{{$dac->reference}}" data-id="{{$dac->id}}"></option>
                    @empty
                        <option value="">Aucun requérant trouvé</option>
                    @endforelse                   
                </datalist>
                    <!-- <input type="text" class="form-control mx-2" id="dac" name="dac"> -->
                    <button type="button" class="btn btn-primary" id="addDac"><i class="icofont-plus-circle fs-5"></i></button>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="type_recours" class="form-label fs-5">Contestation de : </label>
                <!-- <input type="text" class="form-control" id="type_recours" name="type_recours"> -->
                <select name="type" id="type_recours" class="form-control">
                    <option value="RESULTS" selected>Ses résultats Provisoirs</option>
                    <option value="DAC">Son DAC</option>
                    <option value="PROCESS">Sa Procédure/ Son déroulement</option>
                    <option value="OTHERS">Autre</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="date_depot" class="form-label fs-5">Date & Heure Dépôt : </label>
                <input type="datetime-local" class="form-control" id="date_depot" value="{{ now()->format('Y-m-d\TH:i') }}" name="date_depot">
            </div>

            <div class="mb-3">
                <label for="objet" class="form-label fs-5">Objet : </label>
                <input type="text" class="form-control" id="objet" name="object">
            </div>

            <div class="mb-3">
                <label for="requérant" class="form-label fs-5">Requérant : </label>
                <div class='d-flex justify-content-between align-items-center'>

                <input class="form-control mx-2" list="applicantOptions" id="applicantDataList" placeholder="Rechercher..." autocomplete="off">
                <input type="hidden" name="applicant_id" id="selectedApplicantId">

                <datalist id="applicantOptions">
                    @forelse($applicants as $applicant)
                        <option value="{{$applicant->name}}" data-id="{{$applicant->id}}"></option>
                    @empty
                        <option value="">Aucun requérant trouvé</option>
                    @endforelse                   
                </datalist>
                    <!-- <input type="text" class="form-control mx-2" id="requérant" name="requérant"> -->
                    <button type="button" class="btn btn-primary" id="addRequerant"><i class="icofont-plus-circle fs-5"></i></button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary  modelUpdateBtn w-25 fs-5" atl="Save applicant"
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
                <label for="ref" class='fs-5'>Référence</label>
                <input type="text" class="form-control mb-2" id="ref" name='reference'>
                <label for="objetDac" class='fs-5'>Objet</label>
                <input type="text" class="form-control mb-2" id="objetDac" name='object'>
                <label for="ac" class='fs-5'>AC</label>
                <input type="text" class="form-control" id="ac" name='ac'>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary  modelUpdateBtn fs-5" atl="Save dac"
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
                <label for="nom" class='fs-5'>Dénomination</label>
                <input type="text" class="form-control mb-2" id="nom" name='name'>
                <label for="nif" class='fs-5'>NIF</label>
                <input type="text" class="form-control mb-2" id="nif" name='nif'>
                <label for="phone" class='fs-5'>Téléphone</label>
                <input type="text" class="form-control mb-2" id="phone" name='phone_number'>
                <label for="adresse" class='fs-5'>Adresse</label>
                <input type="text" class="form-control" id="adresse" name='address'>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary  modelUpdateBtn fs-5" atl="Save applicant"
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
<!-- 
<script>
document.addEventListener("DOMContentLoaded", function () {
    let input = document.getElementById("applicantDataList");
    let hiddenInput = document.getElementById("selectedApplicantId");
    let dataList = document.getElementById("applicantOptions");

    input.addEventListener("input", function () {
        let selectedOption = [...dataList.options].find(option => option.value === input.value);
        if (selectedOption) {
            hiddenInput.value = selectedOption.getAttribute("data-id"); // Stocke l'ID
        } else {
            hiddenInput.value = ""; // Réinitialise si aucune correspondance
        }
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let input = document.getElementById("dacDataList");
    let hiddenInput = document.getElementById("selectedDacId");
    let dataList = document.getElementById("dacOptions");

    input.addEventListener("input", function () {
        let selectedOption = [...dataList.options].find(option => option.value === input.value);
        if (selectedOption) {
            hiddenInput.value = selectedOption.getAttribute("data-id"); // Stocke l'ID
        } else {
            hiddenInput.value = ""; // Réinitialise si aucune correspondance
        }
    });
});
</script> -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    function handleDataList(inputId, hiddenInputId, dataListId) {
        let input = document.getElementById(inputId);
        let hiddenInput = document.getElementById(hiddenInputId);
        let dataList = document.getElementById(dataListId);

        input.addEventListener("input", function () {
            let selectedOption = [...dataList.options].find(option => option.value === input.value);
            if (selectedOption) {
                hiddenInput.value = selectedOption.getAttribute("data-id"); // Stocke l'ID
            } else {
                hiddenInput.value = ""; // Réinitialise si aucune correspondance
            }
        });
    }

    // Appel de la fonction pour chaque champ
    handleDataList("applicantDataList", "selectedApplicantId", "applicantOptions");
    handleDataList("dacDataList", "selectedDacId", "dacOptions");
});
</script>

@endpush