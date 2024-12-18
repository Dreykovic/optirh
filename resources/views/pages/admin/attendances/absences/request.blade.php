<div class="card-body p-lg-4 p-3">
    <div class="d-flex mb-3 pb-3 border-bottom flex-wrap">
        <img class="avatar rounded-circle" src={{ asset('assets/images/xs/avatar1.jpg') }} alt="">
        <div class="flex-fill ms-3 text-truncate">
            <h6 class="mb-0"><span>{{ $employee->last_name . ' ' . $employee->first_name }}</span> <span
                    class="text-muted small">{{ $absence->date_of_application }}</span></h6>
            <span class="text-muted">{{ $absence->requested_days }}</span>
        </div>
        <div class="d-flex align-items-center">
            <span class="mb-2 me-3">
                <a href="#" class="rating-link active"><i class="bi bi-star-fill text-warning"></i></a>
                <a href="#" class="rating-link active"><i class="bi bi-star-fill text-warning"></i></a>
                <a href="#" class="rating-link active"><i class="bi bi-star-fill text-warning"></i></a>
                <a href="#" class="rating-link active"><i class="bi bi-star-fill text-warning"></i></a>
                <a href="#" class="rating-link active"><i class="bi bi-star-half text-warning"></i></a>
            </span>
            <div class="time-block text-truncate">
                <i class="icofont-clock-time"></i>{{ $absence->date_of_application }}
            </div>
        </div>
    </div>

    <div class="timeline-item-post">
        <h6 class="">{{ $absence_type->label }}
        </h6>
        <p>{{ $absence->reasons }}</p>
        <div class="mb-2 mt-4 text-end">
            <a class="me-lg-2 me-1 btn btn-primary btn-sm" href="#"><i
                    class="icofont-check-circled text-success"></i> <span
                    class="d-none d-sm-none d-md-inline">Approuver</span></a>

            <a class="me-lg-2 me-1 btn btn-primary btn-sm" href="#"><i
                    class="icofont-close-circled text-danger"></i> <span
                    class="d-none d-sm-none d-md-inline">Rejeter</span></a>
            <a class="btn btn-primary btn-sm" href="#"><i class="bi bi-chat-left-text-fill"></i> <span
                    class="d-none d-sm-none d-md-inline">Annuler</span></a>
        </div>
    </div>
</div>
