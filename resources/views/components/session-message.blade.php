@if (session('error'))
    <!--begin::Alert-->
    <div class="alert-dismissible fade show  alert alert-success" role="alert">
        <h6 class="alert-heading">Message d'Erreur</h6>
        <p> {{ session('error') }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <!--end::Alert-->
@endif
@if ($errors->any())
    <!--begin::Alert-->
    <div class="alert-dismissible fade show  alert alert-success" role="alert">
        <h6 class="alert-heading">Message d'Erreur</h6>
        <p> {{ $errors->first() }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!--end::Alert-->
@endif

@if (session('success'))
    <!--begin::Alert-->
    <div class="alert-dismissible fade show  alert alert-success" role="alert">

        <h6 class="alert-heading">Message De Succès</h6>
        <p> {{ session('success') }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!--end::Alert-->
@endif
