<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Absence extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'requested_days',
        'absence_type_id',
        'start_date',
        'end_date',
        'address',
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

    public function absence_type(): BelongsTo
    {
        return $this->belongsTo(AbsenceType::class, 'absence_type_id');
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
                    $this->stage = 'IN_PROGRESS';
                    $this->level = 'TWO';
                    break;

                case 'TWO':
                    $this->stage = 'APPROVED';
                    $this->level = 'THREE';

                    // Trouver le maximum actuel de absence_number de manière sécurisée
                    $maxAbsenceNumber = DB::table($this->getTable())
                        ->whereNotNull('absence_number') // Filtrer les entrées valides
                        ->orderByDesc('absence_number') // Trier par ordre décroissant
                        ->lockForUpdate() // Verrouiller les lignes pour éviter les conflits
                        ->value('absence_number'); // Obtenir la valeur maximale

                    $this->absence_number = $maxAbsenceNumber ? $maxAbsenceNumber + 1 : 1;
                    $this->date_of_approval = new Carbon();
                    break;

                default:
                    $this->stage = 'APPROVED';
                    $this->level = 'THREE';

                    // Trouver le maximum actuel de absence_number de manière sécurisée
                    $maxAbsenceNumber = DB::table($this->getTable())
                        ->whereNotNull('absence_number') // Filtrer les entrées valides
                        ->orderByDesc('absence_number') // Trier par ordre décroissant
                        ->lockForUpdate() // Verrouiller les lignes pour éviter les conflits
                        ->value('absence_number'); // Obtenir la valeur maximale

                    $this->absence_number = $maxAbsenceNumber ? $maxAbsenceNumber + 1 : 1;
                    $this->date_of_approval = new Carbon();

                    break;
            }

            // Sauvegarder les changements dans la transaction
            $this->save();
        });
    }
}
