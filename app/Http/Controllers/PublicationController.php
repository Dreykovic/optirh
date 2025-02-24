<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'sometimes',
                'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'status' => 'required|in:archived,pending,published',
            ]);

            $publication = new Publication();
            $publication->title = $request->input('title');
            $publication->content = $request->input('content');
            $publication->status = $request->input('status');
            $publication->author_id = auth()->id();

            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('publications');
                $publication->file_path = $filePath;
            }

            $publication->save();

            // Redirection avec message de succès
            return response()->json(['message' => 'Note créée avec succès', 'ok' => true]);
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation
            // return response()->json(['ok' => false, 'errors' => $e->errors(), 'message' => 'Données invalides. Veuillez vérifier votre saisie.']);
            return response()->json(['ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Throwable $th) {
            // return response()->json(['ok' => false,  'message' => 'Une erreur s\'est produite. Veuillez réessayer.'], 500);

            return response()->json(['ok' => false,  'message' => $th->getMessage()], 500);
        }
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
