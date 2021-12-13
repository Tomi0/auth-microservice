<?php

namespace Authentication\Infrastructure\Domain\Model\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shared\Infrastructure\Domain\Model\HasUuid;

class User extends Model
{
    use HasUuid;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'user';

    protected $fillable = [
        'username',
        'email',
        'email_verified_at',
        'password',
        'remember_token'
    ];

    protected $casts = [
        'username' => 'string',
        'email' => 'string',
        'email_verified_at' => 'string',
        'password' => 'string',
        'remember_token' => 'string',
    ];
}
