<?php

namespace App\Models\OptiHr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AbsenceType extends Model
{
    use HasFactory;
    protected $fillable = [
        'label',
        'description',
        'type',
        'is_deductible'
    ];

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class, 'absence_type_id');
    }
}
