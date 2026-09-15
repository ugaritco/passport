<?php

namespace Ugarit\Passport\Http\Middleware;

use Ugarit\Passport\AccessToken;
use Ugarit\Passport\Contracts\ScopeAuthorizable;
use Ugarit\Passport\Exceptions\AuthenticationException;
use Ugarit\Passport\Exceptions\MissingScopeException;

class EnsureClientIsResourceOwner extends ValidateToken
{
    /**
     * Determine if the token's client is the resource owner and has all the given scopes.
     *
     * @throws \Ugarit\Passport\Exceptions\AuthenticationException|\Ugarit\Passport\Exceptions\MissingScopeException
     */
    protected function validate(ScopeAuthorizable $token, string ...$params): void
    {
        if (
            $token instanceof AccessToken
            && ! is_null($token->oauth_user_id)
            && $token->oauth_user_id !== $token->oauth_client_id
        ) {
            throw new AuthenticationException;
        }

        foreach ($params as $scope) {
            if ($token->cant($scope)) {
                throw new MissingScopeException($scope);
            }
        }
    }
}
