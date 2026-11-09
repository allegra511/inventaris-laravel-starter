<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventaris Barang') — Sistem Inventaris</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
@if(auth()->check())
<header class="topbar">
    <a class="brand" href="{{ route('dashboard') }}"><span class="brand-mark">I</span><span>Inventaris<span class="brand-sub">Pengelolaan Barang</span></span></a>
    <nav class="nav-links">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('barang.index') }}">Barang</a>
        <a href="{{ route('kategori.index') }}">Kategori</a>
        <a href="{{ route('supplier.index') }}">Supplier</a>
        <a href="{{ route('transaksi.masuk') }}">Barang Masuk</a>
        <a href="{{ route('transaksi.keluar') }}">Barang Keluar</a>
        <a href="{{ route('riwayat.index') }}">Riwayat</a>
    </nav>
    <div class="user-area"><span>{{ auth()->user()->name }} · {{ auth()->user()->role === 'admin' ? 'Admin' : 'Petugas' }}</span><form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline btn-small">Keluar</button></form></div>
</header>
@endif
<main class="container {{ auth()->check() ? '' : 'auth-container' }}">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-error">{{ session('error') }}</div> @endif
    @if($errors->any()) <div class="alert alert-error"><strong>Periksa kembali:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif
    @yield('content')
</main>
<footer class="footer">Sistem Informasi Inventaris &amp; Pengelolaan Barang</footer>
</body>
</html>
