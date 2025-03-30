<?php

namespace App\Services;

use App\Models\OptiHr\File;
use App\Models\OptiHr\Employee;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
class FileService
{
    protected $evolutions = ['ON_GOING', 'ENDED', 'CANCEL', 'SUSPENDED', 'RESIGNED', 'DISMISSED'];
    protected $status = ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'];
    protected $disk = 'public';

    public function storeFile($employeeId, $file)
    {
        $folder = "employees/{$employeeId}";

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
            $counter++;
        }


        // Enregistrer le fichier avec un nom unique
        $path = $file->storeAs($folder, "$fileName.$extension", $disk);

        // Sauvegarder les informations du fichier dans la base de données
        return File::create([
            'employee_id' => $employeeId,
            'name' => "$fileName.$extension", // (nom + extension)
            'display_name' => "bulletin_" . now()->format('d-m-Y') . ".$extension",
            'path' => $path,
            'url' => Storage::url($path), // URL publique
            'mime_type' => $file->getClientMimeType(), // Type MIME
            'status' => $this->status[0],
        ]);
    }

    // public function storeFilesWithCodes(array $files)
    // {
    //     $disk = 'public';
    //     $success = [];
    //     $failed = [];
    //     $missing = [];

    //     // Récupérer les codes d'employés valides
    //     $employeeCodes = Employee::pluck('id', 'code'); // ['code1' => id1, 'code2' => id2, ...]

    //     $employeeCodes = Employee::whereDoesntHave('users', function ($query) {
    //         $query->role('ADMIN');
    //     })
    //     ->whereHas('duties', function ($query) {
    //         $query->where('evolution', 'ON_GOING');
    //     })
    //     ->where('status', $this->status[0])
    //     ->pluck('id', 'code');



    //     // Marquer tous les employés comme sans fichier au départ
    //     $employeesWithoutFiles = $employeeCodes->keys()->toArray();

    //     foreach ($files as $file) {
    //         // Extraire le code employé depuis le nom du fichier (par exemple "CODE123.pdf")
    //         $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    //         $code = strtoupper($fileName); // Assure la correspondance sans distinction de casse

    //         if (!isset($employeeCodes[$code])) {
    //             // Si le code ne correspond pas, ajouter aux échecs
    //             $failed[] = $file->getClientOriginalName();
    //             continue;
    //         }

    //         $employeeId = $employeeCodes[$code];
    //         $folder = "employees/{$employeeId}";

    //         // Créer le répertoire si nécessaire
    //         if (!Storage::disk($disk)->exists($folder)) {
    //             Storage::disk($disk)->makeDirectory($folder);
    //         }

    //         $extension = strtolower($file->getClientOriginalExtension());
    //         $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

    //         // Générer un nom unique si nécessaire
    //         $counter = 1;
    //         $finalName = $originalName;
    //         while (Storage::disk($disk)->exists("$folder/$finalName.$extension")) {
    //             $finalName = "{$originalName}_{$counter}";
    //             $counter++;
    //         }

    //         // Enregistrer le fichier
    //         try {
    //             $path = $file->storeAs($folder, "$finalName.$extension", $disk);

    //             // Sauvegarder les informations dans la base de données
    //             File::create([
    //                 'employee_id' => $employeeId,
    //                 'name' => "$finalName.$extension",
    //                 'display_name' => "bulletin_" . now()->format('d-m-Y') . ".$extension",
    //                 'path' => $path,
    //                 'url' => Storage::url($path),
    //                 'mime_type' => $file->getClientMimeType(),
    //                 'status' => $this->status[0],
    //             ]);

    //             $success[] = $code;
    //             $employeesWithoutFiles = array_diff($employeesWithoutFiles, [$code]);
    //         } catch (\Exception $e) {
    //             // En cas d'erreur, ajouter aux échecs
    //             $failed[] = $file->getClientOriginalName();
    //         }
    //     }

    //     // Les employés sans fichiers
    //     foreach ($employeesWithoutFiles as $code) {
    //         $missing[] = $code;
    //     }

    //     return [
    //         'success' => $success,
    //         'failed' => $failed,
    //         'missing' => $missing,
    //     ];
    // }



    public function renameFile(File $file, $newName)
    {
        $extension = pathinfo($file->path, PATHINFO_EXTENSION);

        $file->update([
            'display_name' => "$newName.$extension",
        ]);

        return $file;
    }



    public function deleteFile(File $file)
    {
        // Supprimer le fichier du disque principal
        if (Storage::exists($file->path)) {
            Storage::delete($file->path);
        }

        // Supprimer également le fichier dans 'public/storage', si nécessaire
        $publicPath = public_path('storage/' . str_replace('public/', '', $file->path));
        if (file_exists($publicPath)) {
            unlink($publicPath); // Supprime le fichier du chemin public
        }

        // Supprimer l'entrée de la base de données
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


    public function processBulletin(UploadedFile $file)
    {
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $employeeName = strtoupper($fileName);

        // Trouver l'employé
        $employee = $this->findEmployeeByName($employeeName);

        if (!$employee) {
            return [
                'success' => false,
                'file' => $file->getClientOriginalName(),
                'message' => 'Employé introuvable',
            ];
        }

        // Stockage du fichier
        $folder = "employees/{$employee->id}";
        if (!Storage::disk($this->disk)->exists($folder)) {
            Storage::disk($this->disk)->makeDirectory($folder);
        }

        $extension = $file->getClientOriginalExtension();
        $uniqueName = "bulletin_" . now()->format('d-m-Y') . ".$extension";
        $path = $file->storeAs($folder, $uniqueName, $this->disk);

        // Sauvegarde en base de données
        File::create([
            'employee_id' => $employee->id,
            'name' => $uniqueName,
            'display_name' => $uniqueName,
            'path' => $path,
            'url' => Storage::url($path),
            'mime_type' => $file->getClientMimeType(),
            'status' => $this->status[0],
        ]);

        return [
            'success' => true,
            'employee' => "{$employee->last_name} {$employee->first_name}",
            'file' => $file->getClientOriginalName(),
        ];
    }


/**
     * Recherche un employé par nom en tenant compte du fait que `first_name` peut contenir plusieurs prénoms.
     */
    private function findEmployeeByName($employeeName)
    {
        $parts = explode('_', $employeeName);
        if (count($parts) < 2) {
            return null;
        }

        $lastName = $parts[0]; // Le premier élément est le nom de famille
        $firstName = $parts[1]; // Le premier prénom

        return Employee::where('last_name', $lastName)
            ->whereRaw("first_name LIKE ?", ["$firstName%"])
            ->first();
    }
   



}
