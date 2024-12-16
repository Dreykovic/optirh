<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InterestType extends Model
{
    use HasFactory;
    /**
     * Les attributs assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'default_rate',
        'is_variable',
        'calculation_method',
    ];

    /**
     * Calcul des intérêts en fonction du type d'intérêt.
     *
     * @param float $principal
     * @param int   $term
     *
     * @return float
     */
    public function calculateInterest($principal, $term)
    {
        switch ($this->calculation_method) {
            case 'flat':
                return $this->calculateFlatInterest($principal, $term);

            case 'reducing':
                return $this->calculateReducingInterest($principal, $term);

            case 'mortgage':
                return $this->calculateMortgageInterest($principal, $term);

            case 'one-time':
                return $this->calculateOneTimeInterest($principal);

            default:
                return 0;
        }
    }

    /**
     * Calcul de l'intérêt en taux fixe (flat rate).
     *
     * @param float $principal
     * @param int   $term
     *
     * @return float
     */
    protected function calculateFlatInterest($principal, $term)
    {
        return $principal * ($this->default_rate / 100) * $term;
    }

    /**
     * Calcul de l'intérêt en montant dégressif (reducing amount).
     *
     * @param float $principal
     * @param int   $term
     *
     * @return float
     */
    protected function calculateReducingInterest($principal, $term)
    {
        $totalInterest = 0;
        $remainingPrincipal = $principal;

        for ($i = 0; $i < $term; ++$i) {
            $monthlyInterest = $remainingPrincipal * ($this->default_rate / 100);
            $totalInterest += $monthlyInterest;
            $remainingPrincipal -= $principal / $term;
        }

        return $totalInterest;
    }

    /**
     * Calcul de l'amortissement hypothécaire.
     *
     * @param float $principal
     * @param int   $term
     *
     * @return float
     */
    protected function calculateMortgageInterest($principal, $term)
    {
        // Par exemple, utilisation d'une formule d'amortissement hypothécaire.
        $rate = $this->default_rate / 100 / 12; // Taux mensuel
        $months = $term * 12; // Durée en mois

        return $principal * $rate * $months / (1 - pow(1 + $rate, -$months));
    }

    /**
     * Calcul pour un paiement unique (one-time payment).
     *
     * @param float $principal
     *
     * @return float
     */
    protected function calculateOneTimeInterest($principal)
    {
        return $principal * ($this->default_rate / 100);
    }

    /**
     * Get all of the accounts for the AccountType.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
