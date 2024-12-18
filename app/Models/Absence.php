<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'requested_days',
        'start_date',
        'end_date',
        'address',
        'date_of_application',
        'date_of_approval',
        'level',
        'stage',
        'status',
        'reasons',
        'proof',
        'comment',
        'duty_id',
    ];

    public function duty(): BelongsTo
    {
        return $this->belongsTo(Duty::class, 'duty_id');
    }

    public function absence_type(): BelongsTo
    {
        return $this->belongsTo(AbsenceType::class, 'absence_type_id');
    }
}
