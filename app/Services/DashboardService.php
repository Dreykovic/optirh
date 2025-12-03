<?php

namespace App\Services;

use App\Models\OptiHr\Absence;
use App\Models\OptiHr\Department;
use App\Models\OptiHr\DocumentRequest;
use App\Models\OptiHr\Employee;
use App\Models\OptiHr\Publication;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Service de gestion du tableau de bord
 *
 * Ce service centralise toutes les requêtes et calculs
 * nécessaires pour le dashboard OptiHR.
 */
class DashboardService
{
    /**
     * Nombre de jours ouvrés par mois (approximation)
     */
    protected const WORKING_DAYS_PER_MONTH = 22;

    // =========================================================================
    // STATISTIQUES DE BASE
    // =========================================================================

    /**
     * Récupère les statistiques de base avec tendances
     */
    public function getStatsWithTrends(): array
    {
        return [
            [
                'key' => 'employees',
                'label' => 'Total employés',
                'value' => $this->getTotalEmployees(),
                'icon' => 'icofont-users-alt-5',
                'iconClass' => 'text-primary',
                'iconBgClass' => 'bg-primary-subtle',
                'trend' => $this->getEmployeeTrend(),
            ],
            [
                'key' => 'departments',
                'label' => 'Départements',
                'value' => $this->getTotalDepartments(),
                'icon' => 'icofont-building',
                'iconClass' => 'text-success',
                'iconBgClass' => 'bg-success-subtle',
                'trend' => null,
            ],
            [
                'key' => 'pending_absences',
                'label' => 'Absences en attente',
                'value' => $this->getPendingAbsences(),
                'icon' => 'icofont-calendar',
                'iconClass' => 'text-warning',
                'iconBgClass' => 'bg-warning-subtle',
                'trend' => $this->getAbsenceTrend(),
            ],
            [
                'key' => 'pending_documents',
                'label' => 'Documents en attente',
                'value' => $this->getPendingDocuments(),
                'icon' => 'icofont-file-document',
                'iconClass' => 'text-info',
                'iconBgClass' => 'bg-info-subtle',
                'trend' => $this->getDocumentTrend(),
            ],
        ];
    }

    /**
     * Compte le total des employés actifs
     */
    public function getTotalEmployees(): int
    {
        return Employee::where('status', 'ACTIVATED')->count();
    }

    /**
     * Compte le total des départements actifs
     */
    public function getTotalDepartments(): int
    {
        return Department::where('status', 'ACTIVATED')->count();
    }

    /**
     * Compte les absences en attente
     */
    public function getPendingAbsences(): int
    {
        return Absence::where('stage', 'PENDING')->count();
    }

    /**
     * Compte les demandes de documents en attente
     */
    public function getPendingDocuments(): int
    {
        return DocumentRequest::where('stage', 'PENDING')->count();
    }

    // =========================================================================
    // TENDANCES (COMPARAISON PÉRIODIQUE)
    // =========================================================================

    /**
     * Calcule la tendance des employés (nouveaux ce mois vs mois dernier)
     */
    public function getEmployeeTrend(): array
    {
        $currentMonth = Employee::where('status', 'ACTIVATED')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonth = Employee::where('status', 'ACTIVATED')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        return $this->calculateTrend($currentMonth, $lastMonth);
    }

    /**
     * Calcule la tendance des absences (demandes ce mois vs mois dernier)
     */
    public function getAbsenceTrend(): array
    {
        $currentMonth = Absence::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonth = Absence::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        return $this->calculateTrend($currentMonth, $lastMonth);
    }

    /**
     * Calcule la tendance des documents (demandes ce mois vs mois dernier)
     */
    public function getDocumentTrend(): array
    {
        $currentMonth = DocumentRequest::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonth = DocumentRequest::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        return $this->calculateTrend($currentMonth, $lastMonth);
    }

    /**
     * Calcule le pourcentage de tendance
     */
    protected function calculateTrend(int $current, int $previous): array
    {
        if ($previous === 0) {
            $percentage = $current > 0 ? 100 : 0;
        } else {
            $percentage = round((($current - $previous) / $previous) * 100, 1);
        }

        return [
            'current' => $current,
            'previous' => $previous,
            'percentage' => abs($percentage),
            'direction' => $percentage >= 0 ? 'up' : 'down',
        ];
    }

    // =========================================================================
    // KPIs (INDICATEURS CLÉS DE PERFORMANCE)
    // =========================================================================

    /**
     * Récupère tous les KPIs
     */
    public function getKpis(): array
    {
        return [
            $this->getAbsenceRate(),
            $this->getDocumentProcessingTime(),
            $this->getApprovalRate(),
        ];
    }

    /**
     * Calcule le taux d'absentéisme
     * (jours d'absence / jours ouvrés totaux) * 100
     */
    public function getAbsenceRate(): array
    {
        $totalEmployees = $this->getTotalEmployees();
        $workingDays = self::WORKING_DAYS_PER_MONTH;

        $totalAbsenceDays = Absence::where('stage', 'APPROVED')
            ->whereMonth('start_date', now()->month)
            ->whereYear('start_date', now()->year)
            ->sum('requested_days');

        $maxDays = $totalEmployees * $workingDays;
        $rate = $maxDays > 0 ? ($totalAbsenceDays / $maxDays) * 100 : 0;

        return [
            'key' => 'absence_rate',
            'value' => round($rate, 2),
            'label' => "Taux d'absentéisme",
            'unit' => '%',
            'color' => $rate > 5 ? 'danger' : ($rate > 3 ? 'warning' : 'success'),
        ];
    }

    /**
     * Calcule le temps moyen de traitement des documents
     */
    public function getDocumentProcessingTime(): array
    {
        $driver = DB::getDriverName();

        // Syntaxe selon le driver de base de données
        $dateDiffExpression = match ($driver) {
            'mysql', 'mariadb' => 'AVG(DATEDIFF(date_of_approval, date_of_application))',
            'pgsql' => 'AVG(DATE(date_of_approval) - DATE(date_of_application))',
            'sqlite' => 'AVG(JULIANDAY(date_of_approval) - JULIANDAY(date_of_application))',
            'sqlsrv' => 'AVG(DATEDIFF(day, date_of_application, date_of_approval))',
            default => 'AVG(DATE(date_of_approval) - DATE(date_of_application))',
        };

        $avgDays = DocumentRequest::where('stage', 'APPROVED')
            ->whereNotNull('date_of_approval')
            ->whereMonth('date_of_approval', now()->month)
            ->whereYear('date_of_approval', now()->year)
            ->selectRaw("{$dateDiffExpression} as avg_days")
            ->value('avg_days');

        $value = round($avgDays ?? 0, 1);

        return [
            'key' => 'processing_time',
            'value' => $value,
            'label' => 'Temps moyen traitement',
            'unit' => 'jours',
            'color' => $value > 7 ? 'danger' : ($value > 3 ? 'warning' : 'success'),
        ];
    }

    /**
     * Calcule le taux d'approbation des absences
     */
    public function getApprovalRate(): array
    {
        $total = Absence::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereIn('stage', ['APPROVED', 'REJECTED'])
            ->count();

        $approved = Absence::where('stage', 'APPROVED')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $rate = $total > 0 ? ($approved / $total) * 100 : 0;

        return [
            'key' => 'approval_rate',
            'value' => round($rate, 1),
            'label' => "Taux d'approbation",
            'unit' => '%',
            'color' => 'primary',
        ];
    }

    // =========================================================================
    // DONNÉES RÉCENTES
    // =========================================================================

    /**
     * Récupère les absences récentes
     */
    public function getRecentAbsences(int $limit = 5): Collection
    {
        $absences = Absence::with(['duty.employee', 'duty.job.department', 'absence_type'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        // Assurer que les dates sont des instances Carbon
        foreach ($absences as $absence) {
            if (!$absence->start_date instanceof Carbon) {
                $absence->start_date = Carbon::parse($absence->start_date);
            }
            if (!$absence->end_date instanceof Carbon) {
                $absence->end_date = Carbon::parse($absence->end_date);
            }
        }

        return $absences;
    }

    /**
     * Récupère les demandes de documents récentes
     */
    public function getRecentDocuments(int $limit = 5): Collection
    {
        return DocumentRequest::with(['duty.employee', 'document_type'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Récupère les publications récentes
     */
    public function getRecentPublications(int $limit = 5): Collection
    {
        $publications = Publication::with('author')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($publications as $publication) {
            if ($publication->published_at && !$publication->published_at instanceof Carbon) {
                $publication->published_at = Carbon::parse($publication->published_at);
            }
        }

        return $publications;
    }

    /**
     * Récupère les anniversaires à venir
     */
    public function getUpcomingBirthdays(int $days = 30): Collection
    {
        $today = now();
        $endDate = now()->addDays($days);

        return Employee::where('status', 'ACTIVATED')
            ->whereNotNull('birth_date')
            ->with(['duties' => function ($q) {
                $q->where('evolution', 'ON_GOING')->with('job.department');
            }])
            ->get()
            ->filter(function ($employee) use ($today, $endDate) {
                if (!$employee->birth_date) {
                    return false;
                }

                $birthday = Carbon::parse($employee->birth_date)->setYear($today->year);

                // Si l'anniversaire est déjà passé cette année, vérifier l'année prochaine
                if ($birthday->lt($today)) {
                    $birthday->addYear();
                }

                return $birthday->between($today, $endDate);
            })
            ->sortBy(function ($employee) use ($today) {
                $birthday = Carbon::parse($employee->birth_date)->setYear($today->year);
                if ($birthday->lt($today)) {
                    $birthday->addYear();
                }
                return $birthday;
            })
            ->take(10)
            ->values();
    }

    // =========================================================================
    // DONNÉES DE DISTRIBUTION (GRAPHIQUES)
    // =========================================================================

    /**
     * Récupère la distribution par département
     */
    public function getDepartmentDistribution(): array
    {
        $data = DB::table('employees')
            ->join('duties', 'employees.id', '=', 'duties.employee_id')
            ->join('jobs', 'duties.job_id', '=', 'jobs.id')
            ->join('departments', 'jobs.department_id', '=', 'departments.id')
            ->where('employees.status', 'ACTIVATED')
            ->where('duties.evolution', 'ON_GOING')
            ->select('departments.name', DB::raw('count(*) as count'))
            ->groupBy('departments.name')
            ->orderBy('count', 'desc')
            ->get();

        return [
            'labels' => $data->pluck('name')->toArray(),
            'data' => $data->pluck('count')->toArray(),
        ];
    }

    /**
     * Récupère la distribution par genre
     */
    public function getGenderDistribution(): array
    {
        $femaleCount = Employee::where('status', 'ACTIVATED')->where('gender', 'FEMALE')->count();
        $maleCount = Employee::where('status', 'ACTIVATED')->where('gender', 'MALE')->count();

        return [
            'labels' => ['Femme', 'Homme'],
            'data' => [$femaleCount, $maleCount],
        ];
    }

    /**
     * Récupère la distribution par type d'absence
     */
    public function getAbsenceTypeDistribution(): array
    {
        $data = Absence::with('absence_type')
            ->where('stage', 'APPROVED')
            ->whereYear('start_date', now()->year)
            ->select('absence_type_id', DB::raw('count(*) as count'))
            ->groupBy('absence_type_id')
            ->get();

        $labels = [];
        $counts = [];

        foreach ($data as $item) {
            $labels[] = $item->absence_type->label ?? 'Autre';
            $counts[] = $item->count;
        }

        return [
            'labels' => $labels,
            'data' => $counts,
        ];
    }

    // =========================================================================
    // CALENDRIER DES ABSENCES
    // =========================================================================

    /**
     * Récupère les événements du calendrier (absences approuvées)
     */
    public function getCalendarEvents(bool $showAll = false): array
    {
        $query = Absence::with(['duty.employee', 'absence_type'])
            ->where('end_date', '>=', Carbon::now()->subDays(30));

        if (!$showAll) {
            $query->where('stage', 'APPROVED');
        }

        $absences = $query->get();
        $events = [];

        foreach ($absences as $absence) {
            $color = match ($absence->stage) {
                'APPROVED' => '#10b981', // Vert
                'PENDING' => '#f59e0b',  // Orange
                'REJECTED' => '#ef4444', // Rouge
                default => '#6b7280',    // Gris
            };

            $startDate = $absence->start_date instanceof Carbon
                ? $absence->start_date
                : Carbon::parse($absence->start_date);

            $endDate = $absence->end_date instanceof Carbon
                ? $absence->end_date
                : Carbon::parse($absence->end_date);

            $events[] = [
                'id' => $absence->id,
                'title' => $absence->duty->employee->first_name . ' ' . $absence->duty->employee->last_name,
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->copy()->addDays(1)->format('Y-m-d'),
                'color' => $color,
                'description' => ($absence->absence_type->label ?? 'Absence') .
                    ($showAll ? ' (' . $absence->stage . ')' : ''),
            ];
        }

        return $events;
    }

    // =========================================================================
    // DONNÉES PERSONNELLES (POUR RÔLE EMPLOYEE)
    // =========================================================================

    /**
     * Récupère les statistiques personnelles d'un employé
     */
    public function getEmployeePersonalStats(Employee $employee): array
    {
        $currentDuty = $employee->duties()->where('evolution', 'ON_GOING')->first();

        $absencesUsed = 0;
        $pendingAbsences = 0;
        $pendingDocuments = 0;
        $absenceBalance = 0;

        if ($currentDuty) {
            $absenceBalance = $currentDuty->absence_balance ?? 0;

            $absencesUsed = Absence::where('duty_id', $currentDuty->id)
                ->where('stage', 'APPROVED')
                ->whereYear('start_date', now()->year)
                ->sum('requested_days');

            $pendingAbsences = Absence::where('duty_id', $currentDuty->id)
                ->where('stage', 'PENDING')
                ->count();

            $pendingDocuments = DocumentRequest::where('duty_id', $currentDuty->id)
                ->where('stage', 'PENDING')
                ->count();
        }

        return [
            'absence_balance' => $absenceBalance,
            'absences_used' => $absencesUsed,
            'pending_absences' => $pendingAbsences,
            'pending_documents' => $pendingDocuments,
        ];
    }

    /**
     * Récupère les absences d'un employé
     */
    public function getEmployeeAbsences(Employee $employee, int $limit = 5): Collection
    {
        $currentDuty = $employee->duties()->where('evolution', 'ON_GOING')->first();

        if (!$currentDuty) {
            return collect();
        }

        $absences = Absence::with(['absence_type'])
            ->where('duty_id', $currentDuty->id)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($absences as $absence) {
            if (!$absence->start_date instanceof Carbon) {
                $absence->start_date = Carbon::parse($absence->start_date);
            }
            if (!$absence->end_date instanceof Carbon) {
                $absence->end_date = Carbon::parse($absence->end_date);
            }
        }

        return $absences;
    }

    /**
     * Récupère les demandes de documents d'un employé
     */
    public function getEmployeeDocuments(Employee $employee, int $limit = 5): Collection
    {
        $currentDuty = $employee->duties()->where('evolution', 'ON_GOING')->first();

        if (!$currentDuty) {
            return collect();
        }

        return DocumentRequest::with(['document_type'])
            ->where('duty_id', $currentDuty->id)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    // =========================================================================
    // FILE D'APPROBATION (POUR MANAGERS)
    // =========================================================================

    /**
     * Récupère la file d'approbation selon le rôle
     */
    public function getApprovalQueue(string $role, int $limit = 10): Collection
    {
        $query = Absence::with(['duty.employee', 'duty.job.department', 'absence_type'])
            ->where('stage', 'PENDING');

        // Filtrer selon le rôle et le niveau d'approbation
        switch ($role) {
            case 'DG':
                $query->whereIn('level', ['TWO', 'THREE']);
                break;
            case 'DSAF':
                $query->whereIn('level', ['ONE', 'TWO', 'THREE']);
                break;
            case 'GRH':
            case 'ADMIN':
                // Accès à toutes les demandes en attente
                break;
            default:
                return collect();
        }

        $absences = $query->orderBy('created_at', 'asc')
            ->take($limit)
            ->get();

        foreach ($absences as $absence) {
            if (!$absence->start_date instanceof Carbon) {
                $absence->start_date = Carbon::parse($absence->start_date);
            }
            if (!$absence->end_date instanceof Carbon) {
                $absence->end_date = Carbon::parse($absence->end_date);
            }
        }

        return $absences;
    }

    /**
     * Compte les approbations en attente par type
     */
    public function getPendingCounts(): array
    {
        return [
            'absences' => Absence::where('stage', 'PENDING')->count(),
            'documents' => DocumentRequest::where('stage', 'PENDING')->count(),
        ];
    }

    // =========================================================================
    // STATISTIQUES EXÉCUTIVES (POUR DG)
    // =========================================================================

    /**
     * Récupère les statistiques pour la vue exécutive
     */
    public function getExecutiveStats(): array
    {
        return [
            [
                'key' => 'employees',
                'label' => 'Effectif total',
                'value' => $this->getTotalEmployees(),
                'icon' => 'icofont-users-alt-5',
                'iconClass' => 'text-primary',
                'iconBgClass' => 'bg-primary-subtle',
                'trend' => $this->getEmployeeTrend(),
            ],
            [
                'key' => 'pending_approvals',
                'label' => 'Approbations en attente',
                'value' => $this->getPendingAbsences() + $this->getPendingDocuments(),
                'icon' => 'icofont-clock-time',
                'iconClass' => 'text-warning',
                'iconBgClass' => 'bg-warning-subtle',
                'trend' => null,
            ],
            [
                'key' => 'absences_month',
                'label' => 'Absences ce mois',
                'value' => Absence::where('stage', 'APPROVED')
                    ->whereMonth('start_date', now()->month)
                    ->whereYear('start_date', now()->year)
                    ->count(),
                'icon' => 'icofont-calendar',
                'iconClass' => 'text-info',
                'iconBgClass' => 'bg-info-subtle',
                'trend' => $this->getAbsenceTrend(),
            ],
        ];
    }

    /**
     * Récupère les KPIs exécutifs
     */
    public function getExecutiveKpis(): array
    {
        return [
            $this->getAbsenceRate(),
            $this->getApprovalRate(),
        ];
    }

    // =========================================================================
    // DONNÉES AJAX (REFRESH)
    // =========================================================================

    /**
     * Récupère toutes les données rafraîchissables
     */
    public function getRefreshData(): array
    {
        return [
            'stats' => [
                'employees' => $this->getTotalEmployees(),
                'departments' => $this->getTotalDepartments(),
                'pending_absences' => $this->getPendingAbsences(),
                'pending_documents' => $this->getPendingDocuments(),
            ],
            'events' => $this->getCalendarEvents(),
        ];
    }
}
