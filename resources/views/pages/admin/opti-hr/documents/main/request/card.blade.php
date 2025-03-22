<div class="accordion-item  mb-3">
    @include('pages.admin.opti-hr.attendances.absences.request.header')
    <div id="collapseAbsenceRequestLine{{ $absence->id }}" class="accordion-collapse collapse "
        aria-labelledby="absenceRequestLine{{ $absence->id }}" data-bs-parent="#absenceRequestsList">
        <div class="card">
            <div class="card-body  ">

                @include('pages.admin.opti-hr.attendances.absences.request.body')

            </div>
        </div>

    </div>
    <div class="card">

        @include('pages.admin.opti-hr.attendances.absences.request.footer')
    </div>
    <!-- Add Comment-->
    @include('pages.admin.opti-hr.attendances.absences.request.comment')
</div>
