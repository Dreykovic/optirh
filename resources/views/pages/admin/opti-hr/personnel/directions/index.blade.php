@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
@endsection
@section('admin-content')
    <!-- Body: Body -->
    <div class="body d-flex py-lg-3 py-md-2">
        <div class="container-xxl">
            <div class="row align-items-center">
                <div class="border-0 mb-4">
                    <div
                        class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                        <h3 class="fw-bold mb-0">Nos Directions</h3>
                        <div class="col-auto d-flex w-sm-100">
                            <button type="button" class="btn btn-dark btn-set-task w-sm-100" data-bs-toggle="modal"
                                data-bs-target="#addDeptModal"><i class="icofont-plus-circle me-2 fs-4"></i>Ajouter</button>
                        </div>
                    </div>
                </div>
            </div> <!-- Row end  -->
            <div class="row clearfix g-3">
                <div class="col-sm-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <!-- <table id="directions" class="table table-hover align-middle mb-0" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Department Head</th>
                                                <th>Department Name</th>
                                                <th>Employee UnderWork</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <span class="fw-bold">1</span>
                                                </td>
                                               <td>
                                                   <img class="avatar rounded-circle" src="assets/images/xs/avatar1.jpg" alt="">
                                                   <span class="fw-bold ms-1">Joan Dyer</span>
                                               </td>
                                               <td>
                                                    Web Development
                                               </td>
                                               <td>
                                                    40
                                               </td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic outlined example">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#depedit"><i class="icofont-edit text-success"></i></button>
                                                        <button type="button" class="btn btn-outline-secondary deleterow"><i class="icofont-ui-delete text-danger"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table> -->
                            <table id="directions" class="table table-hover align-middle mb-0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Directeur</th>
                                        <th>Direction</th>
                                        <th>Définition</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $index => $department)
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class='d-flex justify-content-start align-items-center'>

                                                    @if ($department->director != null)
                                                        <i
                                                            class="icofont icofont-{{ $department->director->gender === 'FEMALE' ? 'businesswoman' : 'business-man-alt-2' }} fs-3  avatar rounded-circle"></i>
                                                        <span
                                                            class="fw-bold ms-1 text-uppercase">{{ $department->director->last_name }}
                                                            {{ $department->director->first_name }}</span>
                                                    @else
                                                        <span class="text-muted">Aucun directeur assigné</span>
                                                    @endif

                                                </div>
                                                <!--  -->
                                            </td>
                                            <td class='text-uppercase'>
                                                {{ $department->name }}
                                            </td>
                                            <td class='text-wrap w-50 text-capitalize'>
                                                {{ $department->description }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic outlined example">
                                                    <!--  -->
                                                    <div class="btn-group">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <span class='btn' data-bs-toggle="modal"
                                                                    data-bs-target="#updateDeptModal{{ $department->id }}"><i
                                                                        class="icofont-edit text-success m-2"></i>Editer</span>
                                                            </li>
                                                            <!-- <li>
                                                                    <span class='btn'><i class="icofont-ui-delete text-danger m-2"></i></i>Supprimer</span>
                                                                </li> -->
                                                            <li>
                                                                <a href="{{ route('directions.show', $department->id) }}">
                                                                    <span class='btn'><i
                                                                            class="icofont-eye-open m-2"></i></i>Détail</span>

                                                                </a>
                                                            </li>

                                                        </ul>
                                                    </div><!-- /btn-group -->

                                                </div>
                                                <!-- Edit Department-->
                                                @include('pages.admin.opti-hr.personnel.directions.edit')
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div><!-- Row End -->
        </div>
    </div>


    <!-- Add Department-->
    @include('pages.admin.opti-hr.personnel.directions.create')



    </div>
@endsection
@push('plugins-js')
    <script src="{{ asset('assets/bundles/dataTables.bundle.js') }}"></script>
@endpush
@push('js')
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
    <script>
        let AppDepartmentListManager = (function() {
            return {
                init: () => {
                    AppModules.initDataTable("#directions");
                },
            };
        })();

        document.addEventListener("DOMContentLoaded", (e) => {
            AppDepartmentListManager.init();
        });
    </script>
@endpush
