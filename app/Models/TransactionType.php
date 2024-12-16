<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TransactionType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',      // Nom du type de transaction
        'related_to',  // À quoi est lié le type de transaction(debit ou credit)
        'status',      // Statut du type de transaction
    ];

    /**
     * Get all of the accounts for the AccountType.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transactionType) {
            if (empty($transactionType->code)) {
                // Génère un code unique, par exemple en utilisant un identifiant UUID raccourci ou une chaîne aléatoire.
                $transactionType->code = strtoupper(Str::random(8));
            }
        });
    }
}
