<?php

namespace App\Http\Controllers\OptiHr;

use App\Http\Controllers\Controller;
use App\Models\OptiHr\AnnualDecision;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class AnnualDecisionController extends Controller
{
    public function __construct()
    {
        parent::__construct(activityLogger: app(ActivityLogService::class)); // Injection automatique

        $this->middleware(['permission:configurer-une-absence|voir-un-tout'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:configurer-une-absence|créer-un-tout'], ['only' => ['store', 'storeOrUpdate', 'setCurrent', 'downloadPdf']]);

        $this->middleware(['permission:configurer-une-absence|écrire-un-tout'], ['only' => ['destroy']]);

    }
    /**
     * Display a listing of the annual decisions.
     *
     * @return  RedirectResponse|View
     */
    public function index(): RedirectResponse|View
    {
        try {
            $decisions = AnnualDecision::orderBy('created_at', 'desc')
                ->paginate(15);
            $currentDecision = AnnualDecision::where('state', 'current')->first();
            
            $this->activityLogger->log(
                'view',
                "Consultation de la liste des décisions"
            );
            
            return view('modules.opti-hr.pages.attendances.annual-decisions.index', compact('decisions', 'currentDecision'));
        } catch (\Exception $e) {
            Log::error('Error loading annual decisions: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des décisions annuelles.');
        }
    }

    /**
     * Display the current annual decision.
     *
     * @return  RedirectResponse|View
     */
    public function show(): RedirectResponse|View
    {
        try {
            $decision = AnnualDecision::where('state', 'current')->first();
            
            if ($decision) {
                $this->activityLogger->log(
                    'access',
                    "Consultation de la décision actuelle {$decision->number}/{$decision->year}/{$decision->reference}",
                    $decision
                );
            }

            return view('modules.opti-hr.pages.attendances.annual-decisions.current', compact('decision'));
        } catch (\Exception $e) {
            Log::error('Error displaying current decision: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur s\'est produite lors de l\'affichage de la décision courante.');
        }
    }



    /**
     * Store a newly created resource or update an existing one.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $id
     * @return JsonResponse|RedirectResponse
     */
    public function storeOrUpdate(Request $request, $id = null): JsonResponse|RedirectResponse
    {
        try {
            // Validation des données avec règles améliorées
            $rules = [
                'number' => 'required|string|max:255',
                'year' => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 1),
                'reference' => 'nullable|string|max:255',
                'date' => 'required|date|before_or_equal:today',
                'pdf' => 'nullable|file|mimes:pdf|max:10240', // Increased to 10MB
                'state' => 'nullable|string|in:current,outdated',
            ];
            
            // Add unique validation for number/year combination
            if ($id) {
                $rules['number'] .= '|unique:annual_decisions,number,' . $id . ',id,year,' . $request->year;
            } else {
                $rules['number'] .= '|unique:annual_decisions,number,NULL,id,year,' . $request->year;
            }
            
            $validatedData = $request->validate($rules, [
                'number.unique' => 'Une décision avec ce numéro existe déjà pour cette année.',
                'year.digits' => 'L\'année doit contenir exactement 4 chiffres.',
                'year.max' => 'L\'année ne peut pas être supérieure à l\'année prochaine.',
                'date.before_or_equal' => 'La date ne peut pas être dans le futur.',
                'pdf.max' => 'Le fichier PDF ne doit pas dépasser 10 MB.'
            ]);

            DB::beginTransaction();
            
            // Set default state if not provided
            if (!isset($validatedData['state'])) {
                $validatedData['state'] = 'current';
            }

            // If this is a current decision, archive all others
            if ($validatedData['state'] === 'current') {
                AnnualDecision::where('state', 'current')
                    ->where('id', '!=', $id)
                    ->update(['state' => 'outdated']);
            }

            // Gestion du fichier PDF
            if ($request->hasFile('pdf')) {
                $pdfFile = $request->file('pdf');
                
                // Validate PDF is not corrupted
                if (!$pdfFile->isValid()) {
                    throw new \Exception('Le fichier PDF téléchargé est corrompu.');
                }
                
                // If updating and there's an existing file, delete it
                if ($id) {
                    $existingDecision = AnnualDecision::find($id);
                    if ($existingDecision && $existingDecision->pdf) {
                        try {
                            Storage::disk('public')->delete($existingDecision->pdf);
                        } catch (\Exception $e) {
                            Log::warning('Could not delete old PDF: ' . $e->getMessage());
                        }
                    }
                }

                // Store with custom filename
                $fileName = 'decision_' . $validatedData['number'] . '_' . $validatedData['year'] . '_' . time() . '.pdf';
                $pdfPath = $pdfFile->storeAs('decisions', $fileName, 'public');
                $validatedData['pdf'] = $pdfPath;
            }

            // Création ou mise à jour de la décision
            $decision = AnnualDecision::updateOrCreate(
                ['id' => $id],
                $validatedData
            );
            
            $action = $id ? 'updated' : 'created';
            $this->activityLogger->log(
                $action,
                ($id ? 'Mise à jour' : 'Création') . " de la décision {$decision->number}/{$decision->year}" . 
                ($decision->reference ? "/{$decision->reference}" : ''),
                $decision
            );
            
            DB::commit();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => $id ? 'Décision mise à jour avec succès' : 'Décision créée avec succès',
                    'ok' => true,
                    'redirect' => route('decisions.show'),
                    'decision' => $decision
                ]);
            }

            return redirect()->route('decisions.show')
                ->with('success', $id ? 'Décision mise à jour avec succès' : 'Décision créée avec succès');
                
        } catch (ValidationException $e) {
            DB::rollBack();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Erreur de validation',
                    'errors' => $e->errors(),
                    'ok' => false
                ], 422);
            }
            
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing/updating annual decision: ' . $e->getMessage(), [
                'id' => $id,
                'data' => $request->except('pdf'),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Une erreur s\'est produite lors de l\'enregistrement de la décision.',
                    'ok' => false
                ], 500);
            }
            
            return back()->withInput()
                ->with('error', 'Une erreur s\'est produite lors de l\'enregistrement de la décision.');
        }
    }





    /**
     * Remove the specified annual decision from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $decision = AnnualDecision::findOrFail($id);
            
            // Check if this is the current decision
            if ($decision->state === 'current') {
                return response()->json([
                    'message' => 'Impossible de supprimer la décision courante. Veuillez d\'abord définir une autre décision comme courante.',
                    'ok' => false
                ], 400);
            }
            
            DB::beginTransaction();
            
            // Store decision info for logging
            $decisionInfo = "{$decision->number}/{$decision->year}" . 
                           ($decision->reference ? "/{$decision->reference}" : '');
            
            // Delete associated PDF if exists
            if ($decision->pdf) {
                try {
                    if (Storage::disk('public')->exists($decision->pdf)) {
                        Storage::disk('public')->delete($decision->pdf);
                    }
                } catch (\Exception $e) {
                    Log::warning('Could not delete PDF file: ' . $e->getMessage());
                }
            }

            $decision->delete();
            
            $this->activityLogger->log(
                'deleted',
                "Suppression de la décision {$decisionInfo}"
            );
            
            DB::commit();

            return response()->json([
                'message' => 'Décision supprimée avec succès',
                'ok' => true,
                'redirect' => route('decisions.show')
            ]);
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'La décision demandée n\'existe pas',
                'ok' => false
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting annual decision: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Une erreur s\'est produite lors de la suppression de la décision',
                'ok' => false
            ], 500);
        }
    }

    /**
     * Set a decision as the current one.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function setCurrent($id): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $decision = AnnualDecision::findOrFail($id);
            
            // Check if already current
            if ($decision->state === 'current') {
                return response()->json([
                    'message' => 'Cette décision est déjà la décision courante',
                    'ok' => true
                ]);
            }
            
            // Archive all current decisions
            $archivedCount = AnnualDecision::where('state', 'current')
                ->where('id', '!=', $id)
                ->update(['state' => 'outdated']);

            // Set the selected decision as current
            $decision->state = 'current';
            $decision->save();
            
            $this->activityLogger->log(
                'updated',
                "Définition de la décision {$decision->number}/{$decision->year}" .
                ($decision->reference ? "/{$decision->reference}" : '') . " comme courante",
                $decision
            );
            
            DB::commit();

            return response()->json([
                'message' => 'Décision définie comme courante avec succès',
                'ok' => true,
                'archived_count' => $archivedCount
            ]);
            
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'La décision demandée n\'existe pas',
                'ok' => false
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error setting current decision: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Une erreur s\'est produite lors de la définition de la décision courante',
                'ok' => false
            ], 500);
        }
    }

    /**
     * Download the PDF file for a decision.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadPdf($id)
    {
        try {
            $decision = AnnualDecision::findOrFail($id);
            
            if (!$decision->pdf) {
                return back()->with('error', 'Aucun fichier PDF n\'est associé à cette décision.');
            }

            if (!Storage::disk('public')->exists($decision->pdf)) {
                // Try to handle missing file gracefully
                $decision->pdf = null;
                $decision->save();
                
                Log::warning('PDF file missing for decision', [
                    'id' => $id,
                    'expected_path' => $decision->pdf
                ]);
                
                return back()->with('error', 'Le fichier PDF associé à cette décision est introuvable.');
            }
            
            $this->activityLogger->log(
                'download',
                "Téléchargement du PDF de la décision {$decision->number}/{$decision->year}" .
                ($decision->reference ? "/{$decision->reference}" : ''),
                $decision
            );

            // Sanitize filename
            $fileName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', 
                "decision_{$decision->number}_{$decision->year}.pdf");

            return Storage::disk('public')->download($decision->pdf, $fileName);
            
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'La décision demandée n\'existe pas.');
        } catch (\Exception $e) {
            Log::error('Error downloading PDF: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Une erreur s\'est produite lors du téléchargement du PDF.');
        }
    }
}