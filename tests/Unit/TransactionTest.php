<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaction_belongs_to_account()
    {
        $user = new User([
            'name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'password' => 'secret',
            'type' => User::USER_TYPE_CUSTOMER,
        ]);
        $user->save();

        $account = new Account([
            'user_id' => $user->id,
            'balance' => 0,
        ]);
        $account->save();

        $transaction = new Transaction([
            'account_id' => $account->id,
            'amount' => 50,
            'description' => 'Deposit',
            'type' => Transaction::TRANSACTION_DEPOSIT,
        ]);
        $transaction->save();

        $this->assertInstanceOf(Account::class, $transaction->account);
        $this->assertEquals($account->id, $transaction->account->id);
    }

    public function test_transaction_type_constants()
    {
        $this->assertEquals('deposit', Transaction::TRANSACTION_DEPOSIT);
        $this->assertEquals('purchase', Transaction::TRANSACTION_PURCHASE);
    }

    public function test_purchase_transaction_cannot_exceed_account_balance()
    {
        $user = new User([
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'password' => 'secret',
            'type' => User::USER_TYPE_CUSTOMER,
        ]);
        $user->save();
    
        $account = new Account([
            'user_id' => $user->id,
            'balance' => 100, // Starting account balance
        ]);
        $account->save();

        // Attempt to create a purchase transaction with an amount exceeding the account balance
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The purchase amount cannot exceed the account balance.');

        Transaction::validateTransactionAmount([
            'account_id' => $account->id,
            'amount' => 1200, // Exceeding account balance
            'type' => Transaction::TRANSACTION_PURCHASE,
        ]);
    }
}
