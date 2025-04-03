<?php

namespace App\Http\Controllers\OptiHr;

use App\Http\Controllers\Controller;
use App\Models\OptiHr\File;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FileController extends Controller
{
    protected $evolutions = ['ON_GOING', 'ENDED', 'CANCEL', 'SUSPENDED', 'RESIGNED', 'DISMISSED'];
    protected $status = ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'];
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function uploadFiles(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:pdf|max:2048', // Limite de 2 Mo par fichier
        ]);
        $files = $request->file('files');

        foreach ($files as $employeeId => $file) {
            if ($file) {
                try {
                    // Utilisation de votre service de stockage
                    app(FileService::class)->storeFile($employeeId, $file);
                } catch (\Exception $e) {
                    // Gérer les erreurs pour chaque fichier
                    return back()->with('error', "Erreur lors du téléchargement pour l'employé ID: {$employeeId}");
                }
            }
        }

        return back()->with('success', 'Documents téléchargés avec succès.');
    }

    public function upload(Request $request, $employeeId)
    {

        $request->validate([
            'files.*' => 'required|file|mimes:pdf|max:2048', // Limite de 2 Mo par fichier
        ]);

        $uploadedFiles = [];
        foreach ($request->file('files') as $file) {
            $uploadedFiles[] = $this->fileService->storeFile($employeeId, $file);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Fichiers sauvegardés avec succès.',
            'files' => $uploadedFiles,
        ]);

    }

    public function rename(Request $request, $id)
    {

        $request->validate([
            'new_name' => 'required|string',
        ]);

        $file = File::findOrFail($id);

        $updatedFile = $this->fileService->renameFile($file, $request->new_name);

        return response()->json(['ok' => true, 'message' => 'Fichier renommé avec succès.', 'file' => $updatedFile]);

    }

    public function delete($fileId)
    {

        $file = File::findOrFail($fileId);
        $this->fileService->deleteFile($file);

        // return response()->json(['message' => 'Fichier supprimé avec succès.','ok' => true]);
        return redirect()->back()->with('message', 'Action réussie !');

    }

    public function download($fileId)
    {

        $file = File::findOrFail($fileId);

        return $this->fileService->downloadFile($file);

    }

    public function openFile($fileId)
    {

        $file = File::findOrFail($fileId);
        $fileUrl = $this->fileService->getFileUrl($file);

        return redirect()->away($fileUrl); // Redirection vers l'URL du fichier

    }

    public function getFiles(Request $request, $employeeId)
    {
        $search = $request->input('search', '');
        $limit = $request->input('limit', 5);
        $page = $request->input('page', 1);

        // Vérification insensible à la casse pour le nom
        // $filesQuery = File::where('employee_id', $employeeId)
        //     ->where('status','ACTIVATED')
        //     ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
        //     ->whereRaw('LOWER(display_name) LIKE ?', ['%' . strtolower($search) . '%'])
        //     ->orderBy('created_at', 'desc');
        $filesQuery = File::where('employee_id', $employeeId)
            ->where('status', $this->status[0])
            ->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($search).'%'])
                    ->orWhereRaw('LOWER(display_name) LIKE ?', ['%'.strtolower($search).'%']);
            })
            ->orderBy('created_at', 'desc');

        // Pagination avec les paramètres limit et page
        $files = $filesQuery->paginate($limit);
        foreach ($files as $file) {
            $file->icon_class = getFileIconClass($file->mime_type);
            $file->icon = getFileIcon($file->mime_type);
        }

        return response()->json($files);
    }

   
     /**
     * Upload de plusieurs bulletins de paie.
     */
    public function uploadBulletins(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|mimes:pdf|max:2048',
        ]);

        $results = [];
        foreach ($request->file('files') as $file) {
            $results[] = $this->fileService->processBulletin($file);
        }

        return response()->json($results);
    }


}
