{{-- Widget: Calendrier des absences --}}
<div class="card dashboard-card calendar-widget h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="icofont-ui-calendar text-primary"></i>
            Calendrier des absences
        </h5>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="showAllAbsences">
            <label class="form-check-label small text-muted" for="showAllAbsences">
                Toutes les absences
            </label>
        </div>
    </div>
    <div class="card-body">
        <div id="absenceCalendar"></div>
    </div>
</div>

@push('js')
<script>
    // Données du calendrier passées depuis le contrôleur
    var calendarEvents = @json($calendarEvents ?? []);
    var allCalendarEvents = @json($allCalendarEvents ?? []);
</script>
@endpush
