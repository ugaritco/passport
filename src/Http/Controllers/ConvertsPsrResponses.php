<?php

namespace Ugarit\Passport\Http\Controllers;

use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Response;

trait ConvertsPsrResponses
{
    /**
     * Convert a PSR7 response to a Heritage Response.
     */
    public function convertResponse(ResponseInterface $psrResponse): Response
    {
        return (new HttpFoundationFactory)->createResponse($psrResponse);
    }
}
