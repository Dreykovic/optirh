@extends('modules.opti-hr.pages.base')
@section('plugins-style')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}>
@endsection
@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div
                class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">Identifiants</h3>
                <div class="d-flex py-2 project-tab flex-wrap w-sm-100">
                    <button type="button" class="btn btn-dark btn-set-task w-sm-100" data-bs-toggle="modal"
                        data-bs-target="#credentialAddModal"><i class="icofont-plus-circle me-2 fs-6"></i>Ajouter</button>
                    <div class="btn-group ms-2 bulk-actions d-none">
                        <button class="btn btn-success btn-sm" id="bulkActivate">
                            <i class="icofont-check-circled"></i> Activer
                        </button>
                        <button class="btn btn-warning btn-sm" id="bulkDeactivate">
                            <i class="icofont-close-circled"></i> Désactiver
                        </button>
                    </div>
                    <span class="ms-2 selected-count d-none text-muted align-self-center"></span>
                    <ul class="nav nav-tabs tab-body-header rounded ms-3 prtab-set w-sm-100" role="tablist">
                        <li class="nav-item"><a class="nav-link {{ $status === 'ALL' ? 'active' : '' }}"
                                href="{{ route('credentials.index', 'ALL') }}" role="tab">Toutes</a></li>

                        <li class="nav-item"><a
                                class="nav-link {{ $status === 'ACTIVATED' || $status === 'ACTIVATED' ? 'active' : '' }}"
                                href="{{ route('credentials.index', 'ACTIVATED') }}" role="tab">Activés</a></li>
                        <li class="nav-item"><a class="nav-link {{ $status === 'DEACTIVATED' ? 'active' : '' }}"
                                href="{{ route('credentials.index', 'DEACTIVATED') }}" role="tab">Non Activés</a></li>
                        {{-- 
                        <li class="nav-item"><a class="nav-link {{ $status === 'DELETED' ? 'active' : '' }}"
                                href="{{ route('credentials.index', 'DELETED') }}" role="tab">Archivés</a></li> --}}


                    </ul>
                </div>
            </div>
        </div>
    </div> <!-- Row end  -->
    <div class="row clearfix g-3">
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body">
                    <table id="usersTable" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 40px;">
                                    <div class="form-check d-flex justify-content-center mb-0">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>Membre</th>
                                <th>username</th>
                                <th>Email</th>
                                <th>Date D'Intégration</th>
                                <th>Role</th>

                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($users as $index => $user)
                                <tr class="parent">
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center mb-0">
                                            <input class="form-check-input user-checkbox" type="checkbox" value="{{ $user->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <x-employee-icon :employee="$user->employee" />
                                        <span
                                            class="">{{ $user->employee->last_name . ' ' . $user->employee->first_name }}</span>
                                    </td>
                                    <td>
                                        <span class=" model-value">{{ $user->username }}</span>
                                        <!-- Libellé du type d'absence -->
                                    </td>
                                    <td>
                                        <span class="">{{ $user->email }}</span>
                                        <!-- Libellé du type d'absence -->
                                    </td>
                                    <td>
                                        <span class="">@formatDate($user->created_at)</span>
                                        <!-- Libellé du type d'absence -->
                                    </td>
                                    <td>
                                        <span class="">{{ $user->getRoleNames()->first() }}</span>
                                        <!-- Libellé du type d'absence -->
                                    </td>
                                    <td>
                                        @switch($user->status)
                                            @case('ACTIVATED')
                                                <span class=" text-success">

                                                    Activé

                                                </span>
                                            @break

                                            @case('DEACTIVATED')
                                                <span class=" text-danger">

                                                    Non Activé
                                                </span>
                                            @break

                                            @default
                                                <span class=" color-lavender-purple">

                                                    Archivé
                                                </span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @include('modules.opti-hr.pages.users.credentials.actions')
                                    </td>
                                </tr>
                                @include('modules.opti-hr.pages.users.credentials.edit-details')
                                @include('modules.opti-hr.pages.users.credentials.edit-role')
                                @include('modules.opti-hr.pages.users.credentials.edit-pwd')
                            @endforeach
                        </tbody>
                    </table>
                    {{-- 
                    <!-- Paginée, affiche les liens de pagination -->
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div> --}}

                </div>
            </div>
        </div>
    </div><!-- Row End -->
    @include('modules.opti-hr.pages.users.credentials.create')
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
