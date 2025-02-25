<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Services\PublicationFileService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PublicationController extends Controller
{
    protected $fileService;

    public function __construct()
    {
        $this->fileService = new PublicationFileService();
    }

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
            $query->with(['author', 'files']);

            $publications = $query->orderBy('created_at', 'ASC')->get();

            return view('pages.admin.publications.config.index', compact('publications', 'status'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
            abort(500);
        }
    }

    public function updateStatus($status, $id)
    {
        try {
            // Liste des statuss valides
            $validStatus = ['archived', 'pending', 'published'];

            // Vérification de la validité du status
            if (!in_array($status, $validStatus)) {
                return redirect()->back()->with('error', 'status invalide');
            }
            $publication = Publication::findOrFail($id);
            $publication->status = $status;

            $publication->save();

            session()->flash('success', 'Status mis à jour avec succès');

            return response()->json(['ok' => true, 'message' => 'Status mis à jour avec succès']);
        } catch (\Throwable $th) {
            // return response()->json(['ok' => false,  'message' => 'Une erreur s\'est produite. Veuillez réessayer.'], 500);

            return response()->json(['ok' => false,  'message' => $th->getMessage()], 500);
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
                'files.*' => 'nullable|mimes:jpg,jpeg,png,gif,pdf|max:2048',  // Les fichiers peuvent être vides
            ]);

            $publication = new Publication();
            $publication->title = $request->input('title');
            $publication->content = $request->input('content');
            $publication->author_id = auth()->id();

            $publication->save();

            foreach ($request->file('files') as $file) {
                $this->fileService->storeFile($publication->id, $file);
            }

            return response()->json(['message' => 'Note créée avec succès', 'ok' => true]);
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

    public function preview($id)
    {
        try {
            $publication = Publication::findOrFail($id);

            if (!$publication->file || !Storage::disk('public')->exists($publication->file)) {
                return response()->json(['ok' => false, 'message' => 'Fichier non trouvé.'], 404);
            }

            return response()->download(Storage::disk('public')->path($publication->file));
        } catch (ModelNotFoundException $e) {
            return response()->json(['ok' => false, 'message' => 'Publication non trouvée.'], 404);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => 'Une erreur s\'est produite. Veuillez réessayer.'], 500);
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
    public function destroy(string $id)
    {
        try {
            $publication = Publication::findOrFail($id);
            // Supprimer le fichier du disque principal
            if (Storage::disk('public')->exists($publication->file)) {
                Storage::disk('public')->delete($publication->file);
            }

            // Supprimer également le fichier dans 'public/storage', si nécessaire
            $publicPath = public_path('storage/'.str_replace('public/', '', $publication->file));
            if (file_exists($publicPath)) {
                unlink($publicPath); // Supprime le fichier du chemin public
            }

            // Supprimer l'entrée de la base de données
            $publication->delete();

            return response()->json(['ok' => true, 'message' => 'la note a été supprimé avec succès.']);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'message' => 'Une erreur s\'est produite. Veuillez réessayer.']);
        }
    }
}
