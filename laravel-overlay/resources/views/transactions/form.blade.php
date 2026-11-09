@extends('layouts.app')
@section('title', $type === 'masuk' ? 'Barang Masuk' : 'Barang Keluar')
@section('content')
<div class="page-heading"><div><p class="eyebrow">SIRKULASI INVENTARIS</p><h1>Transaksi Barang {{ ucfirst($type) }}</h1><p class="muted">{{ $type === 'masuk' ? 'Catat barang yang diterima dari supplier.' : 'Catat barang yang dikeluarkan dari persediaan.' }}</p></div><a class="btn btn-outline" href="{{ route('riwayat.index') }}">Lihat riwayat</a></div>
<section class="panel form-panel"><form method="POST" action="{{ $type === 'masuk' ? route('transaksi.masuk.store') : route('transaksi.keluar.store') }}" class="form-stack">
@csrf
<label>Barang<select name="item_id" required><option value="">Pilih barang</option>@foreach($barangs as $barang)<option value="{{ $barang->id }}" @selected(old('item_id', $defaultItemId ?? '') == $barang->id)>{{ $barang->code }} — {{ $barang->name }} (stok: {{ $barang->stock }} {{ $barang->unit }})</option>@endforeach</select></label>
@if($type === 'masuk')<label>Supplier<select name="supplier_id" required><option value="">Pilih supplier</option>@foreach($suppliers as $supplier)<option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>@endforeach</select></label>@endif
<div class="form-grid"><label>Jumlah<input type="number" name="quantity" min="1" value="{{ old('quantity', $defaultQuantity ?? 1) }}" required></label><label>Tanggal<input type="date" name="date" value="{{ old('date', now()->toDateString()) }}"><small class="hint">Otomatis terisi tanggal hari ini jika tidak diubah.</small></label></div>
<label>Keterangan {{ $type === 'keluar' ? '(wajib)' : '(opsional)' }}<textarea name="note" rows="3" {{ $type === 'keluar' ? 'required' : '' }}>{{ old('note') }}</textarea></label>
<p class="hint">Nomor transaksi dan petugas pencatat dibuat otomatis oleh sistem setelah transaksi disimpan. Stok juga diperbarui otomatis.</p>
<div class="form-actions"><a class="btn btn-outline" href="{{ route('dashboard') }}">Batal</a><button class="btn btn-primary">Simpan transaksi</button></div>
</form></section>
@endsection
