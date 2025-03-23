<?php

namespace App\Models\OptiHr;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasFactory;
    use LogsActivity;


    protected $fillable = [
        'title',
        'start_date',
        'description',
        'duty_id',
        'status',
        'stage',

    ];

    public function duty(): BelongsTo
    {
        return $this->belongsTo(Duty::class, 'duty_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Goal::class, 'evaluation_id');
    }
}
