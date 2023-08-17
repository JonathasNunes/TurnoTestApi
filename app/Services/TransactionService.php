<?php

namespace App\Services;

use App\Services\AccountService;
use App\Repositories\TransactionRepository;

class TransactionService
{
    protected $transactionRepository;
    protected $accountService;

    public function __construct(TransactionRepository $transactionRepository, AccountService $accountService)
    {
        $this->transactionRepository = $transactionRepository;
        $this->accountService = $accountService;
    }

    public function createTransaction(array $data)
    {
        $account = $this->accountService->getAccountByUserId($data['user_id']);

        if ($account->balance < $data['amount']) {
            throw new \Exception('Insufficient balance');
        }

        // Criar a transação pendente
        $data['approved'] = false;
        $transaction = $this->transactionRepository->create($data);

        return $transaction;
    }

    public function approveTransaction($transactionId)
    {
        // Lógica para aprovar a transação pelo administrador
        $this->transactionRepository->updateApproval($transactionId, true);
    }

    // Outros métodos de serviço
}
