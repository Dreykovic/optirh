<?php

namespace App\Models\OptiHr;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        // 'name',
        'name',
        'description',
        'status',
        'director_id',
    ];

    // public function director(): HasOne
    // {
    //     return $this->hasOne(Employee::class);
    // }
    public function director(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'director_id');
    }





    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'department_id');
    }
}
