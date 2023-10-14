<?php

namespace QuantaForge\Sanctum\Http\Middleware;

use QuantaForge\Auth\AuthenticationException;
use QuantaForge\Sanctum\Exceptions\MissingAbilityException;

class CheckForAnyAbility
{
    /**
     * Handle the incoming request.
     *
     * @param  \QuantaForge\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$abilities
     * @return \QuantaForge\Http\Response
     *
     * @throws \QuantaForge\Auth\AuthenticationException|\QuantaForge\Sanctum\Exceptions\MissingAbilityException
     */
    public function handle($request, $next, ...$abilities)
    {
        if (! $request->user() || ! $request->user()->currentAccessToken()) {
            throw new AuthenticationException;
        }

        foreach ($abilities as $ability) {
            if ($request->user()->tokenCan($ability)) {
                return $next($request);
            }
        }

        throw new MissingAbilityException($abilities);
    }
}
