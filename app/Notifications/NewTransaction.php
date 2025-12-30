<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewTransaction extends Notification
{
    use Queueable;

    public $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $type = $this->transaction->type;
        $amount = number_format($this->transaction->amount, 0, ',', '.');
        $walletName = $this->transaction->wallet->name ?? 'Dompet';

        if ($type == 'expense') {
            $message = "Pengeluaran Rp $amount dari $walletName berhasil dicatat.";
            $icon = 'arrow_upward';
            $color = 'text-danger';
        } elseif ($type == 'income') {
            $message = "Pemasukan Rp $amount ke $walletName diterima!";
            $icon = 'arrow_downward';
            $color = 'text-success';
        } else {
            $message = "Transfer Rp $amount berhasil.";
            $icon = 'swap_horiz';
            $color = 'text-primary';
        }

        return [
            'transaction_id' => $this->transaction->id,
            'message' => $message,
            'amount' => $this->transaction->amount,
            'type' => $type,
            'icon' => $icon,
            'color' => $color,
            'time' => now()
        ];
    }
}
