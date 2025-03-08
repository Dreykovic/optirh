<?php

namespace App\Http\Controllers;

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
            return view('pages.admin.dashbord.recours.index');
        } catch (\Throwable $th) {
            // dd($th->getMessage());

            return view('errors.404');
        }
    }
}
