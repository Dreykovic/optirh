<?php

namespace App\Services;

use App\Models\OptiHr\DocumentRequest;
use App\Models\OptiHr\DocumentType;
use App\Models\OptiHr\Job;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class DocumentPdfService
{
    /**
     * Génère le PDF d'attestation pour une demande de document
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception Si le DG ou le type de document n'est pas trouvé
     */
    public function generate(DocumentRequest $documentRequest)
    {
        try {
            // Récupérer le type de document
            $documentType = DocumentType::find($documentRequest->document_type_id);
            if (! $documentType) {
                throw new \Exception("Type de document non trouvé pour la demande #{$documentRequest->id}");
            }

            // Récupérer le DG pour la signature
            $dgJob = Job::where('title', 'DG')->first();
            if (! $dgJob) {
                throw new \Exception('Poste DG non trouvé dans le système');
            }

            $dgDuty = $dgJob->duties->firstWhere('evolution', 'ON_GOING');
            if (! $dgDuty) {
                throw new \Exception('Aucun DG en fonction actuellement');
            }

            $dg = $dgDuty->employee;
            if (! $dg) {
                throw new \Exception('Employé DG non trouvé');
            }

            // Préparer les données pour la vue
            $viewData = [
                'documentRequest' => $documentRequest,
                'documentType' => $documentType->label,
                'dg' => $dg,
                'dgJob' => $dgJob,
            ];

            $view = 'modules.opti-hr.pdf.documents.document_request';

            // Charger la vue et générer le PDF
            $pdf = Pdf::loadView($view, $viewData);

            return $pdf->download("attestation_{$documentRequest->id}.pdf");

        } catch (\Exception $e) {
            Log::error('Erreur génération PDF attestation: '.$e->getMessage(), [
                'document_request_id' => $documentRequest->id,
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
