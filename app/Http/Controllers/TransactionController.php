<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
// use App\Repositories\AccountRepository;
// use App\Services\AccountService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = $request->user();

            $transactionData = $this->validateTransactionData($request);
            $transactionData = $this->prepareTransactionData($transactionData, $user);

            $transaction = $this->transactionService->createTransaction($transactionData);

            return response()->json($transaction, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Validate transaction data from the request.
     */
    protected function validateTransactionData(Request $request)
    {
        return $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'image_name' => 'required|image|mimes:jpeg,png,jpg,gif|max:12048', // Adjust mime types and max size
        ]);
    }

    /**
     * Prepare transaction data for storage.
     */
    protected function prepareTransactionData(array $transactionData, $user)
    {
        $account = $user->account;
        $transactionData['account_id'] = $account->id;
        $transactionData['type'] = Transaction::TRANSACTION_DEPOSIT;
        $transactionData['approval'] = Transaction::TRANSACTION_PENDING;

        $file = $transactionData['image_name'];
        $imageName = time() . '.' . $file->extension();
        $imagePath = public_path('/files');
        $file->move($imagePath, $imageName);
        $transactionData['image_url'] = $imageName;

        return $transactionData;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        try {
            $updatedTransaction = $this->transactionService->updateApproval($request);

            if ($updatedTransaction) {
                $newBalance = $this->calculateNewBalance($updatedTransaction);
                $this->updateAccountBalance($updatedTransaction->account, $newBalance);
            }

            return response()->json($updatedTransaction, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function calculateNewBalance(Transaction $transaction): float
    {
        if ($transaction->type === Transaction::TRANSACTION_PURCHASE) {
            if ($transaction->account->balance >= $transaction->amount) {
                return $transaction->account->balance - $transaction->amount;
            }
            throw new \Exception('Insufficient balance');
        }

        if ($transaction->type === Transaction::TRANSACTION_DEPOSIT) {
            return $transaction->account->balance + $transaction->amount;
        }

        throw new \Exception('Invalid transaction type');
    }

    private function updateAccountBalance(Account $account, float $newBalance): void
    {
        $account->balance = $newBalance;
        $account->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
