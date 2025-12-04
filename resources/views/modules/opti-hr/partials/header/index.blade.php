<div class="header">
    <nav class="navbar py-4">
        <div class="container-xxl">

            <!-- header rightbar icon -->
            <div class="h-right d-flex align-items-center mr-5 mr-lg-0 order-1">
                <div class="d-flex">
                    <a class="nav-link text-primary collapsed" href="{{ route('help.index') }}" title="Get Help">
                        <i class="icofont-info-square fs-5"></i>
                    </a>
                </div>
                {{-- @include('partials.header.notification') --}}
                @include('modules.opti-hr.partials.header.user-menu')
            </div>

            <!-- menu toggler -->
            <button class="navbar-toggler p-0 border-0 menu-toggle order-3" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainHeader">
                <span class=""> <i class="icofont-navigation-menu"></i></span>
            </button>

            <!-- main menu meft-->
            <div class="order-0 col-lg-4 col-md-4 col-sm-12 col-12 mb-3 mb-md-0 ">

                <a type="button" class="btn btn-primary" href="{{ route('absences.create') }}">Demander Un
                    Congés</a>

                <!-- Ajout du solde de congés -->
                <div class="leave-balance-container">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="leave-balance-label">Solde de congés: </span>
                    <span
                        class="leave-balance-value">{{ auth()->user()->employee->duties->firstWhere('evolution', 'ON_GOING')->absence_balance ?? 0 }}
                        jour(s)</span>
                </div>
                <!-- Fin ajout -->
            </div>

        </div>
    </nav>
</div>
