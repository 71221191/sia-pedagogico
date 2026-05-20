<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use  HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'username',
        'password',
        'email',
        'is_active',
        'must_change_password',
        'avatar_url',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // --- REVISA DESDE AQUÍ HACIA ABAJO ---

    /**
     * Relación con el perfil humano (Persona)
     */
    public function person()
    {
        // Un usuario tiene una persona vinculada por user_id
        return $this->hasOne(Person::class, 'user_id');
    }

    /**
     * Casts para tipos de datos
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }

}
