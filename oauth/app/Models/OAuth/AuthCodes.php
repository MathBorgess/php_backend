<?php

namespace App\Model\OAuth;

class AuthCodes
{
    protected $table = 'oauth_auth_codes';

    protected $fillable = [
        'id',
        'user_id',
        'client_id',
        'scopes',
        'revoked',
        'expires_at',
    ];
}
