<?php

namespace Modules\Recours\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Recours\App\Models\Dac; 
use Modules\Recours\App\Models\Applicant; 
use Modules\Recours\App\Models\Appeal; 
use Illuminate\Support\Facades\DB;

class RecoursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('recours::pages.recours.liste');
    }

    // public function appeal_loading(Request $request)
    // {
    //     try {
    //         $search = $request->input('search', '');     
    //         $limit = $request->input('limit', 5);   
    //         $page = $request->input('page', 1);  
    //         $status = $request->input('status', null);
            
    //         // Construire la requête
    //         $query = DB::table('appeals')
    //         ->join('dacs', 'appeals.dac_id', '=', 'dacs.id')
    //         // ->join('decisions', 'appeals.decision_id', '=', 'decisions.id')
    //         ->leftJoin('applicants', 'appeals.applicant_id', '=', 'applicants.id')
    //         ->select(
    //             'appeals.*',
    //             'dacs.reference',
    //             'applicants.name as applicant',
    //         )
    //         ->orderBy('appeals.deposit_date', 'desc');
            
    //         // Filtrer par statut si fourni
    //         if (!is_null($status)) {
    //             $query->where('appeals.status', '=', $status);
    //         }
        
    //         // Recherche textuelle
    //         if ($search) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->whereRaw('LOWER(appeals.object) LIKE ?', ['%' . strtolower($search) . '%'])
    //                   ->orWhereRaw('LOWER(dacs.reference) LIKE ?', ['%' . strtolower($search) . '%'])
    //                   ->orWhereRaw('LOWER(appeals.analyse_status) LIKE ?', ['%' . strtolower($search) . '%'])
    //                   ->orWhereRaw('LOWER(applicants.name) LIKE ?', ['%' . strtolower($search) . '%'])
    //                   ->orWhereRaw("TO_CHAR(appeals.deposit_date, 'YYYY-MM-DD') LIKE ?", ['%' . $search . '%']);
    //                 });
    //         }
        
    //         // Ajouter la pagination
    //         $appeals = $query->paginate($limit);
        
    //         // Retourner la réponse JSON
    //         return response()->json($appeals);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'Erreur lors du chargement des données',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
        
    // }
    public function appeal_loading(Request $request)
    {
        try {
            $search = $request->input('search', '');
            $limit = $request->input('limit', 5);
            $page = $request->input('page', 1);
            $status = $request->input('status', null);
            $startDate = $request->input('startDate', null);
            $endDate = $request->input('endDate', null);
            $etudeStatus = $request->input('etudeStatus', []);
            $decisionStatus = $request->input('decisionStatus', []);

            // Construire la requête
            $query = DB::table('appeals')
                ->join('dacs', 'appeals.dac_id', '=', 'dacs.id')
                ->leftJoin('applicants', 'appeals.applicant_id', '=', 'applicants.id')
                ->select('appeals.*', 'dacs.reference', 'applicants.name as applicant')
                ->orderBy('appeals.deposit_date', 'desc');

            // Filtrer par statut si fourni
            if (!is_null($status)) {
                $query->where('appeals.status', '=', $status);
            }

            // Filtrer entre deux dates
            if ($startDate && $endDate) {
                $query->whereBetween('appeals.deposit_date', [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where('appeals.deposit_date', '>=', $startDate);
            } elseif ($endDate) {
                $query->where('appeals.deposit_date', '<=', $endDate);
            }
            // Filtrer par étude si fourni
            if (!empty($etudeStatus)) {
                $query->whereIn('appeals.analyse_status', $etudeStatus);
            }

            // Filtrer par décision si fourni
            if (!empty($decisionStatus)) {
                $query->whereIn('appeals.decision_status', $decisionStatus);
            }

            // Recherche textuelle
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(appeals.object) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(dacs.reference) LIKE ?', ['%' . strtolower($search) . '%'])
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
                'message' => $e->getMessage()
            ], 500);
        }
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dacs = Dac::orderBy('created_at', 'desc')->get();
        $applicants = Applicant::orderBy('created_at', 'desc')->get();
// dump($applicants);
        return view('recours::pages.recours.new', compact('dacs', 'applicants'));
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
                'object' => 'required|string|max:500'
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
        return view('recours::show');
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
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
