<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\OptiHr\Duty;
use App\Models\OptiHr\Employee;
use App\Models\OptiHr\Publication;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use LogsActivity;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'name',
        'email',
        'password',

        'username',
        'status',
        'profile',
        'picture',
        'employee_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    // protected $dateFormat = 'Y-m-d H:i:sO';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, foreignKey: 'employee_id');
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class, foreignKey: 'author_id');
    }

    /**
     * Vérifie si l'utilisateur a un employé associé
     */
    public function hasEmployee(): bool
    {
        return $this->employee_id !== null && $this->employee !== null;
    }

    /**
     * Retourne le nom d'affichage de l'utilisateur
     * (nom de l'employé si disponible, sinon username)
     */
    public function getDisplayName(): string
    {
        return $this->hasEmployee()
            ? $this->employee->first_name.' '.$this->employee->last_name
            : $this->username;
    }

    /**
     * Retourne le poste actuel de l'utilisateur de façon sécurisée
     */
    public function getCurrentDuty(): ?Duty
    {
        return $this->hasEmployee()
            ? $this->employee->duties->firstWhere('evolution', 'ON_GOING')
            : null;
    }

    /**
     * Retourne le solde de congés de façon sécurisée
     */
    public function getAbsenceBalance(): int
    {
        $duty = $this->getCurrentDuty();

        return $duty ? ($duty->absence_balance ?? 0) : 0;
    }
}
