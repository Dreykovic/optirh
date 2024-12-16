<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',

        'name',
        'address1',
        'address2',
        'country',
        'city',
        'state',
        'business_name',
        'client_no',
        'zip',
        'job',
        'phone',
        'gender',
        'birthdate',
        'status',
        'profile_picture',
        'user_id',
    ];
    protected $attributes = [
        'country' => 'Togo',
        'city' => 'Tchamba',
        'status' => 'ACTIF',
        'profile_picture' => 'assets/images/profile_av.png',
    ];

    // Génération automatique de client_no
    public static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            $client->client_no = $client->generateClientNo();
        });
    }

    // Fonction de génération de client_no
    protected function generateClientNo()
    {
        // Génère un identifiant unique pour le client
        $prefix = 'C';
        $lastClient = Client::orderBy('id', 'desc')->first();
        $lastId = $lastClient ? $lastClient->id + 1 : 1;

        return $prefix.str_pad($lastId, 6, '0', STR_PAD_LEFT);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'client_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'client_id');
    }
}
