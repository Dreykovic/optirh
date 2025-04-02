<?php

namespace App\Http\Controllers\Recours;

use App\Http\Controllers\Controller;
use App\Models\Recours\Appeal;
use App\Models\Recours\Applicant;
use App\Models\Recours\Dac;
use App\Models\Recours\Decision;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RecoursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recours_count = Appeal::count();

        return view('modules.recours.pages.liste', compact('recours_count'));
    }

    public function appeal_loading(Request $request)
    {
        try {
            $search = $request->input('search', '');
            $limit = $request->input('limit', 5);
            $page = $request->input('page', 1);
            $status = $request->input('status', null);
            $startDate = $request->input('startDate', null);
            $endDate = $request->input('endDate', null);
            $statuses = $request->input('statusOptions', '');

            //  \Log::info('Filtre dates :', ['start' => $startDate,'end' => $endDate]);
            // tail -f storage/logs/laravel.log

            // Construire la requête
            $query = DB::table('appeals')
            ->join('dacs', 'appeals.dac_id', '=', 'dacs.id')
            ->leftJoin('applicants', 'appeals.applicant_id', '=', 'applicants.id')
            ->leftJoin('decisions as decided', 'appeals.decided_id', '=', 'decided.id')
            ->leftJoin('decisions as suspended', 'appeals.suspended_id', '=', 'suspended.id')
            ->select(
                'appeals.*',
                'dacs.reference',
                'applicants.name as applicant',
                'decided.decision as decided',
                'suspended.decision as suspended'
            )
            ->orderBy('appeals.created_at', 'desc');
        

            // Filtrer entre deux dates
            if ($startDate && $endDate) {
                $query->whereBetween('appeals.deposit_date', [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where('appeals.deposit_date', '>=', $startDate);
            } elseif ($endDate) {
                $query->where('appeals.deposit_date', '<=', $endDate);
            }

            if (!empty($statuses)) {
                $statuses = explode(',', $statuses); // Transformation en tableau
                // \Log::info('Statuts transformés en tableau : ', $statuses);
            }

            if (!empty($statuses) && is_array($statuses)) {
                $query->where(function ($q) use ($statuses) {
                    $q->whereIn(DB::raw('appeals.analyse_status::TEXT'), $statuses)
                        ->orWhereIn(DB::raw('decisions.decision::TEXT'), $statuses);
                });
            }

            // Recherche textuelle
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(appeals.object) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(dacs.reference) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(decisions.decision) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(appeals.analyse_status) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(applicants.name) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw("TO_CHAR(appeals.deposit_date, 'YYYY-MM-DD') LIKE ?", ['%' . $search . '%']);
                });
            }

            // Ajouter la pagination
            $appeals = $query->paginate($limit);

            return response()->json($appeals);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors du chargement des données',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function create()
    {
        $dacs = Dac::orderBy('created_at', 'desc')->get();
        $applicants = Applicant::orderBy('created_at', 'desc')->get();

        // dump($applicants);
        return view('modules.recours.pages.new', compact('dacs', 'applicants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'dac_id' => 'required|exists:dacs,id',
                'applicant_id' => 'required|exists:applicants,id',
                'type' => 'required|string|in:DAC,PROCESS,RESULTS,OTHERS',
                'date_depot' => 'required|date',
                'object' => 'required|string|max:500',
            ]);

            list($date, $time) = explode('T', $validatedData['date_depot']);

            // Create the applicant
            Appeal::create([
                'dac_id' => $validatedData['dac_id'],
                'applicant_id' => $validatedData['applicant_id'],
                'type' => $validatedData['type'],
                'deposit_hour' => $time,
                'deposit_date' => $date,
                'object' => $validatedData['object'],
                // 'created_by' =>  Auth::user()->employee->id ?? null
            ]);

            return response()->json(['message' => 'Recours créé avec succès.', 'ok' => true]);
        } catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $appeal = Appeal::find($id);

        return view('modules.recours.pages.show', compact('appeal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('recours::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nif' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'phone_number' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'type' => 'required|string|in:DAC,PROCESS,RESULTS,OTHERS',
                'date_depot' => 'required|date',
                'dac_object' => 'required|string|max:500',
                'ac' => 'required|string|max:255',
                'reference' => 'required|string|max:255',
                'appeal_object' => 'required|string|max:500',
                'decision' => 'nullable|string|max:255',
                'notif_date' => 'nullable|date',
                'message_date' => 'nullable|date',
                'response_date' => 'nullable|date',
                'publish_date' => 'nullable|date',

            ]);

            list($date, $time) = explode('T', $validatedData['date_depot']);
            $appeal = Appeal::find($id);
            $dac = Dac::find($appeal->dac->id);
            $applicant = Applicant::find($appeal->applicant->id);

            $appeal->update([
                'type' => $validatedData['type'],
                'deposit_hour' => $time,
                'deposit_date' => $date,
                'object' => $validatedData['appeal_object'],
                'notif_date' => $validatedData['notif_date'],
                'message_date' => $validatedData['message_date'],
                'response_date' => $validatedData['response_date'],
                'publish_date' => $validatedData['publish_date'],
            ]);
            $dac->update([
                'reference' => $validatedData['reference'],
                'dac_object' => $validatedData['dac_object'],
                'ac' => $validatedData['ac'],
            ]);
            $applicant->update([
                'nif' => $validatedData['nif'],
                'name' => $validatedData['name'],
                'address' => $validatedData['address'],
                'phone_number' => $validatedData['phone_number'],
                // 'created_by' =>  Auth::user()->employee->id ?? null
            ]);
            if ($request->input('decision')) {
                if ($appeal->decision && $appeal->decision->decision == 'EN COURS') {
                    $appeal->decision->update([
                        'decision' => $validatedData['decision'],
                    ]);
                }
            }

            return response()->json(['message' => 'Recours MàJ avec succès.', 'ok' => true]);
        } catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $appeal = Appeal::find($id);
            $appeal->delete();

            // return response()->json(['message' => 'Appeal supprimé avec succès.', 'ok' => true]);
            return response()->json(['message' => 'Recours supprimé avec succès.', 'ok' => true], 200);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
    }

   
public function rejected(Request $request, $id)
{
    try {
        $appeal = Appeal::findOrFail($id);

        $decisionData = [
            'decision' => $request->input('decision'),
            'rejected_ref' => $request->input('rejected_ref'),
            'date' => now(),
        ];

        $disk = 'public';
        $folder = "decisions";

        // Vérifier si un fichier a été reçu
        if (!$request->hasFile('rejected_file')) {
            Log::error('Aucun fichier reçu pour le recours ID: ' . $id);
            return response()->json(['ok' => false, 'message' => 'Aucun fichier reçu'], 400);
        }

        $file = $request->file('rejected_file');

        // Vérifier si le fichier est valide
        if (!$file->isValid()) {
            Log::error('Fichier invalide pour le recours ID: ' . $id);
            return response()->json(['ok' => false, 'message' => 'Le fichier est invalide'], 400);
        }

        // Vérifier et créer le dossier si nécessaire
        if (!Storage::disk($disk)->exists($folder)) {
            Storage::disk($disk)->makeDirectory($folder);
            Log::info("Dossier $folder créé.");
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension());
        $fileName = $originalName;
        $counter = 1;

        // Gérer les conflits de noms de fichiers
        while (Storage::disk($disk)->exists("$folder/$fileName.$extension")) {
            $fileName = "{$originalName}_{$counter}";
            $counter++;
        }

        // Enregistrer le fichier
        $path = $file->storeAs($folder, "$fileName.$extension", $disk);

        // Vérifier si le fichier a bien été stocké
        if (!Storage::disk($disk)->exists($path)) {
            Log::error("Échec de l'enregistrement du fichier pour le recours ID: " . $id);
            return response()->json(['ok' => false, 'message' => 'Erreur lors de l\'enregistrement du fichier'], 500);
        }

        // Stocker le chemin dans la décision
        $decisionData['rejected_file'] = "storage/$path";

        // Enregistrer la décision
        $decision = Decision::create($decisionData);

        // Mettre à jour l'état du recours
        $appeal->decided_id = $decision->id;
        $appeal->analyse_status = 'IRRECEVABLE';
        $appeal->save();

        Log::info("Recours ID $id marqué comme irrecevable avec fichier: $path");

        return response()->json([
            'message' => 'Recours irrecevable avec succès.',
            'rejected_file' => "storage/$path",
            'ok' => true
        ], 200);

    } catch (\Throwable $th) {
        Log::error("Erreur lors du rejet du recours ID $id: " . $th->getMessage());
        return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
    }
}


//
public function accepted(Request $request, $id)
{
    Log::info('Requête reçue:', $request->all());

    try {
        $appeal = Appeal::findOrFail($id);

        $decisionData = [
            'decision' => 'SUSPENDU',
            'suspended_ref' => $request->input('suspended_ref'),
            'date' => now(),
        ];

        $disk = 'public';
        $folder = "decisions";

        // Vérifier si un fichier a été reçu
        if (!$request->hasFile('suspended_file')) {
            Log::error('Aucun fichier reçu pour le recours ID: ' . $id);
            return response()->json(['ok' => false, 'message' => 'Aucun fichier reçu'], 400);
        }

        $file = $request->file('suspended_file');

        // Vérifier si le fichier est valide
        if (!$file->isValid()) {
            Log::error('Fichier invalide pour le recours ID: ' . $id);
            return response()->json(['ok' => false, 'message' => 'Le fichier est invalide'], 400);
        }

        // Vérifier et créer le dossier si nécessaire
        if (!Storage::disk($disk)->exists($folder)) {
            Storage::disk($disk)->makeDirectory($folder);
            Log::info("Dossier $folder créé.");
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension());
        $fileName = $originalName;
        $counter = 1;

        // Gérer les conflits de noms de fichiers
        while (Storage::disk($disk)->exists("$folder/$fileName.$extension")) {
            $fileName = "{$originalName}_{$counter}";
            $counter++;
        }

        // Enregistrer le fichier
        $path = $file->storeAs($folder, "$fileName.$extension", $disk);

        // Vérifier si le fichier a bien été stocké
        if (!Storage::disk($disk)->exists($path)) {
            Log::error("Échec de l'enregistrement du fichier pour le recours ID: " . $id);
            return response()->json(['ok' => false, 'message' => 'Erreur lors de l\'enregistrement du fichier'], 500);
        }

        // Stocker le chemin dans la décision
        $decisionData['suspended_file'] = "storage/$path";

        // Enregistrer la décision
        $decision = Decision::create($decisionData);

        // Mettre à jour l'état du recours
        $appeal->suspended_id = $decision->id;
        $appeal->analyse_status = 'RECEVABLE';
        $appeal->save();

        Log::info("Recours ID $id marqué comme irrecevable avec fichier: $path");

        return response()->json([
            'message' => 'Recours recevable avec succès.',
            'suspended_file' => "storage/$path",
            'ok' => true
        ], 200);

    } catch (\Throwable $th) {
        Log::error("Erreur lors du rejet du recours ID $id: " . $th->getMessage());
        return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
    }
}


public function crd(Request $request, $id)
{
    try {
        $appeal = Appeal::findOrFail($id);

        $decisionData = [
            'decision' => $request->input('decision'), // Récupère la raison du rejet
            'decided_ref' => $request->input('decided_ref'), // Récupère le numéro de décision
            'date' => now(),
        ];

        $disk = 'public';
        $folder = "decisions";

        // Vérifier si un fichier a été reçu
        if ($request->hasFile('decided_file')) {
            $file = $request->file('decided_file');

            // Vérifier si le fichier est valide
            if (!$file->isValid()) {
                Log::error('Fichier invalide pour le recours ID: ' . $id);
                return response()->json(['ok' => false, 'message' => 'Le fichier est invalide'], 400);
            }

            // Vérifier et créer le dossier si nécessaire
            if (!Storage::disk($disk)->exists($folder)) {
                Storage::disk($disk)->makeDirectory($folder);
                Log::info("Dossier $folder créé.");
            }

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = strtolower($file->getClientOriginalExtension());
            $fileName = $originalName;
            $counter = 1;

            // Gérer les conflits de noms de fichiers
            while (Storage::disk($disk)->exists("$folder/$fileName.$extension")) {
                $fileName = "{$originalName}_{$counter}";
                $counter++;
            }

            // Enregistrer le fichier
            $path = $file->storeAs($folder, "$fileName.$extension", $disk);

            // Vérifier si le fichier a bien été stocké
            if (!Storage::disk($disk)->exists($path)) {
                Log::error("Échec de l'enregistrement du fichier pour le recours ID: " . $id);
                return response()->json(['ok' => false, 'message' => 'Erreur lors de l\'enregistrement du fichier'], 500);
            }

            // Stocker le chemin dans la décision
            $decisionData['decided_file'] = "storage/$path";
        }

        // Enregistrer la décision
        $decision = Decision::create($decisionData);

        // Mettre à jour l'état du recours
        $appeal->decided_id = $decision->id;
        $appeal->save();

        Log::info("Recours ID $id marqué comme irrecevable avec fichier: $path");

        return response()->json([
            'message' => 'Recours irrecevable avec succès.',
            'decided_file' => isset($path) ? "storage/$path" : null,
            'ok' => true
        ], 200);

    } catch (\Throwable $th) {
        Log::error("Erreur lors du traitement du recours ID $id: " . $th->getMessage());
        return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
    }
}





}
