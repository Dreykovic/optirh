<div type="div" class="btn   dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
    <span class="fw-bolder">...</span>
    <span class="visually-hidden">Toggle Dropdown</span>
</div>
<ul class="dropdown-menu border-0 shadow py-3 px-2">
    <li>
        <a class="dropdown-item py-2 rounded" data-bs-toggle="modal"
            data-bs-target="#credentialsDetailsUpdate{{ $user->id }}" role="button">
            <i class="icofont-pen text-success"></i>

            <span class="d-none d-sm-none d-md-inline">Modifier</span>
        </a>

    </li>

    <li>
        <a class="dropdown-item py-2 rounded" data-bs-toggle="modal"
            data-bs-target="#credentialsPasswordUpdate{{ $user->id }}" role="button">
            <i class="icofont-key"></i>

            <span class="d-none d-sm-none d-md-inline">Changer Mot De Passe</span>
        </a>

    </li>
    <li>
        <a class="dropdown-item py-2 rounded" data-bs-toggle="modal"
            data-bs-target="#credentialsRoleUpdate{{ $user->id }}" role="button">
            <i class="icofont-users"></i>

            <span class="d-none d-sm-none d-md-inline">Changer Role</span>
        </a>

    </li>
    <li>
        <a class="dropdown-item py-2 rounded resendCredentialsBtn"
            data-url="{{ route('credentials.resend', $user->id) }}" role="button">
            <i class="icofont-email text-info"></i>
            <span class="d-none d-sm-none d-md-inline">Renvoyer Identifiants</span>
        </a>
    </li>
    <li>
        <a class="dropdown-item py-2 rounded modelDeleteBtn" data-model-action="delete"
            data-model-delete-url={{ route('credentials.destroy', $user->id) }} data-model-parent-selector="tr.parent"
            role="button">
            <span class="normal-status">
                <i class="icofont-ui-delete text-danger"></i>

                <span class="d-none d-sm-none d-md-inline">Supprimer</span>

            </span>

            <span class="indicateur d-none">
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>

            </span>
        </a>

    </li>
</ul>
