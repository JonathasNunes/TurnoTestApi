<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AccountRepository;

class AccountService
{
    protected $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function createAccount(User $data)
    {
        $account = $this->accountRepository->findByUserId($data->id);

        if ($account) {
            throw new \Exception('Conta já existente');
        }

        $newBalance['user_id'] = $data->id;
        $newBalance['balance'] = 0.00;
        
        return $this->accountRepository->create($newBalance);
    }

    public function getAccountByUserId($userId)
    {
        return $this->accountRepository->findWithTransactionsByUserId($userId);
    }

    public function updateAccountBalance($accountId, $newBalance)
    {
        return $this->accountRepository->updateBalance($accountId, $newBalance);
    }
}
