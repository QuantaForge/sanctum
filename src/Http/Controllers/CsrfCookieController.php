<?php

namespace QuantaForge\Sanctum\Http\Controllers;

use QuantaForge\Http\JsonResponse;
use QuantaForge\Http\Request;
use QuantaForge\Http\Response;

class CsrfCookieController
{
    /**
     * Return an empty response simply to trigger the storage of the CSRF cookie in the browser.
     *
     * @param  \QuantaForge\Http\Request  $request
     * @return \QuantaForge\Http\Response|\QuantaForge\Http\JsonResponse
     */
    public function show(Request $request)
    {
        if ($request->expectsJson()) {
            return new JsonResponse(null, 204);
        }

        return new Response('', 204);
    }
}
