<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    protected $transactionModel;

    public function __construct(Transaction $transactionModel)
    {
        $this->transactionModel = $transactionModel;
    }

    public function create(array $data)
    {
        return $this->transactionModel->create($data);
    }

    public function findById($id)
    {
        return Transaction::find($id);
    }

    public function findByAccountId($accountId)
    {
        return Transaction::where('account_id', $accountId)->get();
    }

    public function updateApproval($transactionId, $s) 
    {

    }
}