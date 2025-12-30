<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Category;

class UserObserver
{

    public function created(User $user): void
    {
        Wallet::create([
            'user_id' => $user->id,
            'name' => 'Tunai',
            'balance' => 0,
        ]);

        $defaults = [
            ['name' => 'Makan & Minum', 'type' => 'expense'],
            ['name' => 'Transportasi', 'type' => 'expense'],
            ['name' => 'Belanja', 'type' => 'expense'],
            ['name' => 'Tagihan & Utilitas', 'type' => 'expense'],
            ['name' => 'Hiburan', 'type' => 'expense'],
            ['name' => 'Kesehatan', 'type' => 'expense'],
            ['name' => 'Pendidikan', 'type' => 'expense'],
            ['name' => 'Gaji', 'type' => 'income'],
            ['name' => 'Bonus', 'type' => 'income'],
            ['name' => 'Investasi', 'type' => 'income'],
        ];

        foreach ($defaults as $cat) {
            Category::create([
                'user_id' => $user->id,
                'name' => $cat['name'],
                'type' => $cat['type'],
            ]);
        }
    }
}
