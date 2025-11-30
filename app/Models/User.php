<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password', 
        'phone',
        'dob',
        'profile_picture'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'dob' => 'date',
        'password' => 'hashed',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function qualifications(): HasMany
    {
        return $this->hasMany(Qualification::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }
}
