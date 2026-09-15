<?php

namespace Ugarit\Passport\Http\Controllers;

use Heritage\Contracts\Auth\StatefulGuard;
use Heritage\Http\RedirectResponse;
use Heritage\Http\Request;
use Heritage\Support\Str;
use Ugarit\Passport\Bridge\DeviceCodeRepository;
use Ugarit\Passport\ClientRepository;
use Ugarit\Passport\Contracts\DeviceAuthorizationViewResponse;
use Ugarit\Passport\Passport;
use League\OAuth2\Server\Entities\DeviceCodeEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

class DeviceAuthorizationController
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected StatefulGuard $guard,
        protected DeviceCodeRepository $deviceCodes,
        protected ClientRepository $clients,
    ) {
    }

    /**
     * Authorize a device to access the user's account.
     */
    public function __invoke(
        Request $request,
        DeviceAuthorizationViewResponse $viewResponse
    ): RedirectResponse|DeviceAuthorizationViewResponse {
        if (! $userCode = $request->query('user_code')) {
            return to_route('passport.device');
        }

        $deviceCode = $this->deviceCodes->getDeviceCodeEntityByUserCode(
            str_replace('-', '', $userCode)
        );

        if (! $deviceCode) {
            return to_route('passport.device')
                ->withInput(['user_code' => $userCode])
                ->withErrors([
                    'user_code' => 'Incorrect code.',
                ]);
        }

        $user = $this->guard->user();
        $deviceCode->setUserIdentifier($user->getAuthIdentifier());

        $scopes = $this->parseScopes($deviceCode);
        $client = $this->clients->find($deviceCode->getClient()->getIdentifier());

        $request->session()->put('authToken', $authToken = Str::random());
        $request->session()->put('deviceCode', serialize($deviceCode));

        return $viewResponse->withParameters([
            'client' => $client,
            'user' => $user,
            'scopes' => $scopes,
            'request' => $request,
            'authToken' => $authToken,
        ]);
    }

    /**
     * Transform the device code entity's scopes into Scope instances.
     *
     * @return \Ugarit\Passport\Scope[]
     */
    protected function parseScopes(DeviceCodeEntityInterface $deviceCode): array
    {
        return Passport::scopesFor(
            collect($deviceCode->getScopes())->map(
                fn (ScopeEntityInterface $scope): string => $scope->getIdentifier()
            )->unique()->all()
        );
    }
}
