{{-- Widget: Anniversaires a venir --}}
<div class="card dashboard-card h-100">
    <div class="card-header">
        <h5 class="card-title">
            <i class="icofont-gift text-warning"></i>
            Anniversaires a venir
        </h5>
    </div>
    <div class="card-body p-0">
        @if(isset($upcomingBirthdays) && $upcomingBirthdays->count() > 0)
            <ul class="birthday-list">
                @foreach($upcomingBirthdays as $employee)
                    @php
                        $birthday = \Carbon\Carbon::parse($employee->birth_date)->setYear(now()->year);
                        if ($birthday->lt(now())) {
                            $birthday->addYear();
                        }
                        $daysUntil = now()->diffInDays($birthday);
                        $isComingSoon = $daysUntil <= 7;
                    @endphp
                    <li class="birthday-item {{ $isComingSoon ? 'coming-soon' : '' }}" style="padding: 0.875rem 1.25rem;">
                        <x-employee-icon :employee="$employee" />
                        <div class="info">
                            <div class="name">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                            <div class="department">
                                {{ $employee->duties->first()?->job?->department?->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="date">
                            <span class="day">{{ $birthday->format('d M') }}</span>
                            @if($daysUntil == 0)
                                <span class="countdown text-success fw-bold">Aujourd'hui!</span>
                            @elseif($daysUntil == 1)
                                <span class="countdown">Demain</span>
                            @elseif($daysUntil <= 7)
                                <span class="countdown">Dans {{ $daysUntil }} jours</span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="empty-state">
                <i class="icofont-calendar d-block"></i>
                <div class="message">Aucun anniversaire a venir</div>
            </div>
        @endif
    </div>
    @if(isset($upcomingBirthdays) && $upcomingBirthdays->count() > 0)
        <div class="card-footer text-center">
            <small class="text-muted">Prochains 30 jours</small>
        </div>
    @endif
</div>
