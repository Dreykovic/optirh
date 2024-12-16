<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanType extends Model
{
    use HasFactory;

    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'loan_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'loan_id_prefix',
        'starting_loan_id',
        'minimum_amount',
        'maximum_amount',
        'interest_rate_per_year',
        'interest_type_id',
        'max_term',
        'term_period',
        'late_payment_penalties',
        'status',
        'loan_application_fee',
        'loan_application_fee_type',
        'loan_processing_fee',
        'loan_processing_fee_type',
        'description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'interest_rate_per_year' => 'decimal:2',
        'late_payment_penalties' => 'decimal:2',
        'loan_application_fee' => 'decimal:2',
        'loan_processing_fee' => 'decimal:2',
    ];

    /**
     * Set the default values for attributes.
     */
    protected $attributes = [
        'status' => 'Active',
        'loan_application_fee_type' => 'Fixed',
        'loan_processing_fee_type' => 'Fixed',
    ];

    public function interest_type(): BelongsTo
    {
        return $this->belongsTo(InterestType::class, 'interest_type_id', 'id');
    }

    /**
     * Scope a query to only include active loan types.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Get the full loan ID by concatenating prefix and starting ID.
     *
     * @return string
     */
    public function getFullLoanIdAttribute()
    {
        return $this->loan_id_prefix.str_pad($this->starting_loan_id, 6, '0', STR_PAD_LEFT);
    }
}
