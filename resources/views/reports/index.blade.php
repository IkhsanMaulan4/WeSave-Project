@extends('layouts.app')

@section('title', 'Laporan & AI Insight')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold text-dark mb-1">Financial Health Check</h2>
    <p class="text-muted">Analisis otomatis kondisi keuanganmu bulan ini.</p>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card card-custom border-0 p-4 mb-4" style="background: linear-gradient(135deg, #102216 0%, #0a3d20 100%); color: white;">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-white bg-opacity-25 p-2 rounded-3">
                    <span class="material-symbols-outlined text-white">psychology</span>
                </div>
                <h5 class="fw-bold mb-0">WeSave AI Insight</h5>
            </div>

            <p class="fs-5 lh-base fw-light" style="font-family: 'Manrope', sans-serif;">
                "{{ $aiMessage }}"
            </p>

            <div class="mt-3 pt-3 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center">
                <small class="text-white text-opacity-50">Analysis based on {{ date('F Y') }} data</small>
                <span class="badge bg-white bg-opacity-10 text-white">Live Update</span>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-6">
                <div class="card card-custom border-0 p-3 h-100">
                    <small class="text-muted fw-bold">Pemasukan</small>
                    <h4 class="fw-bold text-success mt-1 mb-0">+ Rp {{ number_format($totalIncome, 0, ',', '.') }}</h4>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-custom border-0 p-3 h-100">
                    <small class="text-muted fw-bold">Pengeluaran</small>
                    <h4 class="fw-bold text-danger mt-1 mb-0">- Rp {{ number_format($totalExpense, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card card-custom border-0 p-4 h-100">
            <h5 class="fw-bold text-dark mb-4">Pengeluaran per Kategori</h5>

            @if(count($chartLabels) > 0)
                <div style="height: 300px; position: relative;">
                    <canvas id="expenseChart"></canvas>
                </div>
            @else
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                    <span class="material-symbols-outlined fs-1 mb-2">donut_small</span>
                    <p>Belum ada data pengeluaran untuk ditampilkan.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('expenseChart');

    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartLabels) !!}, // Data dari Controller
                datasets: [{
                    label: 'Total Pengeluaran (Rp)',
                    data: {!! json_encode($chartData) !!}, // Data dari Controller
                    backgroundColor: [
                        '#13ec5b', '#0d1b12', '#6366f1', '#f59e0b', '#ef4444',
                        '#ec4899', '#8b5cf6', '#14b8a6'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: { family: "'Manrope', sans-serif" },
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                cutout: '70%', // Membuat bolong tengah (Donut)
            }
        });
    }
</script>
@endsection
