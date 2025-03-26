<?php

namespace App\Services;

use App\Models\OptiHr\Absence;
use App\Models\OptiHr\AbsenceType;
use App\Models\OptiHr\AnnualDecision;
use App\Models\OptiHr\Job;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsencePdfService
{
    public function generate(Absence $leaveRequest, AnnualDecision $decision)
    {
        $absenceType = AbsenceType::find($leaveRequest->absence_type_id);

        $dgJob = Job::where('title', 'DG')->first();
        $dg = $dgJob->duties->firstWhere('evolution', 'ON_GOING')->employee;
        // Détermine le nom du template à charger en fonction du type d'absence
        $viewData = [
            'leaveRequest' => $leaveRequest,
            'absenceType' => $absenceType->label,
            'dg' => $dg,
            'dgJob' => $dgJob,
            'decision' => $decision,
        ];

        // On choisit le template en fonction du type d'absence
        switch ($absenceType->label) {
            case 'annuel':
                $view = 'modules.opti-hr.pdf.absences.leave_request_annual';
                break;
            case 'maternité':
                $view = 'modules.opti-hr.pdf.absences.leave_request_maternity';
                break;
            default:
                $view = 'modules.opti-hr.pdf.absences.leave_request_default'; // Template par défaut si nécessaire
                break;
        }

        // Charge la vue avec les données
        $pdf = Pdf::loadView($view, $viewData);

        // Génère et retourne le PDF
        return $pdf->download("absence_{$leaveRequest->id}.pdf");
    }
}
