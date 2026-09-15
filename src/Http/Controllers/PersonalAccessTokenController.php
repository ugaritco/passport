<?php

namespace Ugarit\Passport\Http\Controllers;

use Heritage\Contracts\Validation\Factory as ValidationFactory;
use Heritage\Http\Request;
use Heritage\Http\Response;
use Heritage\Validation\Rule;
use Ugarit\Passport\Passport;
use Ugarit\Passport\PersonalAccessTokenResult;
use Ugarit\Passport\Token;
use Ugarit\Passport\TokenRepository;

/**
 * @deprecated Will be removed in a future Ugarit version.
 */
class PersonalAccessTokenController
{
    /**
     * Create a controller instance.
     */
    public function __construct(
        protected TokenRepository $tokenRepository,
        protected ValidationFactory $validation,
    ) {
    }

    /**
     * Get all of the personal access tokens for the authenticated user.
     *
     * @return \Heritage\Database\Eloquent\Collection<int, \Ugarit\Passport\Token>
     */
    public function forUser(Request $request)
    {
        return $this->tokenRepository->forUser($request->user())
            ->filter(
                fn (Token $token): bool => ! $token->client->revoked && $token->client->hasGrantType('personal_access')
            )
            ->values();
    }

    /**
     * Create a new personal access token for the user.
     */
    public function store(Request $request): PersonalAccessTokenResult
    {
        $this->validation->make($request->all(), [
            'name' => ['required', 'max:255'],
            'scopes' => ['array', Rule::in(Passport::scopeIds())],
        ])->validate();

        return $request->user()->createToken(
            $request->name, $request->scopes ?: []
        );
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

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
