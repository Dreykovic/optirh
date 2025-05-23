<?php

namespace App\Services;

use App\Models\OptiHr\DocumentRequest;
use App\Models\OptiHr\DocumentType;
use App\Models\OptiHr\Job;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentPdfService
{
    public function generate(DocumentRequest $documentRequest)
    {
        $documentType = DocumentType::find($documentRequest->document_type_id);

        $dgJob = Job::where('title', 'DG')->first();
        $dg = $dgJob->duties->firstWhere('evolution', 'ON_GOING')->employee;
        // Détermine le nom du template à charger en fonction du type d'absence
        $viewData = [
            'documentRequest' => $documentRequest,
            'documentType' => $documentType->label,
            'dg' => $dg,
            'dgJob' => $dgJob,
        ];

        $view = 'modules.opti-hr.pdf.documents.document_request';

        // Charge la vue avec les données
        $pdf = Pdf::loadView($view, $viewData);

        // Génère et retourne le PDF
        return $pdf->download("attestation_{$documentRequest->id}.pdf");
    }
}
