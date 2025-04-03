<?php

namespace App\Models\Recours;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Applicant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'status',
        'created_by',
        'last_updated_by',
        'nif',
        'email'
    ];

    public function appeals(): HasMany
    {
        return $this->hasMany(Appeal::class, 'applicant_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Personnal::class, 'created_by');
    }

    public function updator(): BelongsTo
    {
        return $this->belongsTo(Personnal::class, 'last_updated_by');
    }
}
