<?php

namespace App\Models\OptiHr;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;
    use LogsActivity;


    protected $fillable = [
        'url',
        'mime_type',
        'description',
        'path',
        'data',
        'upload_date',
        'status',
        'name',
        'display_name',
        'employee_id',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
