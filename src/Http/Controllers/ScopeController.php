<?php

namespace Ugarit\Passport\Http\Controllers;

use Heritage\Support\Collection;
use Ugarit\Passport\Passport;

/**
 * @deprecated Will be removed in a future Ugarit version.
 */
class ScopeController
{
    /**
     * Get all of the available scopes for the application.
     *
     * @return \Heritage\Support\Collection<int, \Ugarit\Passport\Scope>
     */
    public function all(): Collection
    {
        return Passport::scopes();
    }
}
