<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */ /**
     * Display a listing of the resource.
     */
    public function index($status = 'all')
    {
        try {
            // Liste des statuss valides
            $validStatus = ['archived', 'pending', 'published'];

            // Vérification de la validité du status
            if ($status !== 'all' && !in_array($status, $validStatus)) {
                return redirect()->back()->with('error', 'status invalide');
            }

            $query = Publication::query();

            // Filtrer par status si le status n'est pas "all"
            $query->when($status !== 'all', function ($q) use ($status) {
                $q->where('status', $status);
            });

            $publications = $query->orderBy('created_at', 'DESC')->get();

            return view('pages.admin.publications.config.index', compact('publications', 'status'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
            abort(500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publication $publication)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publication $publication)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication)
    {
    }
}
