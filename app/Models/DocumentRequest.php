<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class DocumentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type_id',
        'start_date',
        'end_date',

        'date_of_application',
        'date_of_approval',
        'level',
        'stage',
        'status',
        'reasons',
        'proof',
        'comment',
        'duty_id',
    ];

    public function duty(): BelongsTo
    {
        return $this->belongsTo(Duty::class, 'duty_id');
    }

    public function document_type(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    /**
     * Incrémente le numéro d'absence si le stage est 'COMPLETED'.
     */
    /**
     * Met à jour le niveau et l'état de l'absence.
     */
    public function updateLevelAndStage()
    {
        DB::transaction(function () {
            switch ($this->level) {
                case 'ZERO':
                    $this->stage = 'IN_PROGRESS';
                    $this->level = 'ONE';
                    break;

                case 'ONE':
                    $this->stage = 'APPROVED';
                    $this->level = 'TWO';

                    // Trouver le maximum actuel de document_number de manière sécurisée
                    $maxAbsenceNumber = DB::table($this->getTable())
                        ->whereNotNull('document_number') // Filtrer les entrées valides
                        ->orderByDesc('document_number') // Trier par ordre décroissant
                        ->lockForUpdate() // Verrouiller les lignes pour éviter les conflits
                        ->value('document_number'); // Obtenir la valeur maximale

                    $this->document_number = $maxAbsenceNumber ? $maxAbsenceNumber + 1 : 1;
                    break;

                default:
                    $this->stage = 'APPROVED';
                    $this->level = 'TWO';

                    // Trouver le maximum actuel de document_number de manière sécurisée
                    $maxAbsenceNumber = DB::table($this->getTable())
                        ->whereNotNull('document_number') // Filtrer les entrées valides
                        ->orderByDesc('document_number') // Trier par ordre décroissant
                        ->lockForUpdate() // Verrouiller les lignes pour éviter les conflits
                        ->value('document_number'); // Obtenir la valeur maximale

                    $this->document_number = $maxAbsenceNumber ? $maxAbsenceNumber + 1 : 1;

                    break;
            }

            // Sauvegarder les changements dans la transaction
            $this->save();
        });
    }
}
