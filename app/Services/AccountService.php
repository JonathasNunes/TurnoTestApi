<?php

namespace App\Services;

use App\Repositories\AccountRepository;

class AccountService
{
    protected $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function getAccountByUserId($userId)
    {
        return $this->accountRepository->findByUserId($userId);
    }

    public function updateAccountBalance($accountId, $newBalance)
    {
        return $this->accountRepository->updateBalance($accountId, $newBalance);
    }

    // Outros métodos de serviço
}
