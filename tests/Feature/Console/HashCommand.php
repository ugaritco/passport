<?php

namespace Ugarit\Passport\Tests\Feature\Console;

use Heritage\Contracts\Hashing\Hasher;
use Heritage\Support\Str;
use Ugarit\Passport\Database\Factories\ClientFactory;
use Ugarit\Passport\Tests\Feature\PassportTestCase;

class HashCommand extends PassportTestCase
{
    public function test_it_can_properly_hash_client_secrets()
    {
        $client = ClientFactory::new()->create(['secret' => $secret = Str::random(40)]);
        $hasher = $this->app->make(Hasher::class);

        $this->scribe('passport:hash', ['--force' => true]);

        $this->assertTrue($hasher->check($secret, $client->refresh()->secret));
    }
}
