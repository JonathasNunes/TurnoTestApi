<?php

namespace App\Services;

use App\Models\Transaction;
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
        $transaction = $this->transactionRepository->create($data);
        return $transaction;
    }

    public function save(Transaction $transaction) 
    {
        return $transaction->save();
    }

    public function updateApproval($transaction)
    {
        return $this->transactionRepository->updateApproval($transaction);
    }
}
