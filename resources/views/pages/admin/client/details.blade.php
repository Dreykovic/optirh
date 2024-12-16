<div class="row g-3">
    <div class="col-xl-8 col-lg-12 col-md-12">
        <div class="card teacher-card  mb-3">
            <div class="card-body  d-flex teacher-fulldeatil">
                <div class="profile-teacher pe-xl-4 pe-md-2 pe-sm-4 pe-0 text-center w220 mx-sm-0 mx-auto">
                    <a href="#">
                        <img src="{{ asset($client->profile_picture) }}" alt=""
                            class="avatar xl rounded-circle img-thumbnail shadow-sm">
                    </a>
                    <div class="about-info d-flex align-items-center mt-3 justify-content-center flex-column">
                        <h6 class="mb-0 fw-bold d-block fs-6">{{ $client->job }}</h6>
                        <span class="text-muted mb-2 medium">Client ID : {{ $client->client_no }}</span>

                        <span class="text-{{ $client->status === 'ACTIF' ? 'success' : 'danger' }} mb-2 ">
                            {{ $client->status }}</span>


                    </div>
                    @if ($client->status === 'INACTIF')
                        <form class="modelUpdateForm"
                            data-model-update-url="{{ route('clients.updateStatus', $client) }}">

                            <input type="hidden" name="status" value="ACTIF">


                            <button type="submit" class="btn btn-success modelUpdateBtn text-white"
                                atl="update client status">
                                <span class="normal-status">
                                    Activer
                                </span>
                                <span class="indicateur d-none">
                                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    Un Instant...
                                </span>
                            </button>

                        </form>
                    @else
                        <form class="modelUpdateForm"
                            data-model-update-url="{{ route('clients.updateStatus', $client) }}">

                            <input type="hidden" name="status" value="INACTIF">


                            <button type="submit" class="btn btn-danger modelUpdateBtn text-white"
                                atl="update client status">
                                <span class="normal-status">
                                    Désactiver
                                </span>
                                <span class="indicateur d-none">
                                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    Un Instant...
                                </span>
                            </button>

                        </form>
                    @endif













                </div>
                <div class="teacher-info border-start ps-xl-4 ps-md-3 ps-sm-4 ps-4 w-100">
                    <div class=" py-3 d-flex justify-content-between">
                        <h6 class="mb-0 fw-bold "> Informations Personnelles</h6>
                        <button type="button" class="btn p-0" data-bs-toggle="modal"
                            data-bs-target="#editClientDetails"><i class="icofont-edit text-primary fs-6"></i></button>
                    </div>
                    <h6 class="mb-0 mt-2  fw-bold d-block fs-6">{{ $client->name }}
                    </h6>
                    <span class="py-1 fw-bold small-11 mb-0 mt-1 text-muted">{{ $client->job }}</span>

                    <div class="row g-2 pt-2">
                        <div class="col-xl-5">
                            <div class="d-flex align-items-center">
                                <i class="icofont-ui-touch-phone"></i>
                                <span class="ms-2 small">{{ $client->phone }} </span>
                            </div>
                        </div>
                        @if ($client->email)
                            <div class="col-xl-5">
                                <div class="d-flex align-items-center">
                                    <i class="icofont-email"></i>
                                    <span class="ms-2 small">{{ $client->email }}</span>
                                </div>
                            </div>
                        @endif
                        @if ($client->birthdate)
                            <div class="col-xl-5">
                                <div class="d-flex align-items-center">
                                    <i class="icofont-birthday-cake"></i>
                                    <span class="ms-2 small">@formatDateOnly($client->birthdate)</span>
                                </div>
                            </div>
                        @endif

                        <div class="col-xl-5">
                            <div class="d-flex align-items-center">
                                <i class="icofont-address-book"></i>
                                <span
                                    class="ms-2 small">{{ $client->address1 . ' ' . $client->address2 . ' ' . $client->city . ' ' . $client->state }}</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>


    </div>
    <div class="col-xl-4 col-lg-12 col-md-12">

        <div class="card">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold ">Documents KYC</h6>
            </div>
            <div class="card-body">
                <div class="flex-grow-1">
                    <div class="py-2 d-flex align-items-center border-bottom">
                        <div class="d-flex ms-3 align-items-center flex-fill">
                            <span
                                class="avatar lg bg-lightgreen rounded-circle text-center d-flex align-items-center justify-content-center"><i
                                    class="icofont-file-pdf fs-5"></i></span>
                            <div class="d-flex flex-column ps-3">
                                <h6 class="fw-bold mb-0 small-14">cni.pdf</h6>
                            </div>
                        </div>
                        <button type="button" class="btn bg-lightgreen text-end" disabled>Télécharger</button>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div><!-- Row End -->
<!-- Edit Employee Personal Info-->
@include('pages.admin.client.edit-details')
