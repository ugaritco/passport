<?php

namespace Ugarit\Passport\Http\Middleware;

use Ugarit\Passport\Contracts\ScopeAuthorizable;
use Ugarit\Passport\Exceptions\MissingScopeException;

class CheckToken extends ValidateToken
{
    /**
     * Determine if the token has all the given scopes.
     *
     * @throws \Ugarit\Passport\Exceptions\MissingScopeException
     */
    protected function validate(ScopeAuthorizable $token, string ...$params): void
    {
        foreach ($params as $scope) {
            if ($token->cant($scope)) {
                throw new MissingScopeException($scope);
            }
        }
    }
}
