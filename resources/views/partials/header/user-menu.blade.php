<div class="dropdown user-profile ml-2 ml-sm-3 d-flex align-items-center zindex-popover">
    <div class="u-info me-2">
        <p class="mb-0 text-end line-height-sm "><span class="font-weight-bold">{{ auth()->user()->username }}</span></p>
        <small>{{ auth()->user()->getRoleNames()->first() }}</small>
    </div>
    <a class="nav-link dropdown-toggle pulse p-0" href="#" role="button" data-bs-toggle="dropdown"
        data-bs-display="static">
        <img class="avatar lg rounded-circle img-thumbnail" src="{{ asset(asset(auth()->user()->profile_picture)) }}"
            alt="profile">
    </a>
    <div class="dropdown-menu rounded-lg shadow border-0 dropdown-animation dropdown-menu-end p-0 m-0">
        <div class="card border-0 w280">
            <div class="card-body pb-0">
                <div class="d-flex py-1">
                    <img class="avatar rounded-circle" src="{{ asset(auth()->user()->profile_picture) }}"
                        alt="profile">
                    <div class="flex-fill ms-3">
                        <p class="mb-0"><span class="font-weight-bold">{{ auth()->user()->username }}</span>
                        </p>
                        <small class="">{{ auth()->user()->eamil }}</small>
                    </div>
                </div>

                <div>
                    <hr class="dropdown-divider border-dark">
                </div>
            </div>
            <div class="list-group m-2 ">
                {{-- <a href="#" class="list-group-item list-group-item-action border-0 "><i
                        class="icofont-tasks fs-5 me-3"></i>Mes taches</a>
                <a href="#" class="list-group-item list-group-item-action border-0 "><i
                        class="icofont-ui-user-group fs-6 me-3"></i>Membres</a> --}}
                <a href="{{ route('logout') }}" class="list-group-item list-group-item-action border-0 "><i
                        class="icofont-logout fs-6 me-3"></i>Se Déconnecter</a>
                {{-- <div>
                    <hr class="dropdown-divider border-dark">
                </div>
                <a href="#" class="list-group-item list-group-item-action border-0 "><i
                        class="icofont-contact-add fs-5 me-3"></i>Ajputer un compte personnel</a> --}}
            </div>
        </div>
    </div>
</div>
