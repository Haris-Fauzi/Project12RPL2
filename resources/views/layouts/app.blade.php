<!doctype html>
<html lang="id">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>@yield('title', 'Gadget Store')</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
 <aside class="bg-dark text-white p-3" style="width:240px;min-height:100vh">
 <h4>Gadget Store</h4>
 <a class="d-block text-white py-2" href="{{ route('dashboard') }}">Dashboard</a>
 <a class="d-block text-white py-2" href="{{ route('products.index') }}">Data Produk</a>
 <a class="d-block text-white py-2" href="{{ route('categories.index') }}">Kategori Produk</a>
 </aside>
 <main class="flex-grow-1 p-4">
 @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
 @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
 @yield('content')
 </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>