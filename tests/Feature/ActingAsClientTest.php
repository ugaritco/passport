<?php

namespace Ugarit\Passport\Tests\Feature;

use Heritage\Contracts\Routing\Registrar;
use Ugarit\Passport\Client;
use Ugarit\Passport\Http\Middleware\CheckToken;
use Ugarit\Passport\Http\Middleware\CheckTokenForAnyScope;
use Ugarit\Passport\Passport;

class ActingAsClientTest extends PassportTestCase
{
    public function testActingAsClientWhenTheRouteIsProtectedByCheckTokenMiddleware()
    {
        $this->withoutExceptionHandling();

        /** @var Registrar $router */
        $router = $this->app->make(Registrar::class);

        $router->get('/foo', function () {
            return 'bar';
        })->middleware(CheckToken::class);

        Passport::actingAsClient(new Client());

        $response = $this->get('/foo');
        $response->assertSuccessful();
        $response->assertSee('bar');
    }

    public function testActingAsClientWhenTheRouteIsProtectedByCheckTokenForAnyScope()
    {
        $this->withoutExceptionHandling();

        /** @var Registrar $router */
        $router = $this->app->make(Registrar::class);

        $router->get('/foo', function () {
            return 'bar';
        })->middleware(CheckTokenForAnyScope::class.':testFoo');

        Passport::actingAsClient(new Client(), ['testFoo']);

        $response = $this->get('/foo');
        $response->assertSuccessful();
        $response->assertSee('bar');
    }

    public function testActingAsClientSetsTheClientOnTheGuard()
    {
        Passport::actingAsClient($client = new Client());

        $this->assertSame($client, app('auth')->client());
    }
}
