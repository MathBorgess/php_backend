<?php

namespace App\Model\OAuth;

class PersonalAccessClient
{
    protected $table = 'oauth_personal_access_clients';

    protected $fillable = [
    'id',
    'client_id',
    'created_at',
    ];
}

