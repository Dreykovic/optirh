<?php

namespace App\Http\Controllers;

use App\Models\Recours\Appeal;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home()
    {
        return view('modules.opti-hr.pages.dashboard.index');
    }

    public function gateway()
    {
        return view('modules.gateway.index');
    }

    public function recours_home(Request $request)
    {
        
        // $on_going = Appeal::with(['dac', 'applicant', 'decision'])
        //     ->where('analyse_status', 'EN_COURS')
        //     ->orWhereHas('decision', function ($query) {
        //         $query->where('decision', 'EN COURS');
        //     })
        //     ->orderByDesc('day_count')
        //     ->get();
        $on_going = Appeal::with(['dac', 'applicant', 'decided', 'suspended'])
        ->where('analyse_status', 'EN_COURS')
        ->orWhereHas('decided', function ($query) {
            $query->where('decision', 'EN COURS');
        })
        ->orWhereHas('suspended', function ($query) {
            $query->where('decision', 'EN COURS');
        })
        ->orderByDesc('day_count')
        ->get();


        $rejected_count = Appeal::where('analyse_status', 'IRRECEVABLE')->count();
        $accepted_count = Appeal::where('analyse_status', 'RECEVABLE')->count();
        $on_going_count = $on_going->count();
        //
        // Vérification des dates envoyées par l'utilisateur
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        
      
        $query = Appeal::leftJoin('decisions as decided', 'appeals.decided_id', '=', 'decided.id')
        ->leftJoin('decisions as suspended', 'appeals.suspended_id', '=', 'suspended.id')
        ->selectRaw('
            COALESCE(decided.decision, suspended.decision) as decision_group,
            COUNT(*) as count
        ')
        ->where(function ($query) {
            $query->where('decided.decision', '!=', 'EN COURS')
                ->orWhere('suspended.decision', '!=', 'EN COURS');
        })
        ->groupBy('decision_group');


        // Appliquer le filtre uniquement si l'utilisateur envoie des dates
        if ($startDate && $endDate) {
            $query->whereBetween('deposit_date', [$startDate, $endDate]);
        }

        $decisions = $query->pluck('count', 'decision_group');
        // dd($decisions);

       
        $chart = (new LarapexChart())
            ->setTitle('Nombre de recours par décision')
            ->setType('bar') // Type en barres
            // ->setColors(['#FFDAB9', '#FFA07A', '#FF8C00']) // Couleurs personnalisées
            ->setColors(['#FFE5B4', '#FFC87C', '#FFA500']) // Beige orangé, orange clair, orange moyen
            ->setLabels($decisions->keys()->toArray()) // Catégories sur l'axe X
            ->setDataset([
                [
                    'name' => 'Nombre de recours',
                    'data' => $decisions->values()->toArray(),
                ],
            ]);

        return view('modules.recours.pages.dashboard', compact('rejected_count', 'accepted_count', 'on_going', 'on_going_count', 'chart', 'startDate', 'endDate'));
    }
}
