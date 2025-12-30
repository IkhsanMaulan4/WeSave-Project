<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class TransactionDeleted extends Notification
{
    use Queueable;

    public $transactionData;

    public function __construct($transactionData)
    {
        $this->transactionData = $transactionData;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $amount = number_format($this->transactionData['amount'], 0, ',', '.');
        $desc   = $this->transactionData['description'] ?? 'Tanpa keterangan';

        $message = "Transaksi '$desc' (Rp $amount) telah dihapus. Saldo dikembalikan.";

        return [
            'message' => $message,
            'amount' => $this->transactionData['amount'],
            'type' => 'deletion',
            'icon' => 'delete',
            'color' => 'text-danger',
            'time' => now()
        ];
    }
}
