<?php

namespace Tests\Examples\Models;

use Dmn\Modules\Auth\Contracts\AuthModel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements AuthModel
{
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'is_active',
        'email_verified_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function getTypeIdentifier(): string
    {
        return 'type';
    }
}
