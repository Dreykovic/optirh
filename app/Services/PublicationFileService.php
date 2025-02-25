<?php

namespace App\Services;

use App\Models\PublicationFile;
use Illuminate\Support\Facades\Storage;

class PublicationFileService extends FileService
{
    public function storeFile($publicationId, $file)
    {
        $folder = "publication/{$publicationId}";

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

        // Sauvegarder les informations du fichier dans la base de données
        return PublicationFile::create([
            'publication_id' => $publicationId,
            'name' => "$fileName.$extension", // (nom + extension)
            'display_name' => "{$originalName}.{$extension}",
            'path' => $path,
            'url' => Storage::url($path), // URL publique
            'mime_type' => $file->getClientMimeType(), // Type MIME
            'status' => $this->status[0],
        ]);
    }

    public function getFile(PublicationFile $file)
    {
        if (!$file || !Storage::disk('public')->exists($file->path)) {
            throw new \Exception('Fichier introuvable.');
        }

        return Storage::disk('public')->path($file->path);
    }

    public function destroyFile(PublicationFile $file)
    {
        // Supprimer le fichier du disque principal
        if (Storage::exists($file->path)) {
            Storage::delete($file->path);
        }

        // Supprimer également le fichier dans 'public/storage', si nécessaire
        $publicPath = public_path('storage/'.str_replace('public/', '', $file->path));
        if (file_exists($publicPath)) {
            unlink($publicPath); // Supprime le fichier du chemin public
        }

        // Supprimer l'entrée de la base de données
        $file->delete();
    }
}
