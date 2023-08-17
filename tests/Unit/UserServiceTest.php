<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateUser()
    {
        $userRepository = new UserRepository();
        $userService = new UserService($userRepository);

        $userData = [
            'username' => 'john_doe',
            'password' => 'secret123',
        ];

        $user = $userService->createUser($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'username' => 'john_doe',
        ]);
    }

    public function testGetUserById()
    {
        // $user = factory(User::class)->create();

        // $userRepository = new UserRepository();
        // $userService = new UserService($userRepository);

        // $foundUser = $userService->getUserById($user->id);

        // $this->assertInstanceOf(User::class, $foundUser);
        // $this->assertEquals($user->id, $foundUser->id);
    }

}
