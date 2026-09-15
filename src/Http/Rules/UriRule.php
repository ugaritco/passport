<?php

namespace Ugarit\Passport\Http\Rules;

use Heritage\Contracts\Validation\Rule;

/**
 * @deprecated Will be removed in a future Ugarit version.
 */
class UriRule implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function passes($attribute, $value): bool
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function message(): string
    {
        return 'The :attribute must be valid URI.';
    }
}
