<?php

namespace App\Http\Controllers;
use Modules\Recours\App\Models\Appeal; 
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home()
    {
        try {
            return view('pages.admin.dashbord.index');
        } catch (\Throwable $th) {
            // dd($th->getMessage());

            return view('errors.404');
        }
    }

    public function recours_home()
    {
        try {
            $on_going = DB::table('appeals')
                ->join('dacs', 'appeals.dac_id', '=', 'dacs.id')
                ->leftJoin('applicants', 'appeals.applicant_id', '=', 'applicants.id')
                ->leftJoin('decisions', 'appeals.decision_id', '=', 'decisions.id')
                ->where('appeals.analyse_status', 'EN_COURS')
                ->orWhere(function ($query) {
                    $query->whereNotNull('decisions.decision')
                        ->where('decisions.decision', 'EN COURS');
                })
                ->select('appeals.*', 'dacs.reference', 'applicants.name as applicant', 'decisions.decision')
                ->orderByDesc('appeals.day_count')
                ->get();

    
            $rejected_count = Appeal::where('analyse_status', 'REJETE')->count();
            $accepted_count = Appeal::where('analyse_status', 'ACCEPTE')->count();
            $on_going_count = $on_going->count();    
            return view('pages.admin.dashbord.recours.index', compact('rejected_count', 'accepted_count','on_going','on_going_count'));
        } catch (\Throwable $th) {
            \Log::error('Erreur dans recours_home: ' . $th->getMessage());
            return response()->view('errors.404', [], 404);
        }
    }
    

}
