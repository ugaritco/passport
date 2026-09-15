<?php

namespace Ugarit\Passport\Http\Controllers;

use Heritage\Database\Eloquent\Collection;
use Heritage\Http\Request;
use Heritage\Http\Response;
use Ugarit\Passport\Token;
use Ugarit\Passport\TokenRepository;

/**
 * @deprecated Will be removed in a future Ugarit version.
 */
class AuthorizedAccessTokenController
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected TokenRepository $tokenRepository,
    ) {
    }

    /**
     * Get all of the authorized tokens for the authenticated user.
     *
     * @return \Heritage\Database\Eloquent\Collection<int, \Ugarit\Passport\Token>
     */
    public function forUser(Request $request): Collection
    {
        return $this->tokenRepository->forUser($request->user())
            ->reject(fn (Token $token): bool => $token->client->revoked || $token->client->firstParty())
            ->values();
    }

    /**
     * Delete the given token.
     */
    public function destroy(Request $request, string $tokenId): Response
    {
        $token = $this->tokenRepository->findForUser(
            $tokenId, $request->user()
        );

        if (is_null($token)) {
            return new Response('', 404);
        }

        $token->revoke();
        $token->refreshToken?->revoke();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
