<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $data)
    {
        $existingUser = $this->userRepository->findByUsername($data['username']);
        if ($existingUser) {
            throw new \Exception('Username already exists');
        }

        $data['password'] = bcrypt($data['password']);
        $data['balance'] = 0;

        return $this->userRepository->create($data);
    }

    public function getUserById($id)
    {
        return $this->userRepository->findById($id);
    }
}
