<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class ReportController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $transactions = Transaction::where('user_id', $userId)
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');

        $expensesByCategory = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->with('category')
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->get();

        $chartLabels = [];
        $chartData = [];
        foreach ($expensesByCategory as $data) {
            $chartLabels[] = $data->category->name ?? 'Lainnya';
            $chartData[] = $data->total;
        }

        $aiMessage = $this->generateAIInsight($totalIncome, $totalExpense, $expensesByCategory);

        return view('reports.index', compact('totalIncome', 'totalExpense', 'chartLabels', 'chartData', 'aiMessage'));
    }

    private function generateAIInsight($income, $expense, $categoryData)
    {

        $categoriesString = $categoryData->map(function ($item) {
            return $item->category->name . ': Rp ' . number_format($item->total, 0, ',', '.');
        })->implode(', ');

        $prompt = "Saya punya data keuangan bulan ini.
                   Pemasukan: Rp " . number_format($income, 0, ',', '.') . ".
                   Pengeluaran: Rp " . number_format($expense, 0, ',', '.') . ".
                   Detail Pengeluaran: " . $categoriesString . ".

                   Berikan saran keuangan singkat (maksimal 2 kalimat) yang santai dan memotivasi untuk berhemat. Jangan gunakan format markdown (bold/italic), teks biasa saja.";


        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            return "Mode Demo: Masukkan GEMINI_API_KEY di file .env.";
        }

        try {

            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

            $response = Http::post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return $data['candidates'][0]['content']['parts'][0]['text'] ?? "AI tidak memberikan respon teks.";
            } else {
                return "Maaf, AI sedang istirahat. (Error: " . $response->status() . ")";
            }
        } catch (\Exception $e) {
            return "Gagal terhubung ke Gemini AI. Cek koneksi internet.";
        }
    }
}
