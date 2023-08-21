<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Transaction extends Model
{
    use HasFactory;
    const TRANSACTION_DEPOSIT = 'deposit';
    const TRANSACTION_PURCHASE = 'purchase';
    
    const TRANSACTION_APPROVED = 'approved';
    const TRANSACTION_REJECTED = 'rejected';
    const TRANSACTION_PENDING =  'pending';

    protected $fillable = [
        'account_id',
        'amount',
        'description',
        'type',
        'image_url',
        'approval',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public static function validateTransactionAmount($data)
{
    $account = Account::find($data['account_id']);
    
    if ($data['type'] === self::TRANSACTION_PURCHASE && $data['amount'] > $account->balance) {
        throw ValidationException::withMessages([
            'amount' => 'The purchase amount cannot exceed the account balance.',
        ]);
    }
}
}
