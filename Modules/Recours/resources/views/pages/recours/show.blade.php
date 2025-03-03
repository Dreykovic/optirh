@extends('pages.admin.base')
@section('plugins-style')
@endsection
@section('admin-content')

<!-- <div class="card shadow">
        <div class="card-body">
            <h4 class="text-center mb-4">Détails du Recours</h4>

           
            <div class="border p-3 mb-3">
                <h5 class="fw-bold text-primary">Recours</h5>
                <p><strong>Étude :</strong> ...</p>
                <p><strong>Décision :</strong> ...</p>
                <p><strong>Délai :</strong> ...</p>
                <p><strong>Type :</strong> ...</p>
                <p><strong>Dépôt le :</strong> ...</p>
                <p><strong>Objet :</strong> ...</p>
            </div>

           
            <div class="border p-3 mb-3">
                <h5 class="fw-bold text-primary">Marché</h5>
                <p><strong>N° :</strong> ...</p>
                <p><strong>Objet :</strong> ...</p>
                <p><strong>A C :</strong> ...</p>
            </div>

            
            <div class="border p-3 mb-3">
                <h5 class="fw-bold text-primary">Requérant</h5>
                <p><strong>Dénomination :</strong> ...</p>
                <p><strong>Adresse :</strong> ...</p>
                <p><strong>Tél :</strong> ...</p>
            </div>

           
            <div class="d-flex justify-content-between">
                <a href="#" class="btn btn-secondary">Retour</a>
                <a href="#" class="btn btn-primary">Modifier</a>
            </div>
        </div>
</div> -->
<div class="card">
    <div class="card-header">
        <div class='d-flex justify-content-between'>
            <h3>Détails</h3>
            <button type="button" class="btn p-0" data-bs-toggle="modal" data-bs-target="#updateIdentityModal"><i class="icofont-edit text-primary fs-5"></i></button>
        </div>
    </div>
    <div class="card-body">
    <div class="row">
        <!-- Colonne 1 : Recours -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold text-primary">Recours</h5>
                    <p><strong>Étude :</strong> {{$appeal->analyse_status}}</p>
                    <p><strong>Décision :</strong> {{$appeal->decision->decision ?? ''}}</p>
                    <p><strong>Délai :</strong> {{$appeal->day_count}}</p>
                    <p><strong>Type :</strong> {{$appeal->type}}</p>
                    <p><strong>Dépôt le :</strong> {{$appeal->deposit_date}} <strong>À</strong> {{$appeal->deposit_hour}}</p>
                    <p><strong>Objet :</strong> {{$appeal->object}}</p>
                </div>
            </div>
        </div>

        <!-- Colonne 2 : Marché + Requérant -->
        <div class="col-md-6">
            <div class="d-flex flex-column h-100">
                <!-- Section Marché -->
                <div class="card shadow-sm flex-grow-1 mb-2">
                    <div class="card-body">
                        <h5 class="fw-bold text-primary">Marché</h5>
                        <p><strong>N° :</strong>  {{$appeal->dac->reference}}</p>
                        <p><strong>Objet :</strong> {{$appeal->dac->object}}</p>
                        <p><strong>A C :</strong> {{$appeal->dac->ac}}</p>
                    </div>
                </div>

                <!-- Section Requérant -->
                <div class="card shadow-sm flex-grow-1">
                    <div class="card-body">
                        <h5 class="fw-bold text-primary">Requérant</h5>
                        <p><strong>Dénomination :</strong> {{$appeal->applicant->name}}</p>
                        <p><strong>Adresse :</strong> {{$appeal->applicant->address}}</p>
                        <p><strong>Téléphone :</strong> {{$appeal->applicant->phone_number}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Boutons en bas -->
    <div class=" mt-4">
        <a href="#" class="btn btn-secondary">Retour</a>
        <a href="#" class="btn btn-primary">Modifier</a>
    </div>
    </div>
</div>

<!-- modals -->
   <!-- Edit Employee Identity Info-->
<div class="modal fade" id="updateIdentityModal" tabindex="-1"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    
                    <div class="modal-body">
                        <div class="deadline-form modelUpdateFormContainer" id="updateIdentityFormid">
                            <form data-model-update-url="">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="modal-header">
                                <h5 class="modal-title  fw-bold" id="edit1Label">Identité</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                               <!--  -->
                               <fieldset class=" p-3 shadow-sm   mb-2">
                                <legende class="w-auto px-2 fs-6 shadow-4 text-muted fw-bold shadow"><span class='mb-4'>Identité</span></legende>
                                    <div class="row g-3 mb-3 mt-2">
                                        <div class="col-sm-6">
                                            <label for="last_name" class="form-label">Nom</label>
                                            <input type="text" class="form-control" id="last_name" value='' name='last_name' placeholder="">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="first_name" class="form-label">Prénoms</label>
                                            <input type="text" class="form-control" value='' id="first_name" name='first_name'>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="row g-3 mb-3">
                                        <div class="col-sm-6">
                                            <label for="nationality" class="form-label">Nationalité</label>
                                            <input type="text" class="form-control" value='' id="nationality" name='nationality' placeholder="">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="religion" class="form-label">Religion</label>
                                            <select class="form-select" aria-label="Default select Project Category" id="religion" name='religion'>
                                              
                                            
                                            </select>
                                        </div>
                                    </div>
       
                                    <div class="row g-3 mb-3">
                                        <div class="col-sm-6">
                                            <label for="marital_status" class="form-label">St Matrimoniale</label>
                                            <select class="form-select" aria-label="Default select Project Category" id="marital_status" name='marital_status'>
                                                  
                                            
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="gender" class="form-label">Genre</label>
                                            <select class="form-select" aria-label="Default select Project Category" id="gender" name='gender'>
                                               
                                            </select>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-6">
                                        <label for="birth_date" class="form-label">Date Naiss.</label>
                                        <input type="date" value='' class="form-control" id="birth_date" name='birth_date'>
                                    </div>
                                </fieldset>

                            <!--  -->
                            <div class='m-4 d-flex justify-content-center align-items-center'>
                                <!-- <button type="button" class="btn btn-lg btn-block lift text-uppercase btn-secondary" data-bs-dismiss="modal">Annuler</button> -->
                                <button type="submit" class="btn btn-lg btn-block lift text-uppercase btn-primary" atl="update Emp"
                                id="modelAddBtn">
                                    <span class="normal-status">
                                        Enregister
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
            </div>
</div>

@endsection
@push('plugins-js')
@endpush
@push('js')

@endpush 