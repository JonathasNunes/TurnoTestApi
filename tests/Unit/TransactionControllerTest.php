<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TransactionService;
use App\Services\AccountService;
use App\Http\Controllers\TransactionController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Account;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreateTransaction()
    {
        // $user = factory(User::class)->create();
        // $account = factory(Account::class)->create(['user_id' => $user->id]);
        // $this->actingAs($user);

        // $transactionData = [
        //     'user_id' => $user->id,
        //     'amount' => 100,
        //     'description' => 'Test Transaction',
        // ];

        // $transactionService = new TransactionService(new TransactionRepository(), new AccountService(new AccountRepository()));
        // $transactionController = new TransactionController($transactionService);

        // $response = $transactionController->createTransaction($transactionData);

        // $response->assertStatus(201);
        // $this->assertDatabaseHas('transactions', [
        //     'user_id' => $user->id,
        //     'amount' => 100,
        //     'description' => 'Test Transaction',
        //     'approved' => false,
        // ]);
    }

    // Outros testes de controller
}
