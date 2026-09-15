<?php

namespace Ugarit\Passport\Http\Controllers;

use Heritage\Http\RedirectResponse;
use Heritage\Http\Request;
use Ugarit\Passport\Contracts\DeviceUserCodeViewResponse;

class DeviceUserCodeController
{
    /**
     * Show the form for entering the user code.
     */
    public function __invoke(
        Request $request,
        DeviceUserCodeViewResponse $viewResponse
    ): RedirectResponse|DeviceUserCodeViewResponse {
        if ($userCode = $request->query('user_code')) {
            return to_route('passport.device.authorizations.authorize', [
                'user_code' => $userCode,
            ]);
        }

        return $viewResponse->withParameters([
            'request' => $request,
        ]);
    }
}
