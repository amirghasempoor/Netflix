<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
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

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value == null ? null : Storage::disk('public')->url($value)
        );
    }
}
