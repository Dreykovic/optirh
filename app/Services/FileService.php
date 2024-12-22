<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    public function storeFile($employeeId, $file)
    {
        $folder = "employees/{$employeeId}";
        if (!Storage::exists($folder)) {
            Storage::makeDirectory($folder);
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileName = $originalName;
        // Gestion des conflits de noms
        $counter = 1;
        while (Storage::exists("$folder/$fileName.$extension")) {
            $fileName = $originalName . "_{$counter}";
            $counter++;
        }

        $path = $file->storeAs($folder, "$fileName.$extension",'public');

        return File::create([
            'employee_id' => $employeeId,
            'name' => $originalName,
            'path' => $path,
            'url' => Storage::url($path),
            'mime_type' => $file->getClientMimeType(),
            'status' => 'ACTIVATED',
        ]);
    }

    public function renameFile(File $file, $newName)
    {
        $folder = pathinfo($file->path, PATHINFO_DIRNAME);
        $extension = pathinfo($file->path, PATHINFO_EXTENSION);

        $newPath = "$folder/$newName.$extension";
        if (Storage::exists($newPath)) {
            throw new \Exception('Un fichier avec le même nom existe déjà.');
        }

        Storage::move($file->path, $newPath);

        $file->update([
            'name' => $newName,
            'path' => $newPath,
            'url' => Storage::url($newPath),
        ]);

        return $file;
    }

    public function deleteFile(File $file)
    {
        if (Storage::exists($file->path)) {
            Storage::delete($file->path);
        }
        $file->delete();
    }

    public function downloadFile(File $file)
    {
        if (!Storage::exists($file->path)) {
            throw new \Exception('Fichier introuvable.');
        }
        return Storage::download($file->path);
    }

    public function getFileUrl(File $file)
    {
        if (!Storage::exists($file->path)) {
            throw new \Exception('Fichier introuvable.');
        }
        return Storage::url($file->path);
    }

}
