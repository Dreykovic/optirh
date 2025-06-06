<?php

namespace App\Models\Recours;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appeal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'date_depot',
        'type',
        'deposit_date',
        'deposit_hour',
        'object',
        'day_count',
        'analyse_status',
        'status',
        'dac_id',
        'decision_id',
        'authority_id',
        'created_by',
        'applicant_id',
        'last_updated_by',
        'notif_date',
        'message_date',
        'response_date',
        'publish_date'
    ];

   
    public function decided(): BelongsTo
    {
        return $this->belongsTo(Decision::class, 'decided_id');
    }
    public function suspended(): BelongsTo
    {
        return $this->belongsTo(Decision::class, 'suspended_id');
    }

    public function dac(): BelongsTo
    {
        return $this->belongsTo(Dac::class, 'dac_id');
    }

    public function authority(): BelongsTo
    {
        return $this->belongsTo(Authority::class, 'authority_id');
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'appeal_id');
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
