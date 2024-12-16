<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'loans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loan_id',
        'loan_type_id',
        'client_id',
        'status',
        'first_payment_date',
        'release_date',
        'applied_amount',
        'total_principal_paid',
        'total_interest_paid',
        'total_penalties_paid',
        'due_amount',
        'late_payment_penalties',
        'purpose_of_loan',
        'attachment',
        'approved_date',
        'approver_id',
        'creator_id',
        'description',
        'remarks',
        'fees_deduct_account_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'first_payment_date' => 'date',
        'release_date' => 'date',
        'approved_date' => 'date',
        'applied_amount' => 'decimal:2',
        'total_principal_paid' => 'decimal:2',
        'total_interest_paid' => 'decimal:2',
        'total_penalties_paid' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'late_payment_penalties' => 'decimal:2',
    ];

    /**
     * Set the default values for attributes.
     */
    protected $attributes = [
        'status' => 'Approved',  // Statut par défaut
        'late_payment_penalties' => 0,  // Pas de pénalité par défaut
    ];

    /**
     * Get the loan's full status (e.g., "Approved", "Pending", etc.).
     *
     * @return string
     */
    public function getFullStatusAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Scope a query to only include active loans.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Approved');
    }

    /**
     * Get the total amount paid for the loan.
     *
     * @return float
     */
    public function getTotalPaidAttribute()
    {
        return $this->total_principal_paid + $this->total_interest_paid + $this->total_penalties_paid;
    }

    /**
     * Get the remaining balance of the loan.
     *
     * @return float
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->applied_amount - $this->total_paid;
    }

    /**
     * Define the relationship with the LoanType model.
     * Assuming a loan belongs to a loan type.
     */
    public function loan_type()
    {
        return $this->belongsTo(LoanType::class, 'id', 'loan_type_id');
    }

    public function fees_deduct_account()
    {
        return $this->belongsTo(Account::class, 'id', 'fees_deduct_account_id');
    }

    /**
     * Define the relationship with the Borrower model.
     * Assuming a loan belongs to a borrower.
     */
    public function borrower()
    {
        return $this->belongsTo(Client::class, 'id', 'client_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'id', 'approver_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'id', 'creator_id');
    }
}
