@extends('layouts.app')
@section('title', 'Riwayat Transaksi')
@section('content')
<div class="page-heading"><div><p class="eyebrow">AUDIT PERGERAKAN</p><h1>Riwayat Transaksi</h1><p class="muted">Catatan barang masuk dan barang keluar dengan nomor transaksi otomatis.</p></div><div class="heading-actions"><a class="btn btn-primary" href="{{ route('transaksi.masuk') }}">＋ Barang masuk</a><a class="btn btn-outline" href="{{ route('transaksi.keluar') }}">− Barang keluar</a></div></div>
<section class="panel">
    <form method="GET" action="{{ route('riwayat.index') }}" class="filter-form">
        <label>Dari tanggal<input type="date" name="from_date" value="{{ $filters['from_date'] ?? '' }}"></label>
        <label>Sampai tanggal<input type="date" name="to_date" value="{{ $filters['to_date'] ?? '' }}"></label>
        <label>Jenis transaksi<select name="type"><option value="">Semua transaksi</option><option value="masuk" @selected(($filters['type'] ?? '') === 'masuk')>Barang masuk</option><option value="keluar" @selected(($filters['type'] ?? '') === 'keluar')>Barang keluar</option></select></label>
        <div class="filter-actions"><button class="btn btn-primary">Terapkan filter</button><a class="btn btn-outline" href="{{ route('riwayat.index') }}">Reset</a><a class="btn btn-outline" href="{{ route('riwayat.export', request()->query()) }}">Export CSV</a></div>
    </form>
    <div class="table-wrap"><table><thead><tr><th>No. Transaksi</th><th>Tanggal</th><th>Jenis</th><th>Barang</th><th>Supplier</th><th>Jumlah</th><th>Petugas</th><th>Keterangan</th></tr></thead><tbody>
    @forelse($movements as $movement)<tr><td><strong>{{ $movement->transactionCode }}</strong></td><td>{{ $movement->date->format('d-m-Y') }}</td><td><span class="status {{ $movement->type === 'Barang Masuk' ? 'status-success' : 'status-neutral' }}">{{ $movement->type }}</span></td><td><strong>{{ $movement->name }}</strong><small>{{ $movement->code }}</small></td><td>{{ $movement->partner }}</td><td class="{{ $movement->quantity > 0 ? 'positive' : 'negative' }}">{{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}</td><td>{{ $movement->userName }}</td><td>{{ $movement->note }}</td></tr>@empty<tr><td colspan="8" class="empty-state">Tidak ada transaksi untuk filter tersebut.</td></tr>@endforelse
    </tbody></table></div>
</section>
@endsection
