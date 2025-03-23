<div class="row clearfix g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header py-3 d-flex justify-content-between bg-transparent border-bottom-0">
                <h6 class="mb-0 fw-bold ">Permissions</h6>
            </div>
            <div class="card-body">
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

                        @foreach ($role->permissions as $index => $permission)
                            <li class="list-group-item d-flex">


                            </li>
                            <li class="list-group-item d-flex">
                                <div class="number border-end pe-2 fw-bold">
                                    <strong class="color-light-success">{{ $index + 1 }}</strong>
                                </div>

                                <div class="cs-text flex-fill ps-2">
                                    <span>@formatPermission($permission->name)</span>
                                </div>

                            </li>
                        @endforeach


                    </ul>

                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <table id="myProjectTable" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>Membre</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status</th>

                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($role->users as $index => $user)
                            <tr class="parent">

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

                            </tr>

                            @empty
                                <tr>

                                    <td colspan="4"> <x-no-data color="warning" text="Aucun Utilisateur Associé" />
                                    </td>



                                </tr>
                            @endforelse


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- Row End -->
