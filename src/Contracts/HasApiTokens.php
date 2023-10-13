<?php

namespace QuantaQuirk\Sanctum\Contracts;

interface HasApiTokens
{
    /**
     * Get the access tokens that belong to model.
     *
     * @return \QuantaQuirk\Database\Eloquent\Relations\MorphMany
     */
    public function tokens();

    /**
     * Determine if the current API token has a given scope.
     *
     * @param  string  $ability
     * @return bool
     */
    public function tokenCan(string $ability);

    /**
     * Create a new personal access token for the user.
     *
     * @param  string  $name
     * @param  array  $abilities
     * @return \QuantaQuirk\Sanctum\NewAccessToken
     */
    public function createToken(string $name, array $abilities = ['*']);

    /**
     * Get the access token currently associated with the user.
     *
     * @return \QuantaQuirk\Sanctum\Contracts\HasAbilities
     */
    public function currentAccessToken();

    /**
     * Set the current access token for the user.
     *
     * @param  \QuantaQuirk\Sanctum\Contracts\HasAbilities  $accessToken
     * @return \QuantaQuirk\Sanctum\Contracts\HasApiTokens
     */
    public function withAccessToken($accessToken);
}
