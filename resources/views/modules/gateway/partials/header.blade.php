<div class="col">
    <div class="address-bar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="/" class="text-decoration-none">
                <h5 class="mb-0 text-dark"><i class="icofont-dashboard me-2"></i>Portail d'Applications</h5>
            </a>
        </div>
        <div>
            <div class="dropdown user-profile ml-2 ml-sm-3 d-flex align-items-center zindex-popover">
                <div class="u-info me-2">
                    <p class="mb-0 text-end line-height-sm "><span
                            class="font-weight-bold">{{ auth()->user()->username }}</span></p>
                    <small>{{ auth()->user()->getRoleNames()->first() }}</small>
                </div>
                <a class="dropdown-toggle pulse p-0" href="#" role="button" data-bs-toggle="dropdown"
                    data-bs-display="static">
                    <i class="icofont-user-alt-7 avatar rounded-circle img-thumbnail fs-4"></i>
                </a>
                <div class="dropdown-menu rounded-lg shadow border-0 dropdown-animation dropdown-menu-end p-0 m-0">
                    <div class="card border-0 w280">
                        <div class="card-body pb-0">
                            <div class="d-flex py-1">
                                <div class="flex-fill ms-3">
                                    <p class="mb-0"><span
                                            class="font-weight-bold">{{ auth()->user()->username }}</span>
                                    </p>
                                    <small class="">{{ auth()->user()->email }}</small>
                                </div>
                            </div>
                            <div>
                                <hr class="dropdown-divider border-dark">
                            </div>
                        </div>
                        <div class="list-group m-2 ">
                            <a href="#" class="list-group-item list-group-item-action border-0"><i
                                    class="icofont-user fs-6 me-3"></i>Mon profil</a>
                            <a href="#" class="list-group-item list-group-item-action border-0"><i
                                    class="icofont-settings fs-6 me-3"></i>Paramètres</a>
                            <div>
                                <hr class="dropdown-divider border-dark">
                            </div>
                            <a href="{{ route('logout') }}" class="list-group-item list-group-item-action border-0 "><i
                                    class="icofont-logout fs-6 me-3"></i>Se Déconnecter</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
