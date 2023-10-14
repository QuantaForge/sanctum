<?php

namespace QuantaForge\Sanctum\Events;

class TokenAuthenticated
{
    /**
     * The personal access token that was authenticated.
     *
     * @var \QuantaForge\Sanctum\PersonalAccessToken
     */
    public $token;

    /**
     * Create a new event instance.
     *
     * @param  \QuantaForge\Sanctum\PersonalAccessToken  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }
}
