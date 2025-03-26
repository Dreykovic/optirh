<?php

namespace App\Http\Controllers\OptiHr;

use App\Http\Controllers\Controller;
use App\Models\OptiHr\Absence;
use App\Models\OptiHr\Department;
use App\Models\OptiHr\DocumentRequest;
use App\Models\OptiHr\Employee;
use App\Models\OptiHr\Publication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the HR dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get basic counts
        $totalEmployees = Employee::where('status', 'ACTIVATED')->count();
        $totalDepartments = Department::where('status', 'ACTIVATED')->count();
        $pendingAbsences = Absence::where('stage', 'PENDING')->count();
        $pendingDocuments = DocumentRequest::where('stage', 'PENDING')->count();

        // Recent absences for the table
        $recentAbsences = Absence::with(['duty.employee', 'absence_type'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Convert string dates to Carbon objects for absences
        foreach ($recentAbsences as $absence) {
            if (!$absence->start_date instanceof Carbon) {
                $absence->start_date = Carbon::parse($absence->start_date);
            }
            if (!$absence->end_date instanceof Carbon) {
                $absence->end_date = Carbon::parse($absence->end_date);
            }
        }

        // Recent document requests
        $recentDocuments = DocumentRequest::with(['duty.employee', 'document_type'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent publications
        $recentPublications = Publication::with('author')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
        // Convert string dates to Carbon objects for publications
        foreach ($recentPublications as $publications) {
            if (!$publications->published_at instanceof Carbon) {
                $publications->published_at = Carbon::parse($publications->published_at);
            }
            if (!$publications->created_at instanceof Carbon) {
                $publications->created_at = Carbon::parse($publications->created_at);
            }
        }

        // Department distribution data
        $departmentData = DB::table('employees')
            ->join('duties', 'employees.id', '=', 'duties.employee_id')
            ->join('jobs', 'duties.job_id', '=', 'jobs.id')
            ->join('departments', 'jobs.department_id', '=', 'departments.id')
            ->where('employees.status', 'ACTIVATED')
            ->where('duties.evolution', 'ON_GOING')
            ->select('departments.name', DB::raw('count(*) as count'))
            ->groupBy('departments.name')
            ->orderBy('count', 'desc')
            ->get();

        $departmentLabels = $departmentData->pluck('name')->toArray();
        $departmentData = $departmentData->pluck('count')->toArray();

        // Calendar events for absences
        $approvedAbsences = Absence::with(['duty.employee', 'absence_type'])
            ->where('stage', 'APPROVED')
            ->where('end_date', '>=', Carbon::now()->subDays(30))
            ->get();

        $calendarEvents = [];
        foreach ($approvedAbsences as $absence) {
            // Ensure dates are Carbon instances
            $startDate = $absence->start_date instanceof Carbon
                ? $absence->start_date
                : Carbon::parse($absence->start_date);

            $endDate = $absence->end_date instanceof Carbon
                ? $absence->end_date
                : Carbon::parse($absence->end_date);

            $calendarEvents[] = [
                'id' => $absence->id,
                'title' => $absence->duty->employee->first_name . ' ' . $absence->duty->employee->last_name,
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->copy()->addDays(1)->format('Y-m-d'), // Add one day to make it inclusive
                'color' => '#28a745', // Success green for approved
                'description' => $absence->absence_type->label ?? 'Absence'
            ];
        }

        // All absences for toggle functionality
        $allAbsences = Absence::with(['duty.employee', 'absence_type'])
            ->where('end_date', '>=', Carbon::now()->subDays(30))
            ->get();

        $allCalendarEvents = [];
        foreach ($allAbsences as $absence) {
            $color = '#6c757d'; // Default grey

            if ($absence->stage === 'APPROVED') {
                $color = '#28a745'; // Success green
            } elseif ($absence->stage === 'PENDING') {
                $color = '#ffc107'; // Warning yellow
            } elseif ($absence->stage === 'REJECTED') {
                $color = '#dc3545'; // Danger red
            }

            // Ensure dates are Carbon instances
            $startDate = $absence->start_date instanceof Carbon
                ? $absence->start_date
                : Carbon::parse($absence->start_date);

            $endDate = $absence->end_date instanceof Carbon
                ? $absence->end_date
                : Carbon::parse($absence->end_date);

            $allCalendarEvents[] = [
                'id' => $absence->id,
                'title' => $absence->duty->employee->first_name . ' ' . $absence->duty->employee->last_name,
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->copy()->addDays(1)->format('Y-m-d'),
                'color' => $color,
                'description' => ($absence->absence_type->label ?? 'Absence') . ' (' . $absence->stage . ')'
            ];
        }

        // Add gender count for chart
        $femaleCount = Employee::where('status', 'ACTIVATED')->where('gender', 'FEMALE')->count();
        $maleCount = Employee::where('status', 'ACTIVATED')->where('gender', 'MALE')->count();

        return view('modules.opti-hr.pages.dashboard.index', compact(
            'totalEmployees',
            'totalDepartments',
            'pendingAbsences',
            'pendingDocuments',
            'recentAbsences',
            'recentDocuments',
            'recentPublications',
            'departmentLabels',
            'departmentData',
            'calendarEvents',
            'allCalendarEvents',
            'femaleCount',
            'maleCount'
        ));
    }

    /**
     * Get absence data for AJAX calendar updates.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAbsenceCalendarData(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $showAll = $request->input('showAll', false);

        $query = Absence::with(['duty.employee', 'absence_type'])
            ->where('end_date', '>=', $start)
            ->where('start_date', '<=', $end);

        if (!$showAll) {
            $query->where('stage', 'APPROVED');
        }

        $absences = $query->get();

        $events = [];
        foreach ($absences as $absence) {
            $color = '#6c757d'; // Default grey

            if ($absence->stage === 'APPROVED') {
                $color = '#28a745'; // Success green
            } elseif ($absence->stage === 'PENDING') {
                $color = '#ffc107'; // Warning yellow
            } elseif ($absence->stage === 'REJECTED') {
                $color = '#dc3545'; // Danger red
            }

            // Ensure dates are Carbon instances
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
                'description' => ($absence->absence_type->label ?? 'Absence') . ' (' . $absence->stage . ')'
            ];
        }

        return response()->json($events);
    }

    /**
     * Get employee stats data for AJAX charts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployeeStats()
    {
        // Gender distribution
        $genderDistribution = Employee::select('gender', DB::raw('count(*) as count'))
            ->where('status', 'ACTIVATED')
            ->groupBy('gender')
            ->get();

        // Employees per department
        $departmentDistribution = DB::table('employees')
            ->join('duties', 'employees.id', '=', 'duties.employee_id')
            ->join('jobs', 'duties.job_id', '=', 'jobs.id')
            ->join('departments', 'jobs.department_id', '=', 'departments.id')
            ->where('employees.status', 'ACTIVATED')
            ->where('duties.evolution', 'ON_GOING')
            ->select('departments.name', DB::raw('count(*) as count'))
            ->groupBy('departments.name')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json([
            'genderDistribution' => $genderDistribution,
            'departmentDistribution' => $departmentDistribution
        ]);
    }
}