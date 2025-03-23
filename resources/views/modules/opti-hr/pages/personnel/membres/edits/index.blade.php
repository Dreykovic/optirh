@extends('modules.opti-hr.pages.base')
@section('admin-content')
    <h3 class='d-flex justify-content-center align-items-center" style="height: 100vh;'>Mes informations</h3>
    <div class='d-flex justify-content-center align-items-center style="height: 100vh;'>
        <form id="modelAddForm" class='col-xl-10 col-lg-10 col-sm-12 row bg-light'
            data-model-add-url="{{ route('membres.data.update', $employee->id) }}">
            @csrf
            <!-- <input type="hidden" name="_method" value="PUT"> -->
            <fieldset class=" p-3 shadow-sm   mb-2">
                <legende class="w-auto px-2 fs-6 shadow-4 text-muted fw-bold shadow"><span class='mb-4'>Identité</span>
                </legende>
                <div class="row g-3 mb-3 mt-2">
                    <div class="col-sm-6">
                        <label for="last_name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="last_name" value='{{ $employee->last_name }}'
                            name='last_name' placeholder="">
                    </div>
                    <div class="col-sm-6">
                        <label for="first_name" class="form-label">Prénoms</label>
                        <input type="text" class="form-control" value='{{ $employee->first_name }}' id="first_name"
                            name='first_name'>
                    </div>
                </div>
                <!--  -->
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="nationality" class="form-label">Nationalité</label>
                        <input type="text" class="form-control" value='{{ $employee->nationality }}' id="nationality"
                            name='nationality' placeholder="">
                    </div>
                    <div class="col-sm-6">
                        <label for="religion" class="form-label">Religion</label>
                        <select class="form-select" aria-label="Default select Project Category" id="religion"
                            name='religion'>
                            @if ($employee->religion == 'Christian')
                                <option selected value='{{ $employee->religion }}'>Chrétien.ne </option>
                                <option value="Muslim">Musulman.e</option>
                                <option value="Other">Autre</option>
                            @elseif($employee->religion == 'Muslim')
                                <option selected value='{{ $employee->religion }}'>Musulman.e </option>
                                <option value="Christian">Chrétien.ne</option>
                                <option value="Other">Autre</option>
                            @elseif($employee->religion == 'Other')
                                <option selected value='{{ $employee->religion }}'>Autre </option>
                                <option value="Christian">Chrétien.ne</option>
                                <option value="Muslim">Musulman.e</option>
                            @else
                                <option selected value=''>Choisir </option>
                                <option value="Muslim">Musulman.e</option>
                                <option value="Christian">Chrétien.ne</option>
                                <option value="Other">Autre</option>
                            @endif

                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="marital_status" class="form-label">St Matrimoniale</label>
                        <select class="form-select" aria-label="Default select Project Category" id="marital_status"
                            name='marital_status'>
                            @if ($employee->marital_status == 'Single')
                                <option selected value='{{ $employee->marital_status }}'>Célibataire </option>
                                <option value="Married">Marié.e</option>
                                <option value="Divorced">Divorcé.e</option>
                                <option value="Widowed">Veuf.ve</option>
                            @elseif($employee->marital_status == 'Married')
                                <option selected value='{{ $employee->marital_status }}'>Marié.e </option>
                                <option value="Single">Célibataire</option>
                                <option value="Divorced">Divorcé.e</option>
                                <option value="Widowed">Veuf.ve</option>
                            @elseif($employee->marital_status == 'Divorced')
                                <option selected value='{{ $employee->marital_status }}'>Divorcé.e </option>
                                <option value="Single">Célibataire</option>
                                <option value="Maried">Marié.e</option>
                                <option value="Widowed">Veuf.ve</option>
                            @elseif($employee->marital_status == 'Widowed')
                                <option selected value='{{ $employee->marital_status }}'>Veuf.ve </option>
                                <option value="Single">Célibataire</option>
                                <option value="Maried">Marié.e</option>
                                <option value="Divorced">Divorcé.e</option>
                            @else
                                <option value="" selected>Choisir</option>
                                <option value='Widowed'>Veuf.ve </option>
                                <option value="Single">Célibataire</option>
                                <option value="Maried">Marié.e</option>
                                <option value="Divorced">Divorcé.e</option>
                            @endif

                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="gender" class="form-label">Genre</label>
                        <select class="form-select" aria-label="Default select Project Category" id="gender"
                            name='gender'>
                            <option selected value='{{ $employee->gender }}'>
                                {{ $employee->gender == 'MALE' ? 'Homme' : 'Femme' }}</option>
                            @if ($employee->gender == 'MALE')
                                <option value="FEMALE">Femme</option>
                            @else
                                <option value="MALE">Homme</option>
                            @endif
                        </select>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-6">
                    <label for="birth_date" class="form-label">Date Naiss.</label>
                    <input type="date" value='{{ $employee->birth_day }}' class="form-control" id="birth_date"
                        name='birth_date'>
                </div>
            </fieldset>


            <fieldset class=" p-3 shadow-sm   mb-2">
                <legende class="w-auto px-2 fs-6 shadow-4 text-muted fw-bold shadow"><span class='mb-4'>Adresse</span>
                </legende>
                <div class="row g-3 mb-3 mt-2">
                    <div class="col-sm-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" value='{{ $employee->email }}' class="form-control" id="email"
                            name='email' placeholder="">
                    </div>
                    <div class="col-sm-6">
                        <label for="phone_number" class="form-label">Contact</label>
                        <input type="text" value='{{ $employee->phone_number }}' class="form-control"
                            id="phone_number" name='phone_number'>
                    </div>
                </div>
                <!--  -->
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="emergency_contact" class="form-label">Contact Urgence</label>
                        <input type="text" value='{{ $employee->emergency_contact }}' class="form-control"
                            id="emergency_contact" name='emergency_contact'>
                    </div>
                    <div class="col-sm-6">
                        <label for="address1" class="form-label">Adresse</label>
                        <input type="text" value='{{ $employee->address1 }}' class="form-control" id="address1"
                            name='address1'>
                    </div>
                </div>
                <!--  -->
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="city" class="form-label">Ville</label>
                        <input type="city" value='{{ $employee->city }}' class="form-control" id="city"
                            name='city' placeholder="">
                    </div>
                    <div class="col-sm-6">
                        <label for="state" class="form-label">Quartier</label>
                        <input type="state" value='{{ $employee->state }}' class="form-control" id="state"
                            name='state' placeholder="">
                    </div>

                </div>
                <!--  -->
            </fieldset>


            <!--  -->
            <fieldset class=" p-3 shadow-sm   mb-2">
                <legende class="w-auto px-2 fs-6 shadow-4 text-muted fw-bold shadow"><span class='mb-4'>Compte
                        Banque</span></legende>
                <div class="row g-3 mb-3 mt-2">
                    <div class="col-sm-6">
                        <label for="bank_name" class="form-label">Nom Banque</label>
                        <input type="text" value='{{ $employee->bank_name }}' class="form-control" id="bank_name"
                            name='bank_name' placeholder="">
                    </div>
                    <div class="col-sm-6">
                        <label for="rib" class="form-label">No. Compte</label>
                        <input type="text" value='{{ $employee->rib }}' class="form-control" id="rib"
                            name='rib'>
                    </div>
                </div>
                <!--  -->
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="iban" class="form-label">IBAN</label>
                        <input type="text" value='{{ $employee->iban }}' class="form-control" id="iban"
                            name='iban' placeholder="">
                    </div>
                    <div class="col-sm-6">
                        <label for="swift" class="form-label">Swift</label>
                        <input type="text" value='{{ $employee->swift }}' class="form-control" id="swift"
                            name='swift'>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="cle_rib" class="form-label">Clé RIB</label>
                        <input type="cle_rib" value='{{ $employee->cle_rib }}' class="form-control" id="cle_rib"
                            name='cle_rib' placeholder="">
                    </div>
                    <div class="col-sm-6">
                        <label for="code_bank" class="form-label">Code Banque</label>
                        <input type="code_bank" value='{{ $employee->code_bank }}' class="form-control" id="code_bank"
                            name='code_bank' placeholder="">
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-6">
                    <label for="code_guichet" class="form-label">Code Guichet</label>
                    <input type="text" value='{{ $employee->code_guichet }}' class="form-control" id="code_guichet"
                        name='code_guichet'>
                </div>
            </fieldset>




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
@endsection

@push('js')
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
@endpush
