<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if ($user) {
            $categories = [
                // PENGELUARAN
                ['name' => 'Makanan & Minuman', 'type' => 'expense', 'icon' => 'restaurant'],
                ['name' => 'Transportasi', 'type' => 'expense', 'icon' => 'directions_car'],
                ['name' => 'Belanja', 'type' => 'expense', 'icon' => 'shopping_bag'],
                ['name' => 'Tagihan & Utilitas', 'type' => 'expense', 'icon' => 'receipt_long'],
                ['name' => 'Hiburan', 'type' => 'expense', 'icon' => 'movie'],
                ['name' => 'Kesehatan', 'type' => 'expense', 'icon' => 'medical_services'],

                // PEMASUKAN
                ['name' => 'Gaji', 'type' => 'income', 'icon' => 'work'],
                ['name' => 'Bonus', 'type' => 'income', 'icon' => 'star'],
                ['name' => 'Investasi', 'type' => 'income', 'icon' => 'trending_up'],
            ];

            foreach ($categories as $cat) {
                Category::create([
                    'user_id' => $user->id, // Set milik user pertama
                    'name' => $cat['name'],
                    'type' => $cat['type'],
                    'icon' => $cat['icon'],
                ]);
            }
        }
    }
}
