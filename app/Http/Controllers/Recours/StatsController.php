<?php

namespace App\Http\Controllers\Recours;

use App\Http\Controllers\Controller;
use App\Models\Recours\Appeal;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     // Vérification des dates envoyées par l'utilisateur
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');

    //     // Base de requête : Toutes les années si pas de filtre
    //     $query = Appeal::join('decisions', 'appeals.decision_id', '=', 'decisions.id')
    //         ->selectRaw("
    //             CASE 
    //                 WHEN decisions.decision IN ('FORCLUSION', 'IRRECEVABLE', 'HORS COMPETENCE') THEN 'REJETE'
    //                 ELSE decisions.decision
    //             END as decision_group,
    //             COUNT(*) as count
    //         ")
    //         ->groupBy('decision_group');

    //     if ($startDate && $endDate) {
    //         $query->whereBetween('deposit_date', [$startDate, $endDate]);
    //     }

    //     $decisions = $query->pluck('count', 'decision_group');
    //     $chart = (new LarapexChart())
    //         ->setTitle('Nombre de recours par décision')
    //         ->setType('area') // line area
    //         ->setLabels($decisions->keys()->toArray()) // Catégories sur l'axe X
    //         ->setDataset([
    //             [
    //                 'name' => 'Nombre de recours',
    //                 'data' => $decisions->values()->toArray(),
    //             ],
    //         ]);

    //     dump($chart);
    //     return view('modules.recours.pages.stats', compact('chart', 'startDate', 'endDate'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('recours::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    }
}
