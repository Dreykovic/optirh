<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

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
        'profile_picture',
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
    protected $dateFormat = 'Y-m-d H:i:sO';

    public function created_accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'employee_id');
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'user_id', 'id');
    }

    public function created_transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'employee_id');
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'assistant_id');
    }

    public function approved_loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'approver_id');
    }

    public function created_loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'creator_id');
    }
}
