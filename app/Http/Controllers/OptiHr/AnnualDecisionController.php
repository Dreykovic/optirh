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

class AnnualDecisionController extends Controller
{
    /**
     * Display a listing of the annual decisions.
     *
     * @return  RedirectResponse|View
     */
    public function index(): RedirectResponse|View
    {
        try {
            $decisions = AnnualDecision::orderBy('created_at', 'desc')->get();
            $currentDecision = AnnualDecision::where('state', 'current')->first();

            return view('modules.opti-hr.pages.attendances.annual-decisions.index', compact('decisions', 'currentDecision'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des décisions annuelles.');
        }
    }

    /**
     * Display the current annual decision.
     *
     * @return  RedirectResponse|View
     */
    public function show()
    {

        $decision = AnnualDecision::where('state', 'current')->first();

        return view('modules.opti-hr.pages.attendances.annual-decisions.current', compact('decision'));

    }

    /**
     * Show the form for creating a new annual decision.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('modules.opti-hr.pages.attendances.annual-decisions.create-form');
    }

    /**
     * Store a newly created resource or update an existing one.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $id
     * @return \Illuminate\Http\Response
     */
    public function storeOrUpdate(Request $request, $id = null)
    {
        try {
            // Validation des données
            $validatedData = $request->validate([
                'number' => 'required|string|max:255',
                'year' => 'required|string|max:4',
                'reference' => 'nullable|string|max:255',
                'date' => 'required|date',
                'pdf' => 'nullable|file|mimes:pdf|max:2048',
                'state' => 'nullable|string|in:current,archived',
            ]);

            // Set default state if not provided
            if (!isset($validatedData['state'])) {
                $validatedData['state'] = 'current';
            }

            // If this is a current decision, archive all others
            if ($validatedData['state'] === 'current') {
                AnnualDecision::where('state', 'current')->update(['state' => 'archived']);
            }

            // Gestion du fichier PDF
            if ($request->hasFile('pdf')) {
                // If updating and there's an existing file, delete it
                if ($id) {
                    $decision = AnnualDecision::find($id);
                    if ($decision && $decision->pdf) {
                        Storage::disk('public')->delete($decision->pdf);
                    }
                }

                $pdfPath = $request->file('pdf')->store('decisions', 'public');
                $validatedData['pdf'] = $pdfPath;
            }

            // Création ou mise à jour de la décision
            $decision = AnnualDecision::updateOrCreate(
                ['id' => $id],  // Condition de mise à jour
                $validatedData   // Données mises à jour ou créées
            );

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => $id ? 'Décision mise à jour avec succès' : 'Décision créée avec succès',
                    'ok' => true,
                    'decision' => $decision
                ]);
            }

            return redirect()->route('decisions.show')
                ->with('success', $id ? 'Décision mise à jour avec succès' : 'Décision créée avec succès');
        } catch (ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Erreur de validation',
                    'errors' => $e->errors(),
                    'ok' => false
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
            \Log::error('Error saving annual decision: ' . $th->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Une erreur s\'est produite lors de l\'enregistrement de la décision',
                    'error' => $th->getMessage(),
                    'ok' => false
                ], 500);
            }

            return back()->with('error', 'Une erreur s\'est produite lors de l\'enregistrement de la décision.');
        }
    }

    /**
     * Display the specified annual decision.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        try {
            $decision = AnnualDecision::findOrFail($id);

            return view('modules.opti-hr.pages.attendances.annual-decisions.detail', compact('decision'));
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'La décision demandée n\'existe pas.');
        } catch (\Throwable $th) {
            \Log::error('Error loading annual decision detail: ' . $th->getMessage());
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des détails de la décision.');
        }
    }

    /**
     * Show the form for editing the specified annual decision.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        try {
            $decision = AnnualDecision::findOrFail($id);

            return view('modules.opti-hr.pages.attendances.annual-decisions.edit', compact('decision'));
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'La décision demandée n\'existe pas.');
        } catch (\Throwable $th) {
            \Log::error('Error loading annual decision for edit: ' . $th->getMessage());
            return back()->with('error', 'Une erreur s\'est produite lors du chargement de la décision à modifier.');
        }
    }

    /**
     * Remove the specified annual decision from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $decision = AnnualDecision::findOrFail($id);

            // Delete associated PDF if exists
            if ($decision->pdf) {
                Storage::disk('public')->delete($decision->pdf);
            }

            $decision->delete();

            return response()->json([
                'message' => 'Décision supprimée avec succès',
                'ok' => true
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'La décision demandée n\'existe pas',
                'ok' => false
            ], 404);
        } catch (\Throwable $th) {
            \Log::error('Error deleting annual decision: ' . $th->getMessage());

            return response()->json([
                'message' => 'Une erreur s\'est produite lors de la suppression de la décision',
                'error' => $th->getMessage(),
                'ok' => false
            ], 500);
        }
    }

    /**
     * Set a decision as the current one.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setCurrent($id)
    {
        try {
            // First archive the current decision
            AnnualDecision::where('state', 'current')->update(['state' => 'archived']);

            // Then set the selected decision as current
            $decision = AnnualDecision::findOrFail($id);
            $decision->state = 'current';
            $decision->save();

            return response()->json([
                'message' => 'Décision définie comme courante avec succès',
                'ok' => true
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'La décision demandée n\'existe pas',
                'ok' => false
            ], 404);
        } catch (\Throwable $th) {
            \Log::error('Error setting current decision: ' . $th->getMessage());

            return response()->json([
                'message' => 'Une erreur s\'est produite lors de la définition de la décision courante',
                'error' => $th->getMessage(),
                'ok' => false
            ], 500);
        }
    }

    /**
     * Download the PDF file for a decision.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf($id)
    {
        try {
            $decision = AnnualDecision::findOrFail($id);

            if (!$decision->pdf) {
                return back()->with('error', 'Aucun fichier PDF n\'est associé à cette décision.');
            }

            if (!Storage::disk('public')->exists($decision->pdf)) {
                return back()->with('error', 'Le fichier PDF associé à cette décision est introuvable.');
            }

            $fileName = "decision_{$decision->number}_{$decision->year}.pdf";

            return Storage::disk('public')->download($decision->pdf, $fileName);
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'La décision demandée n\'existe pas.');
        } catch (\Throwable $th) {
            \Log::error('Error downloading decision PDF: ' . $th->getMessage());
            return back()->with('error', 'Une erreur s\'est produite lors du téléchargement du fichier PDF.');
        }
    }
}