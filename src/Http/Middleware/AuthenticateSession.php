<?php

namespace QuantaQuirk\Sanctum\Http\Middleware;

use Closure;
use QuantaQuirk\Auth\AuthenticationException;
use QuantaQuirk\Auth\SessionGuard;
use QuantaQuirk\Contracts\Auth\Factory as AuthFactory;
use QuantaQuirk\Http\Request;
use QuantaQuirk\Support\Arr;
use QuantaQuirk\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateSession
{
    /**
     * The authentication factory implementation.
     *
     * @var \QuantaQuirk\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \QuantaQuirk\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\QuantaQuirk\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws \QuantaQuirk\Auth\AuthenticationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->hasSession() || ! $request->user()) {
            return $next($request);
        }

        $guards = Collection::make(Arr::wrap(config('sanctum.guard')))
            ->mapWithKeys(fn ($guard) => [$guard => $this->auth->guard($guard)])
            ->filter(fn ($guard) => $guard instanceof SessionGuard);

        $shouldLogout = $guards->filter(
            fn ($guard, $driver) => $request->session()->has('password_hash_'.$driver)
        )->filter(
            fn ($guard, $driver) => $request->session()->get('password_hash_'.$driver) !==
                                    $request->user()->getAuthPassword()
        );

        if ($shouldLogout->isNotEmpty()) {
            $shouldLogout->each->logoutCurrentDevice();

            $request->session()->flush();

            throw new AuthenticationException('Unauthenticated.', [...$shouldLogout->keys()->all(), 'sanctum']);
        }

        return tap($next($request), function () use ($request, $guards) {
            if (! is_null($request->user())) {
                $this->storePasswordHashInSession($request, $guards->keys()->first());
            }
        });
    }

    /**
     * Store the user's current password hash in the session.
     *
     * @param  \QuantaQuirk\Http\Request  $request
     * @param  string  $guard
     * @return void
     */
    protected function storePasswordHashInSession($request, string $guard)
    {
        if (! $request->user()) {
            return;
        }

        $request->session()->put([
            "password_hash_{$guard}" => $request->user()->getAuthPassword(),
        ]);
    }
}
