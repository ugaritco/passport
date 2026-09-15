<?php

namespace Ugarit\Passport\Tests\Feature;

use Heritage\Foundation\Auth\User as Authenticatable;
use Ugarit\Passport\Contracts\OAuthenticatable;
use Ugarit\Passport\HasApiTokens;
use Orchestra\Testbench\Concerns\WithUgaritMigrations;
use Workbench\Database\Factories\UserFactory;

class HasApiTokensTest extends PassportTestCase
{
    use WithUgaritMigrations;

    public function testGetProvider()
    {
        config([
            'auth.providers.admins' => ['driver' => 'eloquent', 'model' => AdminHasApiTokensStub::class],
            'auth.guards.api-admins' => ['driver' => 'passport', 'provider' => 'admins'],
            'auth.providers.customers' => ['driver' => 'eloquent', 'model' => CustomerHasApiTokensStub::class],
            'auth.guards.api-customers' => ['driver' => 'passport', 'provider' => 'customers'],
        ]);

        $this->assertSame('users', UserFactory::new()->create()->getProviderName());
        $this->assertSame('admins', (new AdminHasApiTokensStub)->getProviderName());
        $this->assertSame('customers', (new CustomerHasApiTokensStub)->getProviderName());
    }
}

class AdminHasApiTokensStub extends Authenticatable implements OAuthenticatable
{
    use HasApiTokens;
}

class CustomerHasApiTokensStub extends Authenticatable implements OAuthenticatable
{
    use HasApiTokens;
}
