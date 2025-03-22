<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;
    use LogsActivity;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'department_id',
        'description',
        'n_plus_one_job_id',
        'status',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function duties(): HasMany
    {
        return $this->hasMany(Duty::class, 'job_id');
    }

    public function n_plus_one_job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'n_plus_one_job_id', 'id');
    }

    public function n_minus_one_jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'n_plus_one_job_id', 'id');
    }
}
