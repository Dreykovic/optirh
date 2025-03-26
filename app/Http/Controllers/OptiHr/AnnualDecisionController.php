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
            $decisions = AnnualDecision::orderBy('created_at', 'desc')->get();
            $currentDecision = AnnualDecision::where('state', 'current')->first();
            $this->activityLogger->log(
                'view',
                "Consultation de la liste des décisions"
            );
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
        $this->activityLogger->log(
            'access',
            "Consultation de la décision actuel {$decision->number}/{$decision->year}/{$decision->reference}",
            $decision
        );
        return view('modules.opti-hr.pages.attendances.annual-decisions.current', compact('decision'));

    }



    /**
     * Store a newly created resource or update an existing one.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $id
     * @return \Illuminate\Http\Response|RedirectResponse
     */
    public function storeOrUpdate(Request $request, $id = null)
    {

        // Validation des données
        $validatedData = $request->validate([
            'number' => 'required|string|max:255',
            'year' => 'required|string|max:4',
            'reference' => 'nullable|string|max:255',
            'date' => 'required|date',
            'pdf' => 'nullable|file|mimes:pdf|max:2048',
            'state' => 'nullable|string|in:current,outdated',
        ]);

        // Set default state if not provided
        if (!isset($validatedData['state'])) {
            $validatedData['state'] = 'current';
        }

        // If this is a current decision, archive all others
        if ($validatedData['state'] === 'current') {
            AnnualDecision::where('state', 'current')->update(['state' => 'outdated']);
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
        $this->activityLogger->log(
            'updated',
            "mis à jour de la décision{$decision->number}/{$decision->year}/{$decision->reference}",
            $decision
        );
        if ($request->wantsJson()) {
            return response()->json([
                'message' => $id ? 'Décision mise à jour avec succès' : 'Décision créée avec succès',
                'ok' => true,
                'redirect' => route('decisions.show')
            ]);
        }

        return redirect()->route('decisions.show')
            ->with('success', $id ? 'Décision mise à jour avec succès' : 'Décision créée avec succès');

    }





    /**
     * Remove the specified annual decision from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $decision = AnnualDecision::findOrFail($id);

        // Delete associated PDF if exists
        if ($decision->pdf) {
            Storage::disk('public')->delete($decision->pdf);
        }

        $decision->delete();

        return response()->json([
            'message' => 'Décision supprimée avec succès',
            'ok' => true,
            "redirect" => route('decisions.show')
        ]);

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
            AnnualDecision::where('state', 'current')->update(['state' => 'outdated']);

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
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadPdf($id)
    {
        $decision = AnnualDecision::findOrFail($id);
        $this->activityLogger->log(
            'download',
            "Téléchargement du PDF de la décision #{$decision->number}/{$decision->year}/{$decision->reference}",
            $decision
        );
        if (!$decision->pdf) {
            return back()->with('error', 'Aucun fichier PDF n\'est associé à cette décision.');
        }

        if (!Storage::disk('public')->exists($decision->pdf)) {
            return back()->with('error', 'Le fichier PDF associé à cette décision est introuvable.');
        }

        $fileName = "decision_{$decision->number}_{$decision->year}.pdf";

        return Storage::disk('public')->download($decision->pdf, $fileName);

    }
}