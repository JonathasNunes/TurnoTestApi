<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
