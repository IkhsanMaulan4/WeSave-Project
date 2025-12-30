<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\SavingGoal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    //
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $totalBalance = $user->wallets()->sum('balance');

        $incomeThisMonth = $user->transactions()
            ->where('type', 'income')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $expenseThisMonth = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $recentTransactions = $user->transactions()
            ->with(['wallet', 'category'])
            ->latest('transaction_date')
            ->take(5)
            ->get();

        $goals = $user->savingGoals()->take(3)->get();

        return view('dashboard', compact(
            'totalBalance',
            'incomeThisMonth',
            'expenseThisMonth',
            'recentTransactions',
            'goals'
        ));
    }
}
