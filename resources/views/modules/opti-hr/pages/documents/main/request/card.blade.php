<div class="accordion-item  mb-3">
    @include('modules.opti-hr.pages.attendances.absences.request.header')
    <div id="collapseAbsenceRequestLine{{ $absence->id }}" class="accordion-collapse collapse "
        aria-labelledby="absenceRequestLine{{ $absence->id }}" data-bs-parent="#absenceRequestsList">
        <div class="card">
            <div class="card-body  ">

                @include('modules.opti-hr.pages.attendances.absences.request.body')

            </div>
        </div>

    </div>
    <div class="card">

        @include('modules.opti-hr.pages.attendances.absences.request.footer')
    </div>
    <!-- Add Comment-->
    @include('modules.opti-hr.pages.attendances.absences.request.comment')
</div>
