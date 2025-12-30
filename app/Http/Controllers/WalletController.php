<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Auth::user()->wallets;

        $totalBalance = $wallets->sum('balance');

        return view('wallets.index', compact('wallets', 'totalBalance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric|min:0',
        ]);

        Wallet::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'balance' => $request->balance,
            'icon' => 'account_balance_wallet', 
        ]);

        return redirect()->back()->with('success', 'Dompet berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $wallet = Wallet::where('user_id', Auth::id())->findOrFail($id);
        $wallet->delete();

        return redirect()->back()->with('success', 'Dompet berhasil dihapus.');
    }
}
