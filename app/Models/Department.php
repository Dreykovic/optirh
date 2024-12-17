<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Department extends Model
{
    use HasFactory;
    protected $fillable = [
        // 'name',
        'name',
        'description',
        'status',
        'director_id',
    ];

    public function director(): HasOne
    {
        return $this->hasOne(Employee::class, 'director_id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'department_id');
    }
}
