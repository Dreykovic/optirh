<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'name',
        'matricule',
        'first_name',
        'last_name',
        'email',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'bank_name',
        'code_bank',
        'code_guichet',
        'rib',
        'cle_rib',
        'iban',
        'swift',
        'birth_date',
        'nationality',
        'religion',
        'marital_status',
        'emergency_contact',
        'status',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'employee_id');
    }

    public function duties(): HasMany
    {
        return $this->hasMany(Duty::class, 'employee_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'employee_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'director_id');
    }
}
