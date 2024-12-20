<div class="accordion-item  mb-3">
    @include('pages.admin.attendances.absences.request.header')
    <div id="collapseAbsenceRequestLine{{ $absence->id }}" class="accordion-collapse collapse "
        aria-labelledby="absenceRequestLine{{ $absence->id }}" data-bs-parent="#absenceRequestsList">
        <div class="card">
            <div class="card-body  ">

                @include('pages.admin.attendances.absences.request.body')
                <w>
            </div>
        </div>
        <div class="card">

            @include('pages.admin.attendances.absences.request.footer')
        </div>
    </div>
    <!-- Add Comment-->
    @include('pages.admin.attendances.absences.request.comment')
