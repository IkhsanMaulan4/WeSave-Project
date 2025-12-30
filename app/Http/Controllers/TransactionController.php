<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewTransaction;
use App\Notifications\TransactionDeleted;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $wallets = Wallet::where('user_id', $userId)->get();
        $categories = Category::where('user_id', $userId)->get();

        $query = Transaction::where('user_id', $userId)
            ->with(['wallet', 'category']); 

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', "%$search%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('wallet', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    });
            });
        }

        $transactions = $query->latest('transaction_date')->get();

        return view('transactions.index', compact('transactions', 'wallets', 'categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:income,expense,transfer',
            'transaction_date' => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $wallet = Wallet::where('user_id', $user->id)->findOrFail($request->wallet_id);
            $amount = $request->amount;

            $imagePath = null;
            if ($request->hasFile('proof_image')) {
                $imagePath = $request->file('proof_image')->store('proofs', 'public');
            }

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $request->wallet_id,
                'category_id' => $request->category_id,
                'destination_wallet_id' => $request->destination_wallet_id,
                'amount' => $amount,
                'type' => $request->type,
                'transaction_date' => $request->transaction_date,
                'description' => $request->description,
                'proof_image' => $imagePath,
            ]);

            if ($request->type == 'expense') {
                $wallet->decrement('balance', $amount);
            } elseif ($request->type == 'income') {
                $wallet->increment('balance', $amount);
            } elseif ($request->type == 'transfer') {
                $destWallet = Wallet::where('user_id', $user->id)->findOrFail($request->destination_wallet_id);
                $wallet->decrement('balance', $amount);
                $destWallet->increment('balance', $amount);
            }

            $user->notify(new NewTransaction($transaction));
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan!');
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $transaction = Transaction::where('user_id', $user->id)->findOrFail($id);
            $wallet = Wallet::findOrFail($transaction->wallet_id);

            $backupData = [
                'amount' => $transaction->amount,
                'description' => $transaction->description,
                'wallet_name' => $wallet->name
            ];

            if ($transaction->type == 'expense') {
                $wallet->increment('balance', $transaction->amount);
            } elseif ($transaction->type == 'income') {
                $wallet->decrement('balance', $transaction->amount);
            } elseif ($transaction->type == 'transfer') {
                $destWallet = Wallet::where('user_id', $user->id)->find($transaction->destination_wallet_id);
                if ($destWallet) {
                    $wallet->increment('balance', $transaction->amount);
                    $destWallet->decrement('balance', $transaction->amount);
                }
            }

            $transaction->delete();

            $user->notify(new TransactionDeleted($backupData));
        });

        return redirect()->back()->with('success', 'Transaksi dihapus & Saldo dikembalikan.');
    }
}
