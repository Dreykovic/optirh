<?php

namespace App\Models\OptiHr;

use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Employee - Gestion des employés
 * 
 * Ce modèle représente un employé dans le système OPTIRH.
 * Il contient toutes les informations personnelles, professionnelles 
 * et bancaires d'un employé.
 * 
 * @package App\Models\OptiHr
 * @author OPTIRH Team
 */
class Employee extends Model
{
    use HasFactory;
    use LogsActivity; // Trait pour l'audit des actions sur les employés

    /**
     * Nom de la table dans la base de données
     *
     * @var string
     */
    protected $table = 'employees';

    /**
     * Les attributs qui peuvent être assignés en masse
     * 
     * Comprend les informations personnelles, d'adresse,
     * bancaires et de statut de l'employé.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'matricule',           // Numéro matricule unique de l'employé
        'first_name',          // Prénom
        'last_name',           // Nom de famille
        'email',               // Adresse email professionnelle
        'address1',            // Adresse principale
        'address2',            // Complément d'adresse
        'city',                // Ville
        'state',               // État/Région
        'country',             // Pays
        'bank_name',           // Nom de la banque
        'code_bank',           // Code banque
        'code_guichet',        // Code guichet
        'rib',                 // Relevé d'Identité Bancaire
        'cle_rib',             // Clé RIB
        'iban',                // International Bank Account Number
        'swift',               // Code SWIFT/BIC
        'birth_date',          // Date de naissance
        'nationality',         // Nationalité
        'religion',            // Religion (optionnel)
        'marital_status',      // Situation matrimoniale
        'emergency_contact',   // Contact d'urgence
        'status',              // Statut de l'employé (ACTIVATED, DEACTIVATED, etc.)
        'phone_number',        // Numéro de téléphone
        'gender',              // Genre (MALE, FEMALE)
    ];

    /**
     * Les attributs qui doivent être cachés lors de la sérialisation
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'rib',
        'iban',
        'swift',
    ];

    /**
     * Les attributs qui doivent être castés vers des types natifs
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec les utilisateurs système
     * 
     * Un employé peut avoir plusieurs comptes utilisateurs
     * (par exemple, un compte principal et des comptes temporaires)
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'employee_id');
    }

    /**
     * Relation avec les postes occupés (duties)
     * 
     * Un employé peut avoir plusieurs postes au fil du temps
     * (historique des postes, promotions, mutations, etc.)
     *
     * @return HasMany
     */
    public function duties(): HasMany
    {
        return $this->hasMany(Duty::class, 'employee_id');
    }

    /**
     * Relation avec les fichiers attachés
     * 
     * Stockage des documents personnels de l'employé
     * (CV, diplômes, certificats, photos, etc.)
     *
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'employee_id');
    }

    /**
     * Récupère le poste actuel de l'employé
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentDuty()
    {
        return $this->hasOne(Duty::class, 'employee_id')
                    ->where('evolution', 'ON_GOING')
                    ->latest();
    }

    /**
     * Récupère le nom complet de l'employé
     * 
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Vérifie si l'employé est actif
     * 
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === 'ACTIVATED';
    }

    /**
     * Récupère l'âge de l'employé
     * 
     * @return int|null
     */
    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    /**
     * Scope pour filtrer les employés actifs
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVATED');
    }

    /**
     * Scope pour rechercher par nom ou prénom
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'LIKE', "%{$search}%")
              ->orWhere('last_name', 'LIKE', "%{$search}%")
              ->orWhere('matricule', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%");
        });
    }
}
