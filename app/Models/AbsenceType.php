<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AbsenceType extends Model
{
    use HasFactory;
    protected $fillable = [
        'label',
        'description',
    ];

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class, 'absence_type_id');
    }
}
