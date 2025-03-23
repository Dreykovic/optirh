<!-- resources/views/components/activity-log-badge.blade.php -->
@php
    use App\Config\ActivityLogActions;

    $actionInfo = ActivityLogActions::getAction($action ?? '');
    $display = $actionInfo['display'] ?? $action;
    $icon = $actionInfo['icon'] ?? 'fa-question';
    $color = $actionInfo['color'] ?? 'text-secondary';
@endphp

<span class="badge {{ $color }} d-inline-flex align-items-center">
    <i class="fas {{ $icon }} me-1"></i>
    {{ $display }}
</span>
