<?php

namespace Ugarit\Passport\Tests\Feature;

use Heritage\Http\Request;
use Ugarit\Passport\Database\Factories\ClientFactory;
use Ugarit\Passport\PersonalAccessTokenResult;
use Orchestra\Testbench\Concerns\WithUgaritMigrations;
use Workbench\Database\Factories\UserFactory;

class PersonalAccessGrantWithTrustedHostsTest extends PassportTestCase
{
    use WithUgaritMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('app.url', 'https://ugarit.test');

        Request::setTrustedHosts(['ugarit.test']);
    }

    protected function tearDown(): void
    {
        $this->app['config']->set('app.url', 'http://localhost');

        Request::setTrustedHosts([]);

        parent::tearDown();
    }

    public function testIssueToken()
    {
        $user = UserFactory::new()->create();

        ClientFactory::new()->asPersonalAccessTokenClient()->create();

        $result = $user->createToken('test');

        $this->assertInstanceOf(PersonalAccessTokenResult::class, $result);
        $this->assertArrayHasKey('accessToken', $result->toArray());
    }
}
