<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Spatie\Permission\Traits\HasRoles;

class Operator extends SanctumPersonalAccessToken
{
    use HasFactory, HasRoles, HasApiTokens;

    protected $fillable = [
        'username',
        'password',
        'email'
    ];
}
