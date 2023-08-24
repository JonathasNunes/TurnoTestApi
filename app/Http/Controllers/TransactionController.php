<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\User;
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

    public function purchase(Request $request) {
        
        try{
            $user = $request->user();
            $transactionData = $this->validateTransactionData($request, Transaction::TRANSACTION_PURCHASE);
            $transactionData = $this->prepareTransactionData($transactionData, $user, Transaction::TRANSACTION_PURCHASE);
            
            $transaction = new Transaction($transactionData);
            $newBalance = $this->calculateNewBalance($transaction);
           
            $transaction->approval = Transaction::TRANSACTION_APPROVED;
            
            if ($this->transactionService->save($transaction)) {
                $this->updateAccountBalance($transaction->account, $newBalance);
                return response()->json($transaction, 201);
            }
        
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Validate transaction data from the request.
     */
    protected function validateTransactionData(Request $request, $type = Transaction::TRANSACTION_DEPOSIT)
    {
        if ($type == Transaction::TRANSACTION_DEPOSIT) {
            return $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'image_name' => 'required|image|mimes:jpeg,png,jpg,gif|max:12048',
            ]);
        }
        else {
            return $request->validate([
                'amount' => 'required|numeric|min:0.01',
            ]);
        }
    }

    /**
     * Prepare transaction data for storage.
     */
    protected function prepareTransactionData(array $transactionData, $user, $type = Transaction::TRANSACTION_DEPOSIT)
    {
        $account = $user->account;
        $transactionData['account_id'] = $account->id;
        $transactionData['type'] = $type;
        $transactionData['approval'] = Transaction::TRANSACTION_PENDING;

        if ($type == Transaction::TRANSACTION_DEPOSIT) {
            $file = $transactionData['image_name'];
            $imageName = time() . '.' . $file->extension();
            $imagePath = public_path('/files');
            $file->move($imagePath, $imageName);

            $transactionData['image_url'] = $imageName;
        }

        return $transactionData;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        try{
            if ($user->type == User::USER_TYPE_ADMIN) {
                $transactions = $this->transactionService->findPendingApproval();
                return response()->json($transactions, 201);
            } else {
                throw new \Exception('Apenas o usuário Administrador tem permissão para realizar esta tarefa!');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        try {
            $updatedTransaction = $this->transactionService->updateApproval($request);

            if ($updatedTransaction && $updatedTransaction->approval == Transaction::TRANSACTION_APPROVED) {
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
            $transaction->approval = Transaction::TRANSACTION_REJECTED;
            $transaction->save();
            throw new \Exception('Não há saldo suficiente');
        }

        if ($transaction->type === Transaction::TRANSACTION_DEPOSIT) {
            return $transaction->account->balance + $transaction->amount;
        }

        throw new \Exception('Tipo de transação inválida');
    }

    private function updateAccountBalance(Account $account, float $newBalance): void
    {
        $account->balance = $newBalance;
        $account->save();
    }
}
