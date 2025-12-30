<?php

namespace App\Http\Controllers\OptiHr;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

/**
 * Controleur du tableau de bord OptiHR
 *
 * Gere l'affichage du dashboard avec contenu personnalise selon le role
 */
class DashboardController extends Controller
{
    /**
     * Service de gestion du dashboard
     */
    protected DashboardService $dashboardService;

    /**
     * Ordre de priorite des roles
     */
    protected array $roleOrder = ['ADMIN', 'GRH', 'DG', 'DSAF', 'DRAJ', 'EMPLOYEE'];

    /**
     * Constructeur avec injection du service
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Affiche le tableau de bord
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        $role = $this->getPrimaryRole($user);

        // Donnees communes a tous les roles
        $commonData = $this->getCommonData();

        // Donnees specifiques au role
        $roleData = match ($role) {
            'ADMIN' => $this->getAdminData(),
            'GRH' => $this->getGrhData(),
            'DG' => $this->getDgData(),
            'DSAF' => $this->getDsafData(),
            'DRAJ' => $this->getDrajData(),
            'EMPLOYEE' => $this->getEmployeeData($user),
            default => $this->getEmployeeData($user),
        };

        return view('modules.opti-hr.pages.dashboard.index', array_merge(
            $commonData,
            $roleData,
            ['userRole' => strtolower($role)]
        ));
    }

    /**
     * Determine le role principal de l'utilisateur
     *
     * @param  \App\Models\User  $user
     */
    protected function getPrimaryRole($user): string
    {
        foreach ($this->roleOrder as $role) {
            if ($user->hasRole($role)) {
                return $role;
            }
        }

        return 'EMPLOYEE';
    }

    /**
     * Donnees communes a tous les roles
     */
    protected function getCommonData(): array
    {
        return [
            'recentPublications' => $this->dashboardService->getRecentPublications(5),
            'upcomingBirthdays' => $this->dashboardService->getUpcomingBirthdays(30),
        ];
    }

    /**
     * Donnees pour le role EMPLOYEE
     *
     * @param  \App\Models\User  $user
     */
    protected function getEmployeeData($user): array
    {
        // Si l'utilisateur n'a pas d'employé associé, retourner des données vides
        if (! $user->hasEmployee()) {
            return [
                'personalStats' => [
                    'absence_balance' => 0,
                    'absences_used' => 0,
                    'pending_absences' => 0,
                    'pending_documents' => 0,
                ],
                'myRecentAbsences' => collect(),
                'myRecentDocuments' => collect(),
            ];
        }

        $employee = $user->employee;

        return [
            'personalStats' => $this->dashboardService->getEmployeePersonalStats($employee),
            'myRecentAbsences' => $this->dashboardService->getEmployeeAbsences($employee, 5),
            'myRecentDocuments' => $this->dashboardService->getEmployeeDocuments($employee, 5),
        ];
    }

    /**
     * Donnees pour le role GRH (acces complet RH)
     */
    protected function getGrhData(): array
    {
        return [
            // Stats avec tendances
            'statsCards' => $this->dashboardService->getStatsWithTrends(),
            'kpis' => $this->dashboardService->getKpis(),

            // Tables
            'recentAbsences' => $this->dashboardService->getRecentAbsences(10),
            'recentDocuments' => $this->dashboardService->getRecentDocuments(10),

            // Graphiques
            'departmentData' => $this->dashboardService->getDepartmentDistribution(),
            'genderData' => $this->dashboardService->getGenderDistribution(),

            // Calendrier
            'calendarEvents' => $this->dashboardService->getCalendarEvents(false),
            'allCalendarEvents' => $this->dashboardService->getCalendarEvents(true),

            // Compteurs
            'pendingCounts' => $this->dashboardService->getPendingCounts(),
        ];
    }

    /**
     * Donnees pour le role DG (vue executive)
     */
    protected function getDgData(): array
    {
        return [
            'statsCards' => $this->dashboardService->getExecutiveStats(),
            'kpis' => $this->dashboardService->getExecutiveKpis(),
            'approvalQueue' => $this->dashboardService->getApprovalQueue('DG'),
            'departmentData' => $this->dashboardService->getDepartmentDistribution(),
            'recentAbsences' => $this->dashboardService->getRecentAbsences(5),
        ];
    }

    /**
     * Donnees pour le role DSAF
     */
    protected function getDsafData(): array
    {
        return [
            'statsCards' => $this->dashboardService->getStatsWithTrends(),
            'approvalQueue' => $this->dashboardService->getApprovalQueue('DSAF'),
            'recentAbsences' => $this->dashboardService->getRecentAbsences(5),
        ];
    }

    /**
     * Donnees pour le role DRAJ
     */
    protected function getDrajData(): array
    {
        return [
            'statsCards' => $this->dashboardService->getStatsWithTrends(),
        ];
    }

    /**
     * Donnees pour le role ADMIN (acces complet)
     */
    protected function getAdminData(): array
    {
        // Admin a le meme acces que GRH
        return $this->getGrhData();
    }

    /**
     * Endpoint AJAX pour les donnees du calendrier
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAbsenceCalendarData(Request $request)
    {
        $showAll = $request->boolean('showAll', false);
        $events = $this->dashboardService->getCalendarEvents($showAll);

        return response()->json($events);
    }

    /**
     * Endpoint AJAX pour les statistiques des employes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployeeStats()
    {
        return response()->json([
            'genderDistribution' => $this->dashboardService->getGenderDistribution(),
            'departmentDistribution' => $this->dashboardService->getDepartmentDistribution(),
        ]);
    }

    /**
     * Endpoint AJAX pour rafraichir le dashboard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json($this->dashboardService->getRefreshData());
    }
}
