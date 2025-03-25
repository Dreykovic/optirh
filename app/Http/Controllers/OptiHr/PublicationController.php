<?php

namespace App\Http\Controllers\OptiHr;

use App\Http\Controllers\Controller;
use App\Models\OptiHr\Publication;
use App\Models\OptiHr\PublicationFile;
use App\Services\ActivityLogger;
use App\Services\PublicationFileService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PublicationController extends Controller
{
    /**
     * Le service de gestion des fichiers de publication
     *
     * @var PublicationFileService
     */
    protected $fileService;

    /**
     * Le service de journalisation des activités
     *
     * @var ActivityLogger
     */
    protected $activityLogger;

    /**
     * Constructeur du contrôleur
     *
     * @param ActivityLogger $activityLogger
     */
    public function __construct(ActivityLogger $activityLogger)
    {
        $this->fileService = new PublicationFileService();
        $this->activityLogger = $activityLogger;

        $this->middleware(['permission:voir-une-publication|écrire-une-publication|créer-une-publication|configurer-une-publication|voir-un-tout'], ['only' => ['index']]);
        $this->middleware(['permission:créer-une-publication|créer-un-tout'], ['only' => ['store']]);
        $this->middleware(['permission:écrire-une-publication|écrire-un-tout'], ['only' => ['destroy', 'updateStatus']]);
    }

    /**
     * Afficher la liste des publications filtrée par statut
     *
     * @param string $status
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index($status = 'all')
    {

        // Liste des statuts valides
        $validStatus = ['archived', 'pending', 'published'];

        // Vérification de la validité du statut
        if ($status !== 'all' && !in_array($status, $validStatus)) {
            $this->activityLogger->log(
                'error',
                "Tentative d'accès aux publications avec un statut invalide: {$status}"
            );

            return redirect()->back()->with('error', 'Statut invalide');
        }

        // Construction de la requête
        $publications = $this->getPublicationsByStatus($status);

        $this->activityLogger->log(
            'view',
            "Consultation de la liste des publications - Statut: {$status}"
        );

        return view('modules.opti-hr.pages.publications.config.index', compact('publications', 'status'));

    }

    /**
     * Récupère les publications filtrées par statut
     *
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getPublicationsByStatus($status)
    {
        $query = Publication::query();

        // Filtrer par statut si le statut n'est pas "all"
        $query->when($status !== 'all', function ($q) use ($status) {
            $q->where('status', $status);
        });

        // Charger les relations nécessaires
        $query->with(['author', 'files']);

        // Trier par date de création
        return $query->orderBy('created_at', 'ASC')->get();
    }

    /**
     * Mettre à jour le statut d'une publication
     *
     * @param string $status
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($status, $id)
    {

        // Liste des statuts valides
        $validStatus = ['archived', 'pending', 'published'];

        // Vérification de la validité du statut
        if (!in_array($status, $validStatus)) {
            $this->activityLogger->log(
                'error',
                "Tentative de mise à jour d'une publication avec un statut invalide: {$status}"
            );

            return response()->json([
                'ok' => false,
                'message' => 'Statut invalide'
            ], 400);
        }

        $publication = Publication::findOrFail($id);
        $oldStatus = $publication->status;

        // Mettre à jour le statut
        $publication->status = $status;
        $publication->save();

        $this->activityLogger->log(
            'updated',
            "Mise à jour du statut de la publication #{$id} - Statut: {$oldStatus} → {$status}",
            $publication
        );

        return response()->json([
            'ok' => true,
            'message' => 'Statut mis à jour avec succès'
        ]);

    }

    /**
     * Enregistrer une nouvelle publication
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'sometimes',
            'files.*' => 'nullable|mimes:jpg,jpeg,png,gif,pdf',
        ]);

        // Création de la publication
        $publication = new Publication();
        $publication->title = $validatedData['title'];
        $publication->content = $request->input('content');
        $publication->author_id = auth()->id();
        $publication->save();

        // Traitement des fichiers
        $this->processPublicationFiles($request, $publication);

        $this->activityLogger->log(
            'created',
            "Création d'une nouvelle publication: {$publication->title}",
            $publication
        );

        return response()->json([
            'message' => 'Note créée avec succès',
            'ok' => true
        ]);

    }

    /**
     * Traiter les fichiers attachés à une publication
     *
     * @param Request $request
     * @param Publication $publication
     * @return void
     */
    private function processPublicationFiles(Request $request, Publication $publication)
    {
        $files = $request->file('files');

        if ($files) {
            foreach ($files as $file) {
                $storedFile = $this->fileService->storeFile($publication->id, $file);

                $this->activityLogger->log(
                    'uploaded',
                    "Ajout d'un fichier à la publication #{$publication->id}: {$file->getClientOriginalName()}",
                    $storedFile
                );
            }
        }
    }

    /**
     * Prévisualiser un fichier de publication
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
 public function preview($id)
{
    $file = PublicationFile::findOrFail($id);

    $this->activityLogger->log(
        'preview',
        "Prévisualisation du fichier #{$id} de la publication #{$file->publication_id}",
        $file
    );

    // Get the file path
    $filePath = $this->fileService->getFile($file);
    
    // Get file mime type
    $mimeType = mime_content_type($filePath);
    
    // For PDFs, images, and text files - display in browser
    if (in_array($mimeType, [
        'application/pdf', 
        'image/jpeg', 
        'image/png', 
        'image/gif', 
        'text/plain',
        'text/html'
    ])) {
        return response()->file($filePath);
    }
    
    // For other file types that can't be previewed, fall back to download
    return response()->download($filePath);
}

    /**
     * Supprimer une publication
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {

        $publication = Publication::findOrFail($id);
        $publicationTitle = $publication->title;

        // Supprimer les fichiers associés
        if ($publication->files->isNotEmpty()) {
            foreach ($publication->files as $file) {
                $this->activityLogger->log(
                    'deleted',
                    "Suppression du fichier #{$file->id} associé à la publication #{$id}",
                    $file
                );

                $this->fileService->destroyFile($file);
            }
        }

        // Supprimer la publication
        $publication->delete();

        $this->activityLogger->log(
            'deleted',
            "Suppression de la publication #{$id}: {$publicationTitle}"
        );

        return response()->json([
            'ok' => true,
            'message' => 'La note a été supprimée avec succès.'
        ]);

    }
}
