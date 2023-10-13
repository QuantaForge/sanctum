<?php

namespace QuantaQuirk\Sanctum\Http\Controllers;

use QuantaQuirk\Http\JsonResponse;
use QuantaQuirk\Http\Request;
use QuantaQuirk\Http\Response;

class CsrfCookieController
{
    /**
     * Return an empty response simply to trigger the storage of the CSRF cookie in the browser.
     *
     * @param  \QuantaQuirk\Http\Request  $request
     * @return \QuantaQuirk\Http\Response|\QuantaQuirk\Http\JsonResponse
     */
    public function show(Request $request)
    {
        if ($request->expectsJson()) {
            return new JsonResponse(null, 204);
        }

        return new Response('', 204);
    }
}
