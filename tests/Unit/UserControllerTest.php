<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\UserService;
use App\Repositories\UserRepository;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testRegisterUser()
    {
        // $userData = [
        //     'username' => $this->faker->unique()->userName,
        //     'password' => 'secret123',
        // ];

        // $userService = new UserService(new UserRepository());
        // $userController = new UserController($userService);

        // $response = $userController->register($userData);

        // $response->assertStatus(201);
        // $this->assertDatabaseHas('users', [
        //     'username' => $userData['username'],
        // ]);
    }

    // Outros testes de controller
}
