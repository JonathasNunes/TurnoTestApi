<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Repositories\AccountRepository;
use App\Services\AccountService;
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

            $transactionData = $request->validate([
                'amount' => 'required|numeric|min:0.01',
            ]);

            $accountService = new AccountService(new AccountRepository);
            $account = $accountService->getAccountByUserId($user->id);
            $transactionData['account_id'] = $account->id;
            $transactionData['type'] = Transaction::TRANSACTION_DEPOSIT;
            $transactionData['approval'] = Transaction::TRANSACTION_PENDING;

            $file = $request->file('image_name');
            $imageName = time().'.'.$file->extension();
            $imagePath = public_path(). '/files';
            $file->move($imagePath, $imageName);
            $transactionData['image_url'] = $imageName;
             
            $transaction = $this->transactionService->createTransaction($transactionData);
            return response()->json($transaction, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
