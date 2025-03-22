<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\PublicationFile;
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
        $this->middleware(['permission:voir-une-publication|écrire-une-publication|créer-une-publication|configurer-une-publication|voir-un-tout'], ['only' => ['index']]);
        $this->middleware(['permission:créer-une-publication|créer-un-tout'], ['only' => ['store']]);
        $this->middleware(['permission:écrire-une-publication|écrire-un-tout'], ['only' => ['destroy', 'updateStatus']]);
    }

    /**
     * Display a listing of the resource.
     */ /**
     * Display a listing of the resource.
     */
    public function index($status = 'all')
    {

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

    }

    public function updateStatus($status, $id)
    {

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

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'sometimes',
            // 'files.*' => 'nullable|mimes:jpg,jpeg,png,gif,pdf|max:10240',  // Les fichiers peuvent être vides
            'files.*' => 'nullable|mimes:jpg,jpeg,png,gif,pdf',  // Les fichiers peuvent être vides
        ]);

        $publication = new Publication();
        $publication->title = $request->input('title');
        $publication->content = $request->input('content');
        $publication->author_id = auth()->id();

        $publication->save();
        $files = $request->file('files');
        if ($files) {
            foreach ($files as $file) {
                $this->fileService->storeFile($publication->id, $file);
            }
        }

        return response()->json(['message' => 'Note créée avec succès', 'ok' => true]);

    }

    public function preview($id)
    {

        $file = PublicationFile::findOrFail($id);

        return response()->download($this->fileService->getFile($file));

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

        $publication = Publication::findOrFail($id);

        if ($publication->files->isNotEmpty()) {
            foreach ($publication->files as $file) {
                $this->fileService->destroyFile($file);
            }
        }
        // Supprimer l'entrée de la base de données
        $publication->delete();

        return response()->json(['ok' => true, 'message' => 'la note a été supprimé avec succès.']);

    }
}
