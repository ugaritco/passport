<?php

namespace Ugarit\Passport;

use Heritage\Contracts\Auth\Authenticatable;
use Heritage\Database\Eloquent\Collection;
use Heritage\Support\Facades\Date;

/**
 * @deprecated Will be removed in a future Ugarit version.
 */
class TokenRepository
{
    /**
     * Get a token by the given user ID and token ID.
     *
     * @deprecated Use $user->tokens()->find()
     *
     * @param  \Ugarit\Passport\Contracts\OAuthenticatable  $user
     */
    public function findForUser(string $id, Authenticatable $user): ?Token
    {
        return $user->tokens()
            ->with('client')
            ->where('revoked', false)
            ->where('expires_at', '>', Date::now())
            ->find($id);
    }

    /**
     * Get the token instances for the given user ID.
     *
     * @deprecated Use $user->tokens()
     *
     * @param  \Ugarit\Passport\Contracts\OAuthenticatable  $user
     * @return \Heritage\Database\Eloquent\Collection<int, \Ugarit\Passport\Token>
     */
    public function forUser(Authenticatable $user): Collection
    {
        return $user->tokens()
            ->with('client')
            ->where('revoked', false)
            ->where('expires_at', '>', Date::now())
            ->get();
    }
}
