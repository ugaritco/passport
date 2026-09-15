<?php

namespace Ugarit\Passport;

use Heritage\Contracts\Auth\Authenticatable;
use Heritage\Contracts\Auth\UserProvider;

class PassportUserProvider implements UserProvider
{
    /**
     * Create a new passport user provider.
     */
    public function __construct(
        protected UserProvider $provider,
        protected string $providerName,
    ) {
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  string|int  $identifier
     * @return \Ugarit\Passport\Contracts\OAuthenticatable|null
     */
    public function retrieveById($identifier): ?Authenticatable
    {
        return $this->provider->retrieveById($identifier);
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  string|int  $identifier
     * @param  string  $token
     * @return \Ugarit\Passport\Contracts\OAuthenticatable|null
     */
    public function retrieveByToken($identifier, #[\SensitiveParameter] $token): ?Authenticatable
    {
        return $this->provider->retrieveByToken($identifier, $token);
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Ugarit\Passport\Contracts\OAuthenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, #[\SensitiveParameter] $token): void
    {
        $this->provider->updateRememberToken($user, $token);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Ugarit\Passport\Contracts\OAuthenticatable|null
     */
    public function retrieveByCredentials(#[\SensitiveParameter] array $credentials): ?Authenticatable
    {
        return $this->provider->retrieveByCredentials($credentials);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Ugarit\Passport\Contracts\OAuthenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, #[\SensitiveParameter] array $credentials): bool
    {
        return $this->provider->validateCredentials($user, $credentials);
    }

    /**
     * Rehash the user's password if required and supported.
     *
     * @param  \Ugarit\Passport\Contracts\OAuthenticatable  $user
     * @param  array  $credentials
     * @param  bool  $force
     * @return void
     */
    public function rehashPasswordIfRequired(Authenticatable $user, #[\SensitiveParameter] array $credentials, bool $force = false): void
    {
        $this->provider->rehashPasswordIfRequired($user, $credentials, $force);
    }

    /**
     * Get the name of the user provider.
     */
    public function getProviderName(): string
    {
        return $this->providerName;
    }
}
