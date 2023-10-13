<?php

namespace QuantaQuirk\Sanctum\Http\Middleware;

use QuantaQuirk\Auth\AuthenticationException;
use QuantaQuirk\Sanctum\Exceptions\MissingAbilityException;

class CheckForAnyAbility
{
    /**
     * Handle the incoming request.
     *
     * @param  \QuantaQuirk\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$abilities
     * @return \QuantaQuirk\Http\Response
     *
     * @throws \QuantaQuirk\Auth\AuthenticationException|\QuantaQuirk\Sanctum\Exceptions\MissingAbilityException
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
