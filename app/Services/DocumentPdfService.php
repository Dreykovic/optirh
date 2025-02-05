<?php

namespace App\Services;

use App\Models\DocumentRequest;
use App\Models\DocumentType;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentPdfService
{
    public function generate(DocumentRequest $documentRequest)
    {
        $absenceType = DocumentType::find($documentRequest->document_type_id);

        // Détermine le nom du template à charger en fonction du type d'absence
        $viewData = [
            'documentRequest' => $documentRequest,
            'absenceType' => $absenceType->label,
        ];

        $view = 'pdf.documents.document_request';

        // Charge la vue avec les données
        $pdf = Pdf::loadView($view, $viewData);

        // Génère et retourne le PDF
        return $pdf->download("attestation_{$documentRequest->id}.pdf");
    }
}
