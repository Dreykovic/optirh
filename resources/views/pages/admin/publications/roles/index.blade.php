@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}>
@endsection
@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div
                class="card-header p-0 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold py-3 mb-0">Roles & Niveaux D'Accès</h3>

            </div>
        </div>
    </div> <!-- Row end  -->

    <div class="row g-3 gy-5 py-3 row-deck align-items-center">
        @forelse ($roles as $role)
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mt-5">
                            <div class="lesson_name">
                                <div class="project-block light-success-bg">
                                    <i class="icofont-tick-boxed"></i>
                                </div>
                                <span class="  project_name fw-bold"> {{ $role->name }}</span>

                            </div>

                        </div>

                        <div class="row g-2 pt-4">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="icofont-paper-clip"></i>
                                    <span class="ms-2">{{ $role->permissions->count() }} Permissions</span>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="icofont-group-students "></i>
                                    <span class="ms-2">{{ $role->users->count() }} Utilisateurs</span>
                                </div>
                            </div>

                        </div>
                        <div class="dividers-block"></div>

                        <div class="customer-like mb-2">

                            <ul class="list-group mt-3">
                                @php
                                    $permissionsToShow = $role->permissions->take(3); // Prendre seulement les 2 premières permissions
                                    $remainingPermissionsCount =
                                        $role->permissions->count() - $permissionsToShow->count();
                                @endphp
                                @foreach ($permissionsToShow as $index => $permission)
                                    <li class="list-group-item d-flex">

                                        <div class="cs-text flex-fill ps-2">
                                            <span>@formatPermission($permission->name)</span>
                                        </div>

                                    </li>
                                @endforeach


                            </ul>

                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            @if ($remainingPermissionsCount > 0)
                                <div class='d-flex align-items-center py-2'>
                                    <span class='bullet bg-primary me-3'></span>
                                    <em>Et {{ $remainingPermissionsCount }} de plus...</em>
                                </div>
                            @endif
                            <a href="{{ route('roles.details', $role->id) }}" class="btn btn-outline-success my-1 me-2">
                                <span class="  p-1 rounded">
                                    Plus de Détails</span> </a>

                        </div>

                    </div>
                </div>
            </div>
        @empty
        @endforelse


    </div>
@endsection
@push('plugins-js')
    <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
@endpush
@push('js')
    <script src="{{ asset('app-js/users/credentials/table.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
@endpush
