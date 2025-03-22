<?php

namespace App\Http\Controllers;

use App\Models\Recours\Appeal;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class HomeController extends Controller
{
    public function home()
    {

        return view('pages.admin.dashbord.index');

    }

    public function recours_home(Request $request)
    {

        // $on_going = DB::table('appeals')
        //     ->join('dacs', 'appeals.dac_id', '=', 'dacs.id')
        //     ->leftJoin('applicants', 'appeals.applicant_id', '=', 'applicants.id')
        //     ->leftJoin('decisions', 'appeals.decision_id', '=', 'decisions.id')
        //     ->where('appeals.analyse_status', 'EN_COURS')
        //     ->orWhere(function ($query) {
        //         $query->whereNotNull('decisions.decision')
        //             ->where('decisions.decision', 'EN COURS');
        //     })
        //     ->select('appeals.*', 'dacs.reference', 'applicants.name as applicant', 'decisions.decision')
        //     ->orderByDesc('appeals.day_count')
        //     ->get();
        $on_going = Appeal::with(['dac', 'applicant', 'decision'])
            ->where('analyse_status', 'EN_COURS')
            ->orWhereHas('decision', function ($query) {
                $query->where('decision', 'EN COURS');
            })
            ->orderByDesc('day_count')
            ->get();



        $rejected_count = Appeal::where('analyse_status', 'REJETE')->count();
        $accepted_count = Appeal::where('analyse_status', 'ACCEPTE')->count();
        $on_going_count = $on_going->count();
        //
        // Vérification des dates envoyées par l'utilisateur
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Base de requête : Toutes les années si pas de filtre
        // $query = Appeal::join('decisions', 'appeals.decision_id', '=', 'decisions.id')
        //     ->selectRaw("
        //         CASE
        //             WHEN decisions.decision IN ('FORCLUSION', 'IRRECEVABLE', 'HORS COMPETENCE') THEN 'REJETE'
        //             ELSE decisions.decision
        //         END as decision_group,
        //         COUNT(*) as count
        //     ")
        //     ->groupBy('decision_group');

        // $query = Appeal::join('decisions', 'appeals.decision_id', '=', 'decisions.id')
        // ->selectRaw("
        //     decisions.decision as decision_group,
        //     COUNT(*) as count
        // ")
        // ->groupBy('decisions.decision');
        $query = Appeal::join('decisions', 'appeals.decision_id', '=', 'decisions.id')
        ->selectRaw("
                decisions.decision as decision_group,
                COUNT(*) as count
            ")
        ->where('decisions.decision', '!=', 'EN COURS') // Exclure les décisions "EN COURS"
        ->groupBy('decisions.decision');


        // Appliquer le filtre uniquement si l'utilisateur envoie des dates
        if ($startDate && $endDate) {
            $query->whereBetween('deposit_date', [$startDate, $endDate]);
        }

        $decisions = $query->pluck('count', 'decision_group');
        //dd($decisions);

        // Création du graphique

        // $chart = (new LarapexChart)
        //     ->setTitle('Nombre de recours par décision')
        //     ->setType('bar') // Forcer le type en barres
        //     ->setLabels($decisions->keys()->toArray())
        //     ->setDataset($decisions->values()->toArray());
        $chart = (new LarapexChart())
            ->setTitle('Nombre de recours par décision')
            ->setType('bar') // Type en barres
            // ->setColors(['#FFDAB9', '#FFA07A', '#FF8C00']) // Couleurs personnalisées
            ->setColors(['#FFE5B4', '#FFC87C', '#FFA500']) // Beige orangé, orange clair, orange moyen
            ->setLabels($decisions->keys()->toArray()) // Catégories sur l'axe X
            ->setDataset([
                [
                    'name' => 'Nombre de recours',
                    'data' => $decisions->values()->toArray()
                ]
            ]);



        return view('pages.admin.dashbord.recours.index', compact('rejected_count', 'accepted_count', 'on_going', 'on_going_count', 'chart', 'startDate', 'endDate'));

    }


}
