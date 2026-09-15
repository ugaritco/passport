<?php

namespace Ugarit\Passport;

use Heritage\Database\Eloquent\Model;
use Heritage\Database\Eloquent\Relations\BelongsTo;
use Heritage\Database\Eloquent\Relations\HasOne;

class Token extends Model
{
    use ResolvesInheritedScopes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oauth_access_tokens';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The guarded attributes on the model.
     *
     * @var array<string>|bool
     */
    protected $guarded = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scopes' => 'array',
        'revoked' => 'bool',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the client that the token belongs to.
     *
     * @return \Heritage\Database\Eloquent\Relations\BelongsTo<\Ugarit\Passport\Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Passport::clientModel());
    }

    /**
     * Get the refresh token associated with the token.
     *
     * @return \Heritage\Database\Eloquent\Relations\HasOne<\Ugarit\Passport\RefreshToken, $this>
     */
    public function refreshToken(): HasOne
    {
        return $this->hasOne(Passport::refreshTokenModel(), 'access_token_id');
    }

    /**
     * Get the user that the token belongs to.
     *
     * @deprecated Will be removed in a future Ugarit version.
     *
     * @return \Heritage\Database\Eloquent\Relations\BelongsTo<\Heritage\Foundation\Auth\User, $this>
     */
    public function user(): BelongsTo
    {
        $provider = $this->client->provider ?: config('auth.guards.api.provider');

        $model = config('auth.providers.'.$provider.'.model');

        return $this->belongsTo($model, 'user_id', (new $model)->getAuthIdentifierName());
    }

    /**
     * Determine if the token has a given scope.
     */
    public function can(string $scope): bool
    {
        if (empty($this->scopes)) {
            return false;
        }

        return in_array('*', $this->scopes)
            || $this->scopeExistsIn($scope, $this->scopes);
    }

    /**
     * Determine if the token is missing a given scope.
     */
    public function cant(string $scope): bool
    {
        return ! $this->can($scope);
    }

    /**
     * Revoke the token instance.
     */
    public function revoke(): bool
    {
        return $this->forceFill(['revoked' => true])->save();
    }

    /**
     * Get the current connection name for the model.
     */
    public function getConnectionName(): ?string
    {
        return parent::getConnectionName() ?? config('passport.connection');
    }
}
