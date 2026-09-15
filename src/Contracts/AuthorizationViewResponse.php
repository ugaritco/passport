<?php

namespace Ugarit\Passport\Contracts;

use Heritage\Contracts\Support\Responsable;

interface AuthorizationViewResponse extends Responsable
{
    /**
     * Specify the parameters that should be passed to the view.
     *
     * @param  array<string, mixed>  $parameters
     */
    public function withParameters(array $parameters = []): static;
}
