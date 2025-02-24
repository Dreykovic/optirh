<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
                $file = $request->file('file');

                $folder = 'publications';

                // Créer le répertoire si nécessaire

                $disk = 'public';
                if (!Storage::disk($disk)->exists($folder)) {
                    Storage::disk($disk)->makeDirectory($folder);
                }

                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Nom sans extension
                $extension = $file->getClientOriginalExtension(); // Extension
                $fileName = $originalName;

                // Gestion des conflits de noms
                $counter = 1;

                $extension = strtolower($extension);
                while (Storage::disk($disk)->exists("$folder/$fileName.$extension")) {
                    $fileName = "{$originalName}_{$counter}";
                    ++$counter;
                }

                // Enregistrer le fichier avec un nom unique
                $path = $file->storeAs($folder, "$fileName.$extension", $disk);
                $publication->file = $path;
            }

            $publication->save();

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
