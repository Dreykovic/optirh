<?php

namespace App\Models\OptiHr;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class DocumentRequest extends Model
{
    use HasFactory;
    use LogsActivity;

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
                    $this->document_number = $this->generateDocumentNumber();
                    break;

                default:
                    $this->stage = 'APPROVED';
                    $this->level = 'TWO';
                    $this->document_number = $this->generateDocumentNumber();
                    break;
            }

            // Sauvegarder les changements dans la transaction
            $this->save();
        });
    }

    /**
     * Génère un numéro de document unique pour l'année en cours
     * Utilise un lock pessimiste pour éviter les doublons en cas de requêtes concurrentes
     */
    private function generateDocumentNumber(): int
    {
        $currentYear = now()->year;

        // Trouver le maximum actuel de document_number pour l'année en cours
        $maxNumber = DB::table($this->getTable())
            ->whereNotNull('document_number')
            ->whereYear('created_at', $currentYear)
            ->lockForUpdate()
            ->max('document_number');

        return $maxNumber ? $maxNumber + 1 : 1;
    }
}
