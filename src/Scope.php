<?php

namespace Ugarit\Passport;

use Heritage\Contracts\Support\Arrayable;
use Heritage\Contracts\Support\Jsonable;

/**
 * @implements \Heritage\Contracts\Support\Arrayable<string, string>
 */
class Scope implements Arrayable, Jsonable
{
    /**
     * Create a new scope instance.
     */
    public function __construct(
        public string $id,
        public string $description,
    ) {
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
