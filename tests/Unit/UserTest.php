<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Account;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_if_user_columns_is_correct() 
    {
        $user = new User();
        
        $expected = [
            'name',
            'email',
            'password',
            'type',
        ];

        $arrayCompared = array_diff($expected, $user->getFillable());

        $this->assertEquals(0, count($arrayCompared));
    }

    public function test_user_has_account()
    {
        $user = new User([
            'name' => 'John Doe',
            'email' => 'john'.rand().rand().rand().'@example.com',
            'password' => bcrypt('secret'),
            'type' => User::USER_TYPE_CUSTOMER,
        ]);
        $user->save();

        $account = new Account([
            'balance' => 0,
            'user_id' => $user->id,
        ]);
        $account->save();

        $this->assertInstanceOf(Account::class, $user->account);
        $this->assertEquals(0, $user->account->balance);
    }

    public function test_user_has_many_transactions()
    {
        $user = new User([
            'name' => 'John Doe',
            'email' => 'john'.rand().rand().rand().'@example.com',
            'password' => bcrypt('secret'),
            'type' => User::USER_TYPE_CUSTOMER,
        ]);
        $user->save();

        $account = new Account([
            'balance' => 0,
            'user_id' => $user->id,
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

        $transactions = $account->transactions;

        $this->assertCount(2, $transactions);
        $this->assertEquals(100, $transactions[0]->amount);
        $this->assertEquals(50, $transactions[1]->amount);
    }

    public function test_user_can_have_different_types()
    {
        $userAdmin = new User([
            'name' => 'Admin User',
            'email' => 'admin'.rand().rand().rand().'@example.com',
            'password' => bcrypt('secret'),
            'type' => User::USER_TYPE_ADMIN,
        ]);
        $userAdmin->save();

        $userCustomer = new User([
            'name' => 'Customer User',
            'email' => 'john'.rand().rand().rand().'@example.com',
            'password' => bcrypt('secret'),
            'type' => User::USER_TYPE_CUSTOMER,
        ]);
        $userCustomer->save();

        $this->assertEquals(User::USER_TYPE_ADMIN, $userAdmin->type);
        $this->assertEquals(User::USER_TYPE_CUSTOMER, $userCustomer->type);
    }

    public function test_user_can_have_transactions_of_different_types()
    {
        $user = new User([
            'name' => 'John Doe',
            'email' => 'john'.rand().rand().rand().'@example.com',
            'password' => bcrypt('secret'),
            'type' => User::USER_TYPE_CUSTOMER,
        ]);
        $user->save();

        $account = new Account([
            'balance' => 0,
            'user_id' => $user->id,
        ]);
        $account->save();

        $transactionDeposit = new Transaction([
            'account_id' => $account->id,
            'amount' => 100,
            'description' => 'Deposit',
            'type' => Transaction::TRANSACTION_DEPOSIT,
        ]);
        $transactionDeposit->save();

        $transactionPurchase = new Transaction([
            'account_id' => $account->id,
            'amount' => 50,
            'description' => 'Purchase',
            'type' => Transaction::TRANSACTION_PURCHASE,
        ]);
        $transactionPurchase->save();

        $this->assertCount(2, $account->transactions);
    }

    public function test_user_can_be_found_by_email()
    {
        $email = 'jane'.rand().rand().rand().'@example.com';
        $user = new User([
            'name' => 'Jane Smith',
            'email' => $email,
            'password' => 'secret',
            'type' => User::USER_TYPE_CUSTOMER,
        ]);
        $user->save();

        $foundUser = User::where('email', $email)->first();

        $this->assertEquals($user->id, $foundUser->id);
    }
}
