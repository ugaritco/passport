<?php

namespace Ugarit\Passport\Http\Controllers;

use Exception;
use Heritage\Http\Request;
use Ugarit\Passport\Exceptions\InvalidAuthTokenException;
use League\OAuth2\Server\Entities\DeviceCodeEntityInterface;

trait RetrievesDeviceCodeFromSession
{
    /**
     * Get the device code from the session.
     *
     * @throws \Ugarit\Passport\Exceptions\InvalidAuthTokenException
     * @throws \Exception
     */
    protected function getDeviceCodeFromSession(Request $request): DeviceCodeEntityInterface
    {
        if ($request->isNotFilled('auth_token') ||
            $request->session()->pull('authToken') !== $request->input('auth_token')) {
            $request->session()->forget(['authToken', 'deviceCode']);

            throw InvalidAuthTokenException::different();
        }

        $deviceCode = $request->session()->pull('deviceCode')
            ?? throw new Exception('Device code was not present in the session.');

        return unserialize($deviceCode, ['allowed_classes' => [
            \Ugarit\Passport\Bridge\DeviceCode::class,
            \Ugarit\Passport\Bridge\Client::class,
            \Ugarit\Passport\Bridge\Scope::class,
            \DateTimeImmutable::class,
        ]]);
    }
}
