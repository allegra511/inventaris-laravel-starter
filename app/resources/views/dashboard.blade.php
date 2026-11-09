@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-heading"><div><p class="eyebrow">RINGKASAN OPERASIONAL</p><h1>Dashboard Inventaris</h1><p class="muted">Pantau ketersediaan dan pergerakan barang dalam satu tempat.</p></div><span class="date-chip">{{ now()->translatedFormat('d F Y') }}</span></div>
<div class="stats-grid">
    <div class="stat-card"><span>Total jenis barang</span><strong>{{ $totalBarang }}</strong><small>Item terdaftar</small></div>
    <div class="stat-card"><span>Total stok</span><strong>{{ number_format($totalStok) }}</strong><small>Seluruh unit tersimpan</small></div>
    <div class="stat-card"><span>Supplier</span><strong>{{ $totalSupplier }}</strong><small>Mitra pemasok</small></div>
    <div class="stat-card warning-stat"><span>Stok minimum</span><strong>{{ $stokKritis }}</strong><small>Perlu diperhatikan</small></div>
</div>
<div class="content-grid">
    <section class="panel"><div class="panel-heading"><div><h2>Barang perlu restock</h2><p class="muted">Stok sama dengan atau di bawah batas minimum.</p></div><a href="{{ route('barang.index') }}" class="text-link">Lihat barang</a></div>
        @if($barangStokKritis->isEmpty())<p class="empty-state">Tidak ada barang dengan stok kritis.</p>@else
        <div class="table-wrap"><table><thead><tr><th>Barang</th><th>Lokasi</th><th>Stok</th><th>Minimum</th></tr></thead><tbody>
        @foreach($barangStokKritis as $barang)<tr><td><strong>{{ $barang->name }}</strong><small>{{ $barang->code }}</small></td><td>{{ $barang->location ?: '—' }}</td><td><span class="status status-danger">{{ $barang->stock }} {{ $barang->unit }}</span></td><td>{{ $barang->minimum_stock }}</td></tr>@endforeach
        </tbody></table></div>@endif
    </section>
    <section class="panel quick-actions"><h2>Aksi cepat</h2><p class="muted">Catat pergerakan barang.</p><a class="action-link" href="{{ route('transaksi.masuk') }}"><span class="action-icon plus">＋</span><span><strong>Barang masuk</strong><small>Tambah stok dari supplier</small></span></a><a class="action-link" href="{{ route('transaksi.keluar') }}"><span class="action-icon minus">−</span><span><strong>Barang keluar</strong><small>Catat pengeluaran barang</small></span></a><a class="action-link" href="{{ route('riwayat.index') }}"><span class="action-icon history">↺</span><span><strong>Riwayat transaksi</strong><small>Lihat pergerakan barang</small></span></a></section>
</div>
<section class="panel"><div class="panel-heading"><div><h2>Transaksi terbaru</h2><p class="muted">Lima transaksi masuk terakhir.</p></div><a href="{{ route('riwayat.index') }}" class="text-link">Semua riwayat</a></div>
@if($barangMasukTerbaru->isEmpty())<p class="empty-state">Belum ada transaksi barang masuk.</p>@else
<div class="table-wrap"><table><thead><tr><th>Tanggal</th><th>Barang</th><th>Supplier</th><th>Jumlah</th></tr></thead><tbody>@foreach($barangMasukTerbaru as $trx)<tr><td>{{ $trx->date->format('d-m-Y') }}</td><td>{{ $trx->barang?->name ?? 'Barang dihapus' }}</td><td>{{ $trx->supplier?->name ?? '—' }}</td><td><span class="status status-success">+{{ $trx->quantity }}</span></td></tr>@endforeach</tbody></table></div>@endif
</section>
@endsection
