<?php

namespace App\Models\OptiHr;

use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * Modèle Absence - Gestion des demandes d'absence
 * 
 * Ce modèle représente une demande d'absence dans le système OPTIRH.
 * Il gère le workflow de validation hiérarchique des congés et absences.
 * 
 * Workflow des absences :
 * - PENDING : En attente de traitement
 * - IN_PROGRESS : En cours de validation
 * - APPROVED : Validée et approuvée
 * - REJECTED : Rejetée
 * - CANCELLED : Annulée par l'employé
 * 
 * @package App\Models\OptiHr
 * @author OPTIRH Team
 */
class Absence extends Model
{
    use HasFactory;
    use LogsActivity; // Trait pour l'audit des actions sur les absences

    /**
     * Nom de la table dans la base de données
     *
     * @var string
     */
    protected $table = 'absences';

    /**
     * Les attributs qui peuvent être assignés en masse
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'requested_days',      // Nombre de jours demandés
        'absence_type_id',     // Type d'absence (congé, maladie, etc.)
        'start_date',          // Date de début d'absence
        'end_date',            // Date de fin d'absence
        'address',             // Adresse pendant l'absence
        'date_of_application', // Date de la demande
        'date_of_approval',    // Date d'approbation
        'level',               // Niveau de validation (ZERO, ONE, TWO, THREE)
        'stage',               // Étape du workflow (PENDING, IN_PROGRESS, APPROVED, etc.)
        'status',              // Statut général
        'reasons',             // Motifs de la demande
        'proof',               // Pièces justificatives
        'comment',             // Commentaires des validateurs
        'duty_id',             // Référence au poste de l'employé
        'is_deductible'        // Si l'absence est déductible du solde
    ];

    /**
     * Les attributs qui doivent être cachés lors de la sérialisation
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // Aucun attribut sensible à masquer pour les absences
    ];

    /**
     * Les attributs qui doivent être castés vers des types natifs
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'date_of_application' => 'datetime',
        'date_of_approval' => 'datetime',
        'requested_days' => 'integer',
        'is_deductible' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec le poste (duty) de l'employé
     * 
     * Une absence est liée au poste occupé par l'employé
     * au moment de la demande
     *
     * @return BelongsTo
     */
    public function duty(): BelongsTo
    {
        return $this->belongsTo(Duty::class, 'duty_id');
    }

    /**
     * Relation avec le type d'absence
     * 
     * Définit le type de congé ou absence demandé
     * (congé annuel, maladie, maternité, etc.)
     *
     * @return BelongsTo
     */
    public function absence_type(): BelongsTo
    {
        return $this->belongsTo(AbsenceType::class, 'absence_type_id');
    }

    /**
     * Met à jour le niveau et l'état de l'absence selon le workflow de validation
     * 
     * Cette méthode gère la progression hiérarchique de validation :
     * - ZERO -> ONE : Première validation (chef direct)
     * - ONE -> TWO : Deuxième validation (responsable département)
     * - TWO -> THREE : Validation finale (RH/Direction) avec attribution du numéro
     * 
     * @return void
     */
    public function updateLevelAndStage(): void
    {
        DB::transaction(function () {
            switch ($this->level) {
                case 'ZERO':
                    // Premier niveau de validation : chef direct
                    $this->stage = 'IN_PROGRESS';
                    $this->level = 'ONE';
                    break;

                case 'ONE':
                    // Deuxième niveau de validation : responsable département
                    $this->stage = 'IN_PROGRESS';
                    $this->level = 'TWO';
                    break;

                case 'TWO':
                    // Validation finale : attribution du numéro d'absence
                    $this->stage = 'APPROVED';
                    $this->level = 'THREE';
                    $this->assignAbsenceNumber();
                    $this->date_of_approval = Carbon::now();
                    break;

                default:
                    // Cas par défaut : validation directe
                    $this->stage = 'APPROVED';
                    $this->level = 'THREE';
                    $this->assignAbsenceNumber();
                    $this->date_of_approval = Carbon::now();
                    break;
            }

            // Sauvegarder les changements dans la transaction
            $this->save();
        });
    }

    /**
     * Assigne un numéro séquentiel unique à l'absence
     * 
     * Utilise un verrou de base de données pour éviter les conflits
     * lors de l'attribution de numéros séquentiels
     * 
     * @return void
     */
    private function assignAbsenceNumber(): void
    {
        // Trouver le maximum actuel de absence_number de manière sécurisée
        $maxAbsenceNumber = DB::table($this->getTable())
            ->whereNotNull('absence_number') // Filtrer les entrées valides
            ->orderByDesc('absence_number') // Trier par ordre décroissant
            ->lockForUpdate() // Verrouiller pour éviter les conflits de concurrence
            ->value('absence_number'); // Obtenir la valeur maximale

        // Attribuer le prochain numéro disponible
        $this->absence_number = $maxAbsenceNumber ? $maxAbsenceNumber + 1 : 1;
    }

    /**
     * Vérifie si l'absence est en attente de validation
     * 
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->stage === 'PENDING';
    }

    /**
     * Vérifie si l'absence est approuvée
     * 
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->stage === 'APPROVED';
    }

    /**
     * Vérifie si l'absence est rejetée
     * 
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->stage === 'REJECTED';
    }

    /**
     * Calcule la durée en jours de l'absence
     * 
     * @return int
     */
    public function getDurationAttribute(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Récupère le nom complet de l'employé
     * 
     * @return string|null
     */
    public function getEmployeeNameAttribute(): ?string
    {
        return $this->duty && $this->duty->employee 
            ? $this->duty->employee->full_name 
            : null;
    }

    /**
     * Scope pour filtrer les absences par stage
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $stage
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStage($query, string $stage)
    {
        return $query->where('stage', $stage);
    }

    /**
     * Scope pour filtrer les absences par type
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $typeId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType($query, int $typeId)
    {
        return $query->where('absence_type_id', $typeId);
    }

    /**
     * Scope pour filtrer les absences par période
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPeriod($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }
}
