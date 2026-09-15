<?php

namespace Ugarit\Passport\Http\Controllers;

use Heritage\Http\Request;
use Ugarit\Passport\Contracts\ApprovedDeviceAuthorizationResponse;
use League\OAuth2\Server\AuthorizationServer;

class ApproveDeviceAuthorizationController
{
    use HandlesOAuthErrors, RetrievesDeviceCodeFromSession;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected AuthorizationServer $server,
    ) {
    }

    /**
     * Approve the device authorization request.
     */
    public function __invoke(
        Request $request,
        ApprovedDeviceAuthorizationResponse $response
    ): ApprovedDeviceAuthorizationResponse {
        $deviceCode = $this->getDeviceCodeFromSession($request);

        $this->withErrorHandling(fn () => $this->server->completeDeviceAuthorizationRequest(
            $deviceCode->getIdentifier(),
            $deviceCode->getUserIdentifier(),
            true
        ));

        return $response;
    }
}
