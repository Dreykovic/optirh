<?php

namespace App\Models\OptiHr;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualDecision extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'number',
        'year',
        'date',
        'pdf',
        'state',
        'reference',
    ];
    
    protected $casts = [
        'date' => 'date',
        'year' => 'integer',
    ];
    
    protected $attributes = [
        'state' => 'outdated',
    ];
    
    /**
     * Scope a query to only include current decisions.
     */
    public function scopeCurrent($query)
    {
        return $query->where('state', 'current');
    }
    
    /**
     * Scope a query to only include outdated decisions.
     */
    public function scopeOutdated($query)
    {
        return $query->where('state', 'outdated');
    }
    
    /**
     * Check if the decision is current.
     */
    public function isCurrent(): bool
    {
        return $this->state === 'current';
    }
    
    /**
     * Get the formatted decision number.
     */
    public function getFormattedNumberAttribute(): string
    {
        $parts = [$this->number, $this->year];
        if ($this->reference) {
            $parts[] = $this->reference;
        }
        return implode('/', $parts);
    }
}
