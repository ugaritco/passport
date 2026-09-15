<?php

namespace Ugarit\Passport\Tests\Feature\Attributes;

use Heritage\Support\Facades\Route;
use Ugarit\Passport\Attributes\AuthorizeToken;
use Orchestra\Testbench\Attributes\RequiresUgarit;
use Orchestra\Testbench\TestCase;

#[RequiresUgarit('13.0.0')]
class AuthorizeTokenTest extends TestCase
{
    public function testItAppliesCheckTokenMiddleware(): void
    {
        $route = Route::get('/test', [AuthorizeTokenControllerTest::class, 'index']);

        $this->assertSame([
            'Ugarit\Passport\Http\Middleware\CheckToken:all',
            'Ugarit\Passport\Http\Middleware\CheckToken:only-index',
            'Ugarit\Passport\Http\Middleware\CheckToken:also-index',
        ], $route->controllerMiddleware());

        $route = Route::get('/test', [AuthorizeTokenControllerTest::class, 'show']);

        $this->assertSame([
            'Ugarit\Passport\Http\Middleware\CheckToken:all',
            'Ugarit\Passport\Http\Middleware\CheckToken:except-index',
        ], $route->controllerMiddleware());
    }

    public function testItAppliesCheckTokenForAnyScopeMiddleware(): void
    {
        $route = Route::get('/test', [AuthorizeTokenControllerTest::class, 'store']);

        $this->assertSame([
            'Ugarit\Passport\Http\Middleware\CheckToken:all',
            'Ugarit\Passport\Http\Middleware\CheckToken:except-index',
            'Ugarit\Passport\Http\Middleware\CheckTokenForAnyScope:only-store,something-else',
        ], $route->controllerMiddleware());
    }
}

#[AuthorizeToken('all')]
#[AuthorizeToken('only-index', only: ['index'])]
#[AuthorizeToken('except-index', except: ['index'])]
class AuthorizeTokenControllerTest
{
    #[AuthorizeToken('also-index')]
    public function index(): void
    {
    }

    public function show(): void
    {
    }

    #[AuthorizeToken(['only-store', 'something-else'], anyScope: true)]
    public function store(): void
    {
    }
}
