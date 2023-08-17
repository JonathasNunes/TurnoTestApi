<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AccountService;
use App\Repositories\AccountRepository;
use App\Http\Controllers\AccountController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Domain\User\User;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testGetAccount()
    {
        // $user = factory(User::class)->create();
        // $this->actingAs($user);

        // $accountService = new AccountService(new AccountRepository());
        // $accountController = new AccountController($accountService);

        // $response = $accountController->getAccount(request());

        // $response->assertStatus(200);
        // $this->assertEquals($user->id, $response->json('user_id'));
    }

    // Outros testes de controller
}
