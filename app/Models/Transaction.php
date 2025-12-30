<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'destination_wallet_id',
        'category_id',
        'amount',
        'type',
        'transaction_date',
        'description',
        'proof_image'
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
