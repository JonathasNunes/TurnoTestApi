<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Account;
use App\Models\User;
use App\Models\Transaction;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_belongs_to_user()
    {
        $user = new User([
            'name' => 'John Doe',
            'email' => 'john'.rand().rand().rand().'@example.com',
            'password' => bcrypt('secret'),
            'type' => User::USER_TYPE_CUSTOMER,
        ]);
        $user->save();

        $account = new Account([
            'user_id' => $user->id,
            'balance' => 0,
        ]);
        $account->save();

        $this->assertInstanceOf(User::class, $account->user);
        $this->assertEquals($user->id, $account->user->id);
    }

    public function test_account_has_many_transactions()
    {
        $user = new User([
            'name' => 'Jane Smith',
            'email' => 'jane'.rand().rand().rand().'@example.com',
            'password' => bcrypt('secret'),
            'type' => User::USER_TYPE_CUSTOMER,
        ]);
        $user->save();

        $account = new Account([
            'user_id' => $user->id,
            'balance' => 0,
        ]);
        $account->save();

        $transaction1 = new Transaction([
            'account_id' => $account->id,
            'amount' => 100,
            'description' => 'Deposit',
            'type' => Transaction::TRANSACTION_DEPOSIT,
        ]);
        $transaction1->save();
        
        $transaction2 = new Transaction([
            'account_id' => $account->id,
            'amount' => 50,
            'description' => 'Purchase',
            'type' => Transaction::TRANSACTION_PURCHASE,
        ]);
        $transaction2->save();

        $this->assertCount(2, $account->transactions);
    }
}
