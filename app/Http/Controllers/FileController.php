<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function upload(Request $request, $employeeId)
    {
        try {
            $request->validate([
                'files.*' => 'required|file|mimes:pdf,png,jpeg,jpg|max:2048', // Limite de 2 Mo par fichier
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
        }  catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
       
    }

    public function rename(Request $request, $fileId)
    {
        try {
            $request->validate([
                'new_name' => 'required|string',
            ]);
    
            $file = File::findOrFail($fileId);
    
            $updatedFile = $this->fileService->renameFile($file, $request->new_name);
    
            return response()->json(['ok' => true,'message' => 'Fichier renommé avec succès.', 'file' => $updatedFile]);
        }  catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
       
    }

    public function delete($fileId)
    {
        try {
            $file = File::findOrFail($fileId);
        $this->fileService->deleteFile($file);

        return response()->json(['message' => 'Fichier supprimé avec succès.','ok' => true]);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
        
    }

    public function download($fileId)
    {
        try {
            $file = File::findOrFail($fileId);
            return $this->fileService->downloadFile($file);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
       
    }
}
