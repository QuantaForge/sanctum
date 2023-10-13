<?php

namespace QuantaQuirk\Sanctum\Http\Middleware;

use QuantaQuirk\Sanctum\Exceptions\MissingScopeException;

/**
 * @deprecated
 * @see \QuantaQuirk\Sanctum\Http\Middleware\CheckAbilities
 */
class CheckScopes
{
    /**
     * Handle the incoming request.
     *
     * @param  \QuantaQuirk\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$scopes
     * @return \QuantaQuirk\Http\Response
     *
     * @throws \QuantaQuirk\Auth\AuthenticationException|\QuantaQuirk\Sanctum\Exceptions\MissingScopeException
     */
    public function handle($request, $next, ...$scopes)
    {
        try {
            return (new CheckAbilities())->handle($request, $next, ...$scopes);
        } catch (\QuantaQuirk\Sanctum\Exceptions\MissingAbilityException $e) {
            throw new MissingScopeException($e->abilities());
        }
    }
}
