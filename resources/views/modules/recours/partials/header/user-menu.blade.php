<div class="dropdown user-profile ml-2 ml-sm-3 d-flex align-items-center zindex-popover">
    <div class="u-info me-2">
        <p class="mb-0 text-end line-height-sm "><span class="font-weight-bold">{{ auth()->user()->username }}</span></p>
        <small>{{ auth()->user()->getRoleNames()->first() }}</small>
    </div>
    <a class=" dropdown-toggle pulse p-0" href="#" role="button" data-bs-toggle="dropdown" data-bs-display="static">

        <i class="icofont icofont-business-man-alt-2 avatar lg rounded-circle img-thumbnail fs-3    "></i>

    </a>
    <div class="dropdown-menu rounded-lg shadow border-0 dropdown-animation dropdown-menu-end p-0 m-0">
        <div class="card border-0 w280">
            <div class="card-body pb-0">
                <div class="d-flex py-1">

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
                @if(auth()->user()->hasEmployee())
                    <a href="{{ route('employee.pay', Auth::user()->employee) }}"
                        class="list-group-item list-group-item-action border-0 "><i
                            class="icofont-files-stack fs-5 me-3"></i>Bulletins de paie</a>

                    <a href="{{ route('employee.data') }}" class="list-group-item list-group-item-action border-0 "><i
                            class="icofont-settings fs-5 me-3"></i>Paramètres Profil</a>
                @endif
                <div>
                    <hr class="dropdown-divider border-dark">
                </div>

                <a href="{{ route('logout') }}" class="list-group-item list-group-item-action border-0 "><i
                        class="icofont-logout fs-6 me-3"></i>Se Déconnecter</a>
            </div>
        </div>
    </div>
</div>
