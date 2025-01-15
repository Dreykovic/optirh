<?php

namespace App\Services;

use App\Models\Absence;
use App\Models\AbsenceType;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsencePdfService
{
    public function generate(Absence $leaveRequest)
    {
        $absenceType = AbsenceType::find($leaveRequest->absence_type_id);

        // Détermine le nom du template à charger en fonction du type d'absence
        $viewData = [
            'leaveRequest' => $leaveRequest,
            'absenceType' => $absenceType->label,
        ];

        // On choisit le template en fonction du type d'absence
        switch ($absenceType->label) {
            case 'annuel':
                $view = 'pdf.absences.leave_request_annual';
                break;
            case 'maternité':
                $view = 'pdf.absences.leave_request_maternity';
                break;
            default:
                $view = 'pdf.absences.leave_request_default'; // Template par défaut si nécessaire
                break;
        }

        // Charge la vue avec les données
        $pdf = Pdf::loadView($view, $viewData);

        // Génère et retourne le PDF
        return $pdf->download("absence_{$leaveRequest->id}.pdf");
    }
}
