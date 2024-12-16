<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'account_number_prefix',
        'starting_account_number',

        'next_account_number',
        'currency',
        'interest_rate',
        'interest_method',
        'interest_period',
        'minimum_balance_for_interest',
        'allow_withdraw',
        'minimum_deposit_amount',
        'minimum_account_balance',
        'maintenance_fee',
        'maintenance_fee_posting_month',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'interest_rate' => 'decimal:2',
        'minimum_balance_for_interest' => 'decimal:2',
        'minimum_deposit_amount' => 'decimal:2',
        'minimum_account_balance' => 'decimal:2',
        'maintenance_fee' => 'decimal:2',
    ];

    /**
     * Set the default values for attributes.
     */
    protected $attributes = [
        'currency' => 'USD',
        'interest_rate' => 0.0,
        'interest_method' => 'Pro-Rata Basis',
        'interest_period' => 'Every 3 months',
        'minimum_balance_for_interest' => 10.0,
        'allow_withdraw' => true,
        'minimum_deposit_amount' => 0.0,
        'minimum_account_balance' => 0.0,
        'maintenance_fee' => 0.0,
        'status' => 'ACTIVE',
        'starting_account_number' => 0,
    ];

    /**
     * Get all of the accounts for the AccountType.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
