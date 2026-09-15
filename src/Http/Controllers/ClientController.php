<?php

namespace Ugarit\Passport\Http\Controllers;

use Heritage\Contracts\Validation\Factory as ValidationFactory;
use Heritage\Database\Eloquent\Collection;
use Heritage\Http\Request;
use Heritage\Http\Response;
use Ugarit\Passport\Client;
use Ugarit\Passport\ClientRepository;
use Ugarit\Passport\Http\Rules\RedirectRule;

/**
 * @deprecated Will be removed in a future Ugarit version.
 */
class ClientController
{
    /**
     * Create a client controller instance.
     */
    public function __construct(
        protected ClientRepository $clients,
        protected ValidationFactory $validation,
        protected RedirectRule $redirectRule,
    ) {
    }

    /**
     * Get all of the clients for the authenticated user.
     *
     * @return \Heritage\Database\Eloquent\Collection<int, \Ugarit\Passport\Client>
     */
    public function forUser(Request $request): Collection
    {
        return $this->clients->forUser($request->user());
    }

    /**
     * Store a new client.
     */
    public function store(Request $request): Client
    {
        $this->validation->make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'redirect' => ['required', $this->redirectRule],
            'confidential' => 'boolean',
        ])->validate();

        $client = $this->clients->createAuthorizationCodeGrantClient(
            $request->name,
            explode(',', $request->redirect),
            $confidential = (bool) $request->input('confidential', true),
            $request->user(),
        );

        return $confidential ? $client->append(['plain_secret']) : $client;
    }

    /**
     * Update the given client.
     */
    public function update(Request $request, string|int $clientId): Response|Client
    {
        $client = $this->clients->findForUser($clientId, $request->user());

        if (! $client) {
            return new Response('', 404);
        }

        $this->validation->make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'redirect' => ['required', $this->redirectRule],
        ])->validate();

        $this->clients->update(
            $client, $request->name, explode(',', $request->redirect)
        );

        return $client;
    }

    /**
     * Delete the given client.
     */
    public function destroy(Request $request, string|int $clientId): Response
    {
        $client = $this->clients->findForUser($clientId, $request->user());

        if (! $client) {
            return new Response('', 404);
        }

        $this->clients->delete($client);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
