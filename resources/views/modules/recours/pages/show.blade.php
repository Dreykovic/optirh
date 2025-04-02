@extends('modules.recours.pages.base')
@section('plugins-style')
@endsection
@section('admin-content')
    <div class='col-lg-12 row'>
        <!--  -->
        <div class="card col-lg-12">
            <div class="card-header">
                <div class='d-flex justify-content-between'>
                    <h3>Détails</h3>
                    <button type="button" class="btn p-0" data-bs-toggle="modal" data-bs-target="#updateAppealModal"><i
                            class="icofont-edit text-primary fs-5"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Colonne 1 : Recours -->
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="fw-bold text-primary">Recours</h5>
                                @if ($appeal->analyse_status == 'RECEVABLE')
                                    <p><strong>Étude :</strong> <span
                                            class="badge bg-success p-2">{{ $appeal->analyse_status }}</span></p>
                                @elseif($appeal->analyse_status == 'IRRECEVABLE')
                                    <p><strong>Étude :</strong> <span
                                            class="badge bg-danger p-2">{{ $appeal->analyse_status }}</span></p>
                                @else
                                    <p><strong>Étude :</strong> <span
                                            class="badge bg-warning p-2">{{ $appeal->analyse_status }}</span></p>
                                @endif
                                <p><strong>Décision :</strong> <span
                                        class="badge bg-info p-2">{{ $appeal->decision->decision ?? 'N/A' }}</span></p>
                                <p><strong>Durée Écoulée :</strong> {{ $appeal->day_count }} jrs</p>
                                <p><strong>Contestation :</strong>
                                    @if ($appeal->type == 'RESULTS')
                                        Ses résultats
                                    @elseif($appeal->type == 'DAC')
                                        Son DAC
                                    @elseif($appeal->type == 'PROCESS')
                                        Sa Procédure/Son déroulement
                                    @else
                                        Autre
                                    @endif

                                </p>
                                <p><strong>Dépôt le :</strong> {{ $appeal->deposit_date }} <strong>À</strong>
                                    {{ $appeal->deposit_hour }}</p>
                                <p><strong>Objet :</strong> {{ $appeal->object }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne 2 : Marché + Requérant -->
                    <div class="col-md-6">
                        <div class="d-flex flex-column h-100">
                            <!-- Section Marché -->
                            <div class="card shadow-sm flex-grow-1 mb-2">
                                <div class="card-body">
                                    <h5 class="fw-bold text-primary">DAC</h5>
                                    <p><strong>N° :</strong> {{ $appeal->dac->reference }}</p>
                                    <p><strong>Objet :</strong> {{ $appeal->dac->object }}</p>
                                    <p><strong>A C :</strong> {{ $appeal->dac->ac }}</p>
                                </div>
                            </div>

                            <!-- Section Requérant -->
                            <div class="card shadow-sm flex-grow-1">
                                <div class="card-body">
                                    <h5 class="fw-bold text-primary">Requérant</h5>
                                    <p><strong>Dénomination :</strong> {{ $appeal->applicant->name }}</p>
                                    <p><strong>Adresse :</strong> {{ $appeal->applicant->address }}</p>
                                    <p><strong>Téléphone :</strong> {{ $appeal->applicant->phone_number }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons en bas -->
                <div class='d-flex p-4 mt-4 '>
                    <!-- Bouton de suppression -->
                    @if ($appeal->analyse_status == 'EN_COURS')
                        <div class='mx-2'>
                            <form id="delete-form" action="{{ route('recours.delete', $appeal->id) }}" method="post">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" id="delete-btn" class="btn btn-outline-danger">Supprimer</button>
                            </form>
                        </div>
                        <div class='mx-2'>
                            <form id="suspended-form" action="{{ route('recours.accepted', $appeal->id) }}" method="post">
                                @csrf
                                <!-- <input type="hidden" name="_method" value="PUT"> -->
                                <button type="button" id="accepted-btn" class="btn btn-outline-warning">Recevable</button>
                            </form>
                        </div>
                        <div class='mx-2'>
                            <form id="rejected-form" action="{{ route('recours.rejected', $appeal->id) }}" method="post">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <button type="button" id="rejected-btn" class="btn btn-outline-info">Non Recevable</button>
                            </form>
                        </div>
                    @endif

                    @if($appeal->analyse_status == 'RECEVABLE' && $appeal->decision->decision == 'SUSPENDU')
                        <div class='mx-2'>
                            <form id="crd-form" action="{{ route('recours.crd', $appeal->id) }}" method="post">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <button type="button" id="crd-btn" class="btn btn-outline-info">Décision du CRD</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!--  -->
        <!-- <div class="card col-lg-2 border-0">
            <h5 class='text-center mt-2'>Change Log</h5>
        </div> -->
    </div>



    <!-- modals -->
    <!-- Edit Employee Identity Info-->
    <div class="modal fade" id="updateAppealModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="deadline-form modelUpdateFormContainer" id="updateAppealForm{{ $appeal->id }}">
                        <form data-model-update-url="{{ route('recours.update', $appeal->id) }}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="modal-header d-fex justify-content-between">
                                <h5 class="modal-title  fw-bold" id="edit1Label">Modifier</h5>
                                @if ($appeal->decision && $appeal->decision->decision == 'EN COURS')
                                    <div class='d-flex justify-items-center mx-auto'>
                                        <label for="decision" class='form-label mx-2 mt-2'>Décision: </label>
                                        <select class='form-control' name="decision" id="decision">
                                            <option value="" selected>choisir</option>
                                            <option value="FONDE">FONDE</option>
                                            <option value="NON FONDE">NON FONDE</option>
                                            <option value="DESISTEMENT">DESISTEMENT</option>
                                        </select>
                                    </div>
                                @endif
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>

                            </div>
                            <!--  -->
                            <fieldset class=" p-3 mb-2">
                                <legende class="w-auto px-2 fs-6 shadow-4 text-muted fw-bold shadow"><span
                                        class='mb-4'>Dac</span></legende>
                                <div class="row g-3 mb-3 mt-2">
                                    <div class="col-sm-4">
                                        <label for="last_name" class="form-label">N°: </label>
                                        <input type="text" class="form-control" id="last_name"
                                            value='{{ $appeal->dac->reference }}' name='reference' placeholder="">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="first_name" class="form-label">Objet</label>
                                        <input type="text" class="form-control" value='{{ $appeal->dac->object }}'
                                            id="first_name" name='dac_object'>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="birth_date" class="form-label">Authorité</label>
                                        <input type="text" value='{{ $appeal->dac->ac }}' class="form-control"
                                            id="birth_date" name='ac'>
                                    </div>
                                </div>
                                <!--  -->
                            </fieldset>

                            <fieldset class=" p-3 mb-2">
                                <legende class="w-auto px-2 fs-6 shadow-4 text-muted fw-bold shadow"><span
                                        class='mb-4'>Requérant</span></legende>
                                <div class="row g-3 mb-3 mt-2">
                                    <div class="col-sm-3">
                                        <label for="last_name" class="form-label">Dénomination</label>
                                        <input type="text" class="form-control" id="last_name"
                                            value='{{ $appeal->applicant->name }}' name='name' placeholder="">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="last_name" class="form-label">NIF</label>
                                        <input type="text" class="form-control" id="last_name"
                                            value='{{ $appeal->applicant->nif }}' name='nif' placeholder="">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="first_name" class="form-label">Adresse</label>
                                        <input type="text" class="form-control"
                                            value='{{ $appeal->applicant->address }}' id="first_name" name='address'>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="birth_date" class="form-label">Téléphone</label>
                                        <input type="text" value='{{ $appeal->applicant->phone_number }}'
                                            class="form-control" id="birth_date" name='phone_number'>
                                    </div>
                                </div>
                                <!--  -->


                            </fieldset>

                            <fieldset class=" p-3  mb-2">
                                <legende class="w-auto px-2 fs-6 shadow-4 text-muted fw-bold shadow"><span
                                        class='mb-4'>Recours</span></legende>
                                <div class="row g-3 mb-3 mt-2">
                                    <div class="col-sm-4">
                                        <label for="last_name" class="form-label">Contestation De</label>
                                        <select name="type" id="type_recours" class="form-control">
                                            @if ($appeal->type == 'RESULTS')
                                                <option value="RESULTS" selected>Ses résultats Provisoirs</option>
                                                <option value="DAC">Son DAC</option>
                                                <option value="PROCESS">Sa Procédure/ Son déroulement</option>
                                                <option value="OTHERS">Autre</option>
                                            @elseif($appeal->type == 'DAC')
                                                <option value="RESULTS">Ses résultats Provisoirs</option>
                                                <option value="DAC" selected>Son DAC</option>
                                                <option value="PROCESS">Sa Procédure/ Son déroulement</option>
                                                <option value="OTHERS">Autre</option>
                                            @elseif($appeal->type == 'PROCESS')
                                                <option value="RESULTS">Ses résultats Provisoirs</option>
                                                <option value="DAC">Son DAC</option>
                                                <option value="PROCESS" selected>Sa Procédure/ Son déroulement</option>
                                                <option value="OTHERS">Autre</option>
                                            @elseif($appeal->type == 'PROCESS')
                                                <option value="RESULTS">Ses résultats Provisoirs</option>
                                                <option value="DAC">Son DAC</option>
                                                <option value="PROCESS" selected>Sa Procédure/ Son déroulement</option>
                                                <option value="OTHERS">Autre</option>
                                            @else
                                                <option value="RESULTS">Ses résultats Provisoirs</option>
                                                <option value="DAC">Son DAC</option>
                                                <option value="PROCESS">Sa Procédure/ Son déroulement</option>
                                                <option value="OTHERS" selected>Autre</option>
                                            @endif

                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="first_name" class="form-label">Date Dépot</label>
                                        <input type="datetime-local" class="form-control"
                                            value="{{ $appeal->deposit_date ? $appeal->deposit_date . 'T' . $appeal->deposit_hour : '' }}"
                                            id="date_depot" name="date_depot">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="birth_date" class="form-label">Objet</label>
                                        <input type="text" value='{{ $appeal->object }}' class="form-control"
                                            id="birth_date" name='appeal_object'>
                                    </div>
                                </div>
                                <!--  -->

                                <!-- <div class="col-sm-6">
                                            <label for="birth_date" class="form-label">Date Naiss.</label>
                                            <input type="date" value='' class="form-control" id="birth_date" name='birth_date'>
                                        </div> -->
                            </fieldset>
                            <!-- mails -->
                             @if($appeal->analyse_status == 'RECEVABLE' && $appeal->decision->decision == 'SUSPENDU')
                            <fieldset class=" p-3 mb-2">
                                <legende class="w-auto px-2 fs-6 shadow-4 text-muted fw-bold shadow"><span
                                        class='mb-4'>Mails de Suspension</span></legende>
                                <div class="row g-3 mb-3 mt-2">
                                   
                                    <div class="col-sm-6">
                                        <label for="" class="form-label">Date d'Envoi</label>
                                        <input type="date" class="form-control" 
                                            id="" name='message_date'>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="" class="form-label">Date de Réponse convenue</label>
                                        <input type="date" class="form-control"
                                            id="" name='response_date'>
                                    </div>
                                </div>
                                <!--  -->
                            </fieldset>
                            @endif
                            @if($appeal->analyse_status=='RECEVABLE' && $appeal->decision->decision !== 'SUSPENDU')
                            <fieldset class=" p-3 mb-2">
                                <legende class="w-auto px-2 fs-6 shadow-4 text-muted fw-bold shadow"><span
                                        class='mb-4'>Approbation</span></legende>
                                <div class="row g-3 mb-3 mt-2">
                                   
                                    <div class="col-sm-6">
                                        <label for="" class="form-label">Date Notif.</label>
                                        <input type="date" class="form-control" 
                                            id="" name='notif_date'>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="" class="form-label">Date Pub.</label>
                                        <input type="date" class="form-control"
                                            id="" name='publish_date'>
                                    </div>
                                </div>
                                <!--  -->
                            </fieldset>
                            @endif
                            <!--  -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-lg btn-block lift text-uppercase btn-secondary"
                                    data-bs-dismiss="modal">Annuler</button>
                                <button type="submit"
                                    class="btn btn-lg btn-block lift text-uppercase btn-primary modelUpdateBtn"
                                    atl="Update Appeal" data-bs-dismiss="modal">
                                    <span class="normal-status">
                                        Enregister
                                    </span>
                                    <span class="indicateur d-none">
                                        <span class="spinner-grow spinner-grow-sm" role="status"
                                            aria-hidden="true"></span>
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
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <!-- <script src="{{ asset('app-js/personnel/contrats/actions.js') }}"></script> -->
    <script src="{{ asset('app-js/recours/accepte.js') }}"></script>
    <script src="{{ asset('app-js/recours/rejete.js') }}"></script>
    <script src="{{ asset('app-js/recours/crd.js') }}"></script>
    <script src="{{ asset('app-js/recours/delete.js') }}"></script>
@endpush
