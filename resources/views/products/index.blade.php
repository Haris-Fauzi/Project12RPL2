@extends('layouts.app')
@section('title', 'Data Produk')
@section('page-heading', 'Manajemen Produk')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h3 class="fw-bold">Data Produk</h3><p class="text-secondary">Kelola inventaris produk toko gadget.</p></div>
    <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Produk</a>
</div>
<div class="card border-0 shadow-sm"><div class="card-body">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari kode, nama, atau merek produk..."></div>
        <div class="col-md-4">
            <select name="category_id" class="form-select">
                <option value="">Semua kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3"><button class="btn btn-outline-primary">Cari / Filter</button> <a href="{{ route('products.index') }}" class="btn btn-light">Reset</a></div>
    </form>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Kode</th><th>Nama Produk</th><th>Kategori</th><th>Merek</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->product_code }}</td>
                    <td class="fw-semibold">{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->brand ?: '-' }}</td>
                    <td>Rp {{ number_format((float)$product->price, 0, ',', '.') }}</td>
                    <td><span class="badge {{ $product->stock <= 5 ? 'text-bg-danger' : ($product->stock <= 20 ? 'text-bg-warning' : 'text-bg-success') }}">{{ $product->stock }}</span></td>
                    <td class="text-nowrap">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('products.edit', $product) }}">Edit</a>
                        <form class="d-inline" method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-secondary">Belum ada data produk.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $products->links() }}
</div></div>
@endsection
