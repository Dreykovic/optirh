<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        // 'name',
        'amount',
        'status',
        'type',
        'description',
        'date',
        'account_id',
        'employee_id',
        'assistant_id',

        'transaction_type_id',
    ];

    /**
     * Get the employee that owns the Transaction.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function assistant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assistant_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function transaction_type(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            switch ($model->type) {
                case 'WITHDRAWAL':
                    // code...
                    break;
                case 'DEPOSIT':
                    // code...
                    break;
                case 'CONTRIBUTION':
                    // code...
                    break;
                case 'REFUND':
                    // code...
                    break;
                case 'INFLOW':
                    // code...
                    break;
                case 'OUTFLOW':
                    // code...
                    break;

                default:
                    // code...
                    break;
            }
        });
    }
}
