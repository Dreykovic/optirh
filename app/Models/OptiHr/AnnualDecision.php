<?php

namespace App\Models\OptiHr;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualDecision extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'number',
        'year',
        'date',
        'pdf',
        'state',
        'reference',
    ];
}
