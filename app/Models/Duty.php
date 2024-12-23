<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Duty extends Model
{
    use HasFactory;
    protected $fillable = [
        'duration',
        'begin_date',
        'type',
        'job_id',
        'employee_id',
        'evolution',
        'status',
        'absence_balance'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class, 'duty_id');
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class, 'duty_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Absence::class, 'duty_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($duty) {
            // Génère le numéro de compte de manière sécurisée
            $duty->absence_balance = 30;
        });
    }

}
