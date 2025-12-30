<?php

namespace App\Http\Controllers;

use App\Models\SavingGoal;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SavingGoalController extends Controller
{
    public function index()
    {
        $goals = Auth::user()->savingGoals;
        $wallets = Auth::user()->wallets;

        return view('saving_goals.index', compact('goals', 'wallets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'deadline' => 'nullable|date',
        ]);

        SavingGoal::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'current_amount' => 0,
            'deadline' => $request->deadline,
        ]);

        return redirect()->back()->with('success', 'Target menabung berhasil dibuat!');
    }


    public function storeAllocation(Request $request, $id)
    {
        $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($request, $id) {
            $goal = SavingGoal::where('user_id', Auth::id())->findOrFail($id);
            $wallet = Wallet::where('user_id', Auth::id())->findOrFail($request->wallet_id);

            if ($wallet->balance < $request->amount) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'amount' => 'Saldo dompet tidak mencukupi!',
                ]);
            }

            $wallet->decrement('balance', $request->amount);

            $goal->increment('current_amount', $request->amount);
        });

        return redirect()->back()->with('success', 'Berhasil menabung ke target ini!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'deadline' => 'nullable|date',
        ]);

        $goal = SavingGoal::where('user_id', Auth::id())->findOrFail($id);

        $goal->update([
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'deadline' => $request->deadline,
        ]);

        return redirect()->back()->with('success', 'Target berhasil diperbarui!');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $goal = SavingGoal::where('user_id', Auth::id())->findOrFail($id);

            if ($goal->current_amount > 0) {

                $wallet = Wallet::where('user_id', Auth::id())->first();

                if (!$wallet) {
                    $wallet = Wallet::create([
                        'user_id' => Auth::id(),
                        'name' => 'Dompet Tunai (Refund)',
                        'balance' => 0,
                        'icon' => 'account_balance_wallet'
                    ]);

                    session()->flash('warning', 'Karena tidak ada dompet, sistem membuat "Dompet Tunai" baru untuk menampung refund.');
                }

                $wallet->increment('balance', $goal->current_amount);

                session()->flash('info', 'Saldo Rp ' . number_format($goal->current_amount) . ' dikembalikan ke dompet: ' . $wallet->name);
            }

            $goal->delete();
        });

        return redirect()->back()->with('success', 'Target berhasil dihapus.');
    }
}
