<?php

namespace App\Services;

use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;
use App\Services\AccountService;

class UserService
{
    protected $userRepository;
    protected $accountService;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->accountService = new AccountService(new AccountRepository);
    }

    public function createUser(array $data)
    {
        $user = $this->userRepository->create($data);

        //If success, create account with 0 balance
        if (isset($user->id)) {
            return $this->accountService->createAccount($user);
        }
    }

    public function getUserById($id)
    {
        return $this->userRepository->findById($id);
    }
}
