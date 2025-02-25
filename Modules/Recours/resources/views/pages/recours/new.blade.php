@extends('pages.admin.base')
@section('plugins-style')
@endsection
@section('admin-content')

<div class='style="height: 100vh;'>
    <!-- <h3 class='m-auto'>Nouveau Recours</h3> -->
    <form action="" method="POST" class='w-50 m-auto shadow-sm p-4 rounded'>
        @csrf
        
        <div class="mb-3 ">
            <label for="dac" class="form-label fs-5">Dac</label>
            <div class='d-flex justify-content-between align-items-center'>
                <input type="text" class="form-control mx-2" id="dac" name="dac">
                <button type="button" class="btn btn-primary" id="addDac">+</button>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="type_recours" class="form-label fs-5">Type Recours</label>
            <input type="text" class="form-control" id="type_recours" name="type_recours">
        </div>

        <div class="mb-3">
            <label for="date_depot" class="form-label fs-5">Date Heure Dépôt</label>
            <input type="datetime-local" class="form-control" id="date_depot" name="date_depot">
        </div>

        <div class="mb-3">
            <label for="objet" class="form-label fs-5">Objet</label>
            <input type="text" class="form-control" id="objet" name="objet">
        </div>

        <div class="mb-3">
            <label for="requérant" class="form-label fs-5">Requérant</label>
            <div class='d-flex justify-content-between align-items-center'>
                <input type="text" class="form-control mx-2" id="requérant" name="requérant">
                <button type="button" class="btn btn-primary" id="addRequerant">+</button>
            </div>
        </div>
        <button type="submit" class="btn btn-success m-auto">Valider</button>
    </form>
</div>

<!-- Modal DAC -->
<div class="modal fade" id="modalDac" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un DAC</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="ref">Réf</label>
                <input type="text" class="form-control" id="ref">
                <label for="objetDac">Objet</label>
                <input type="text" class="form-control" id="objetDac">
                <label for="ac">AC</label>
                <input type="text" class="form-control" id="ac">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveDac">Valider</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Requérant -->
<div class="modal fade" id="modalRequerant" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Requérant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="nom">Dénomination</label>
                <input type="text" class="form-control" id="nom">
                <label for="nif">NIF</label>
                <input type="text" class="form-control" id="nif">
                <label for="phone">Téléphone</label>
                <input type="text" class="form-control" id="phone">
                <label for="adresse">Adresse</label>
                <input type="text" class="form-control" id="adresse">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveRequerant">Valider</button>
            </div>
        </div>
    </div>
</div>


@endsection
@push('plugins-js')
@endpush
@push('js')
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