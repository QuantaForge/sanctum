<?php

namespace QuantaForge\Sanctum\Http\Middleware;

use QuantaForge\Sanctum\Exceptions\MissingScopeException;

/**
 * @deprecated
 * @see \QuantaForge\Sanctum\Http\Middleware\CheckForAnyAbility
 */
class CheckForAnyScope
{
    /**
     * Handle the incoming request.
     *
     * @param  \QuantaForge\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$scopes
     * @return \QuantaForge\Http\Response
     *
     * @throws \QuantaForge\Auth\AuthenticationException|\QuantaForge\Sanctum\Exceptions\MissingScopeException
     */
    public function handle($request, $next, ...$scopes)
    {
        try {
            return (new CheckForAnyAbility())->handle($request, $next, ...$scopes);
        } catch (\QuantaForge\Sanctum\Exceptions\MissingAbilityException $e) {
            throw new MissingScopeException($e->abilities());
        }
    }
}
