<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Operator extends Authenticatable
{
    use HasFactory, HasRoles, HasApiTokens;

    protected $fillable = [
        'username',
        'password',
        'email'
    ];
}
