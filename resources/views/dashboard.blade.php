@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<!-- Tambahkan Bootstrap Icons CDN jika belum ada di layouts/app.php -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Gadget Store Dashboard</h3>
    <p class="text-secondary">Ringkasan inventaris dan kondisi stok produk toko gadget.</p>
</div>

<!-- Kartu Statistik -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0">
            <div class="card-body">
                <div class="text-secondary small">Total Produk</div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h2 class="fw-bold mb-0">{{ number_format($totalProducts) }}</h2>
                    <i class="bi bi-phone fs-2 text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0">
            <div class="card-body">
                <div class="text-secondary small">Kategori</div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h2 class="fw-bold mb-0">{{ number_format($totalCategories) }}</h2>
                    <i class="bi bi-tags fs-2 text-success"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0">
            <div class="card-body">
                <div class="text-secondary small">Total Unit Stok</div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h2 class="fw-bold mb-0">{{ number_format($totalStock) }}</h2>
                    <i class="bi bi-box-seam fs-2 text-info"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0">
            <div class="card-body">
                <div class="text-secondary small">Stok Menipis (≤ 5)</div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h2 class="fw-bold mb-0 text-danger">{{ number_format($lowStockProducts) }}</h2>
                    <i class="bi bi-exclamation-triangle fs-2 text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Row 1 -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Jumlah Produk per Kategori</h5>
                <div style="height:300px"><canvas id="categoryChart"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Komposisi Kondisi Stok</h5>
                <div style="height:260px"><canvas id="stockChart"></canvas></div>
                <div class="small text-secondary mt-2">Menipis: 0–5 • Cukup: 6–20 • Banyak: >20 unit</div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Row 2 & Nilai Persediaan -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Total Unit Stok per Kategori</h5>
                <div style="height:280px"><canvas id="stockByCategoryChart"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100 d-flex flex-column justify-content-center">
            <div class="card-body">
                <h5 class="fw-bold text-dark mb-2">Estimasi Nilai Persediaan</h5>
                <h3 class="fw-bold text-primary mb-3">Rp {{ number_format($inventoryValue, 0, ',', '.') }}</h3>
                <p class="small text-secondary mb-0">Akumulasi dari (harga produk × jumlah stok saat ini). Bukan menghitung omzet penjualan.</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Produk Terbaru -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Produk Terbaru</h5>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">Lihat semua</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($latestProducts as $product)
                    <tr>
                        <td><code>{{ $product->product_code }}</code></td>
                        <td class="fw-semibold">{{ $product->name }}</td>
                        <td><span class="badge bg-secondary">{{ $product->category->name ?? '-' }}</span></td>
                        <td>Rp {{ number_format((float)$product->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $product->stock <= 5 ? 'bg-danger' : 'bg-success' }}">
                                {{ $product->stock }} unit
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">Belum ada data produk. Tambahkan produk untuk menampilkan analitik.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const categoryLabels = @json($categoryStats->pluck('name')->values());
const productCounts = @json($categoryStats->pluck('product_count')->map(fn($v) => (int) $v)->values());
const stockTotals = @json($categoryStats->pluck('stock_total')->map(fn($v) => (int) $v)->values());
const stockStatus = @json($stockStatus);

// 1. Chart Jumlah Produk per Kategori
new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: {
        labels: categoryLabels,
        datasets: [{
            label: 'Jumlah Produk',
            data: productCounts,
            backgroundColor: 'rgba(78, 115, 223, 0.8)',
            borderColor: 'rgba(78, 115, 223, 1)',
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});

// 2. Chart Komposisi Kondisi Stok
new Chart(document.getElementById('stockChart'), {
    type: 'doughnut',
    data: {
        labels: ['Stok Menipis', 'Stok Cukup', 'Stok Banyak'],
        datasets: [{
            data: stockStatus,
            backgroundColor: ['#e74a3b', '#f6c23e', '#1cc88a'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// 3. Chart Total Unit Stok per Kategori
new Chart(document.getElementById('stockByCategoryChart'), {
    type: 'bar',
    data: {
        labels: categoryLabels,
        datasets: [{
            label: 'Total Unit Stok',
            data: stockTotals,
            backgroundColor: 'rgba(54, 185, 204, 0.8)',
            borderColor: 'rgba(54, 185, 204, 1)',
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});
</script>
@endpush
