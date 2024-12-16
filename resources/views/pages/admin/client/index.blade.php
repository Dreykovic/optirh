@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/intl-tel-input/css/intlTelInput.min.css') }}">
@endsection
@section('admin-content')
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card border-0 mb-4 no-bg">
                <div class="card-header py-3 px-0 d-flex align-items-center  justify-content-between border-bottom">
                    <h3 class=" fw-bold flex-fill mb-0">Clients</h3>
                    <div class="col-auto d-flex">

                        <button type="button" class="btn btn-dark ms-1 " data-bs-toggle="modal"
                            data-bs-target="#createClient"><i class="icofont-plus-circle me-2 fs-6"></i>Ajouter un
                            client</button>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Row End -->
    <div
        class="row g-3 row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-3 row-deck py-1 pb-4">
        @forelse ($clients as $client)
            <div class="col">
                <div class="card   shadow">
                    <div class="card-body  d-flex items-center justify-content-center">
                        <div class="profile-av pe-xl-4 pe-md-2 pe-sm-4 pe-4 text-center w220">
                            <img src="{{ asset($client->profile_picture) }}" alt=""
                                class="avatar xl rounded-circle img-thumbnail shadow-sm">
                            <div class="about-info d-flex align-items-center mt-1 justify-content-center flex-column">
                                <h6 class="mb-0 fw-bold d-block fs-6 mt-2">
                                    {{ $client->name }}
                                </h6>
                                <div class="btn-group mt-2" role="group" aria-label="Basic outlined example">
                                    <a href="{{ route('clients.show', $client->id) }}" class="btn btn-outline-secondary"><i
                                            class="icofont-eye text-success"></i></a>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                        data-bs-target="#deleteproject"><i
                                            class="icofont-ui-delete text-danger"></i></button>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
        @endforelse



    </div>


    <!-- Create Client-->
    @include('pages.admin.client.create')



    <!-- Modal  Delete Folder/ File-->
    <div class="modal fade" id="deleteproject" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title  fw-bold" id="deleteprojectLabel"> Delete item Permanently?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body justify-content-center flex-column d-flex">
                    <i class="icofont-ui-delete text-danger display-2 text-center mt-2"></i>
                    <p class="mt-4 fs-5 text-center">You can only delete this item Permanently</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger color-fff">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('plugins-js')
    <script src="{{ asset('assets/plugins/intl-tel-input/js/intlTelInput.min.js') }}"></script>
@endpush
@push('js')
    <script src={{ asset('app-js/crud/post.js') }}></script>
@endpush
