<?php

namespace App\Repositories;

use App\Models\Account;

class AccountRepository
{
    public function create($data)
    {
        return Account::create($data);
    }

    public function findById($id)
    {
        return Account::find($id);
    }

    public function findByUserId($userId)
    {
        return Account::where('user_id', $userId)->first();
    }

    public function findWithTransactionsByUserId(int $userId) 
    {
        $results = Account::with(['transactions'])->where('user_id', $userId);
        return $results->get();     
    }

    public function update($id, $data)
    {
        $account = $this->findById($id);

        if ($account) {
            $account->update($data);
            return $account;
        }

        return null;
    }

    public function updateBalance($accountId, $newBalance)
    {
        return Account::where('id', $accountId)->update(['balance' => $newBalance]);
    }
}