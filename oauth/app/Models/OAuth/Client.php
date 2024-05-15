<?php

namespace App\Model\OAuth;

class Client
{
    protected $table = 'oauth_clients';

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'secret',
        'provider',
        'redirect',
        'personal_access_client',
        'password_client',
        'revoked'
    ];
}
