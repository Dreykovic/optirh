<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Account extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_type_id',
        'state',                 // État du compte : ACTIVE, INACTIVE, etc.
        'current_balance',       // Solde actuel
        'loan_guarantee_amount', // Montant garanti pour prêt
        'fees',                  // Frais associés
        'creation_date',         // Date de création
        'employee_id',           // Employé responsable
        'assistant_id',          // Assistant associé (facultatif)
        'client_id',             // Client propriétaire
        'account_no',            // Numéro de compte
    ];

    protected $attributes = [
        'state' => 'ACTIVE',
        'current_balance' => 0,
        'loan_guarantee_amount' => 0,
        'fees' => 0,
    ];

    /**
     * Get the user that owns the Account.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function account_type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    /**
     * Get the employe associated with the Account.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'employee_id');
    }

    public function assistant(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'assistant_id');
    }

    /**
     * Get all of the transactions for the Account.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id', 'id');
    }

    /**
     * Génère un numéro de compte unique basé sur les attributs du type de compte.
     *
     * @return string
     */
    public function generateAccountNo()
    {
        // Début de la transaction
        return \DB::transaction(function () {
            // Récupère le type de compte associé avec verrouillage pour éviter les modifications simultanées
            $accountType = $this->account_type()->lockForUpdate()->first();

            // Préfixe du numéro de compte et numéro suivant
            $prefix = $accountType->account_number_prefix;
            $nextNumber = $accountType->next_account_number;

            // Génération du numéro de compte
            $accountNo = $prefix.str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

            // Incrémentation du numéro suivant et sauvegarde
            ++$accountType->next_account_number;
            $accountType->save();

            return $accountNo;
        });
    }

    /**
     * Boot the model and auto-generate account_no before creating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($account) {
            // Génère le numéro de compte de manière sécurisée
            $account->account_no = $account->generateAccountNo();
        });
    }
}
