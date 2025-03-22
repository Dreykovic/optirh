<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\PublicationFile;
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
     * @return \Illuminate\View\View
     */
    public function index($status = 'all')
    {
        try {
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

            return view('pages.admin.publications.config.index', compact('publications', 'status'));
        } catch (\Throwable $th) {
            Log::error('Erreur lors du chargement des publications', [
                'status' => $status,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            $this->activityLogger->log(
                'error',
                "Erreur lors du chargement des publications - Statut: {$status}"
            );

            return back()->with('error', 'Une erreur s\'est produite lors du chargement des publications. Veuillez réessayer.');
        }
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
        try {
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
        } catch (ModelNotFoundException $e) {
            Log::warning('Publication non trouvée lors de la mise à jour du statut', [
                'publication_id' => $id,
                'status' => $status
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Publication non trouvée'
            ], 404);
        } catch (\Throwable $th) {
            Log::error('Erreur lors de la mise à jour du statut d\'une publication', [
                'publication_id' => $id,
                'status' => $status,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            $this->activityLogger->log(
                'error',
                "Échec de mise à jour du statut de la publication #{$id}"
            );

            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Enregistrer une nouvelle publication
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
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
        } catch (ValidationException $e) {
            Log::warning('Erreur de validation lors de la création d\'une publication', [
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            Log::error('Erreur lors de la création d\'une publication', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'inputs' => $request->except(['files']),
            ]);

            $this->activityLogger->log(
                'error',
                "Échec de création d'une publication"
            );

            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite lors de la création de la publication.'
            ], 500);
        }
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
        try {
            $file = PublicationFile::findOrFail($id);

            $this->activityLogger->log(
                'download',
                "Téléchargement du fichier #{$id} de la publication #{$file->publication_id}",
                $file
            );

            return response()->download($this->fileService->getFile($file));
        } catch (ModelNotFoundException $e) {
            Log::warning('Fichier de publication non trouvé lors de la prévisualisation', [
                'file_id' => $id
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Fichier non trouvé.'
            ], 404);
        } catch (\Throwable $th) {
            Log::error('Erreur lors de la prévisualisation d\'un fichier de publication', [
                'file_id' => $id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            $this->activityLogger->log(
                'error',
                "Échec de téléchargement du fichier #{$id}"
            );

            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite lors de la prévisualisation du fichier.'
            ], 500);
        }
    }

    /**
     * Supprimer une publication
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        try {
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
        } catch (ModelNotFoundException $e) {
            Log::warning('Publication non trouvée lors de la suppression', [
                'publication_id' => $id
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Publication non trouvée.'
            ], 404);
        } catch (\Throwable $th) {
            Log::error('Erreur lors de la suppression d\'une publication', [
                'publication_id' => $id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            $this->activityLogger->log(
                'error',
                "Échec de suppression de la publication #{$id}"
            );

            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite lors de la suppression de la publication.'
            ], 500);
        }
    }
}
