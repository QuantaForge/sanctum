<?php

namespace QuantaQuirk\Sanctum\Http\Middleware;

use QuantaQuirk\Routing\Pipeline;
use QuantaQuirk\Support\Collection;
use QuantaQuirk\Support\Str;

class EnsureFrontendRequestsAreStateful
{
    /**
     * Handle the incoming requests.
     *
     * @param  \QuantaQuirk\Http\Request  $request
     * @param  callable  $next
     * @return \QuantaQuirk\Http\Response
     */
    public function handle($request, $next)
    {
        $this->configureSecureCookieSessions();

        return (new Pipeline(app()))->send($request)->through(
            static::fromFrontend($request) ? $this->frontendMiddleware() : []
        )->then(function ($request) use ($next) {
            return $next($request);
        });
    }

    /**
     * Configure secure cookie sessions.
     *
     * @return void
     */
    protected function configureSecureCookieSessions()
    {
        config([
            'session.http_only' => true,
            'session.same_site' => 'lax',
        ]);
    }

    /**
     * Get the middleware that should be applied to requests from the "frontend".
     *
     * @return array
     */
    protected function frontendMiddleware()
    {
        $middleware = array_values(array_filter(array_unique([
            config('sanctum.middleware.encrypt_cookies', \QuantaQuirk\Cookie\Middleware\EncryptCookies::class),
            \QuantaQuirk\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \QuantaQuirk\Session\Middleware\StartSession::class,
            config('sanctum.middleware.validate_csrf_token'),
            config('sanctum.middleware.verify_csrf_token', \QuantaQuirk\Foundation\Http\Middleware\VerifyCsrfToken::class),
            config('sanctum.middleware.authenticate_session'),
        ])));

        array_unshift($middleware, function ($request, $next) {
            $request->attributes->set('sanctum', true);

            return $next($request);
        });

        return $middleware;
    }

    /**
     * Determine if the given request is from the first-party application frontend.
     *
     * @param  \QuantaQuirk\Http\Request  $request
     * @return bool
     */
    public static function fromFrontend($request)
    {
        $domain = $request->headers->get('referer') ?: $request->headers->get('origin');

        if (is_null($domain)) {
            return false;
        }

        $domain = Str::replaceFirst('https://', '', $domain);
        $domain = Str::replaceFirst('http://', '', $domain);
        $domain = Str::endsWith($domain, '/') ? $domain : "{$domain}/";

        $stateful = array_filter(config('sanctum.stateful', []));

        return Str::is(Collection::make($stateful)->map(function ($uri) {
            return trim($uri).'/*';
        })->all(), $domain);
    }
}
