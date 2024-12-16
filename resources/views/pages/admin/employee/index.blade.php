@extends('pages.admin.base')
@section('plugins-style')
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
    <link href={{ asset('assets/plugins/select2/css/select2.min.css') }} rel="stylesheet"> --}}
@endsection
@section('admin-content')
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card border-0 mb-4 no-bg">
                <div class="card-header py-3 px-0 d-sm-flex align-items-center  justify-content-between border-bottom">
                    <h3 class=" fw-bold flex-fill mb-0 mt-sm-0">Employés</h3>
                    <button type="button" class="btn btn-dark me-1 mt-1 w-sm-100" data-bs-toggle="modal"
                        data-bs-target="#createemp"><i class="icofont-plus-circle me-2 fs-6"></i>Ajouter Un employée</button>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle mt-1  w-sm-100" type="button"
                            id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                            Status
                        </button>
                        <ul class="dropdown-menu  dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                            <li><a class="dropdown-item" href="#">All</a></li>
                            <li><a class="dropdown-item" href="#">Task Assign Members</a></li>
                            <li><a class="dropdown-item" href="#">Not Assign Members</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Row End -->
    <div
        class="row g-3 row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-2 row-deck py-1 pb-4">
        @forelse ($employees as $employee)
            <div class="col">
                <div class="card teacher-card">
                    <div class="card-body d-flex">
                        <div class="profile-av pe-xl-4 pe-md-2 pe-sm-4 pe-4 text-center w220">
                            <img src="{{ asset($employee->profile_picture) }}" alt=""
                                class="avatar xl rounded-circle img-thumbnail shadow-sm">
                            <div class="about-info d-flex align-items-center mt-3 justify-content-center">
                                <div class="followers me-2">
                                    <i class="icofont-tasks color-careys-pink fs-4"></i>
                                    <span class="">04</span>
                                </div>
                                <div class="star me-2">
                                    <i class="icofont-star text-warning fs-4"></i>
                                    <span class="">4.5</span>
                                </div>
                                <div class="own-video">
                                    <i class="icofont-data color-light-orange fs-4"></i>
                                    <span class="">04</span>
                                </div>
                            </div>
                        </div>
                        <div class="teacher-info border-start ps-xl-4 ps-md-3 ps-sm-4 ps-4 w-100">
                            <h6 class="mb-0 mt-2  fw-bold d-block fs-6">{{ $employee->username }}</h6>
                            @foreach ($employee->roles as $role)
                                <span
                                    class="light-info-bg py-1 px-2 rounded-1 d-inline-block fw-bold small-11 mb-0 mt-1">{{ $role->name }}
                                </span>
                            @endforeach

                            <div class="video-setting-icon mt-3 pt-3 border-top">

                            </div>
                            <a href="task.html" class="btn btn-dark btn-sm mt-1"><i
                                    class="icofont-plus-circle me-2 fs-6"></i>Add Task</a>
                            <a href="employee-profile.html" class="btn btn-dark btn-sm mt-1"><i
                                    class="icofont-invisible me-2 fs-6"></i>Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    </div>

    <!-- Create Employee-->
    @include('pages.admin.employee.create')
@endsection
@push('plugins-js')
    {{-- <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
    <script src={{ asset('assets/plugins/select2/js/select2.min.js') }}></script> --}}
@endpush
@push('js')
    {{-- <script src={{ asset('app-js/transaction/history.js') }}></script> --}}
    <script src={{ asset('app-js/crud/post.js') }}></script>
@endpush
