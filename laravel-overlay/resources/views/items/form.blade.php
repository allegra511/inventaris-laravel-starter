@extends('layouts.app')
@section('title', isset($barang) ? 'Edit Barang' : 'Tambah Barang')
@section('content')
<div class="page-heading"><div><p class="eyebrow">DATA MASTER</p><h1>{{ isset($barang) ? 'Edit barang' : 'Tambah barang' }}</h1></div><a class="btn btn-outline" href="{{ route('barang.index') }}">Kembali</a></div>
<section class="panel form-panel"><form method="POST" action="{{ isset($barang) ? route('barang.update', $barang) : route('barang.store') }}" class="form-stack">
@csrf @if(isset($barang)) @method('PUT') @endif
<div class="form-grid"><label>Kode barang<input name="code" value="{{ old('code', $barang->code ?? '') }}" required></label><label>Nama barang<input name="name" value="{{ old('name', $barang->name ?? '') }}" required></label></div>
<label>Kategori<select name="category_id" required><option value="">Pilih kategori</option>@foreach($kategoris as $kategori)<option value="{{ $kategori->id }}" @selected(old('category_id', $barang->category_id ?? '') == $kategori->id)>{{ $kategori->name }}</option>@endforeach</select></label>
<div class="form-grid"><label>Lokasi penyimpanan<input name="location" value="{{ old('location', $barang->location ?? '') }}" placeholder="Contoh: Rak A1"></label><label>Satuan<input name="unit" value="{{ old('unit', $barang->unit ?? 'pcs') }}" required placeholder="pcs, rim, box"></label></div>
<div class="form-grid">
@if(isset($barang))
<label>Stok saat ini<input value="{{ $barang->stock }} {{ $barang->unit }}" readonly></label>
@else
<label>Stok awal<input type="number" name="stock" min="0" value="{{ old('stock', 0) }}" required></label>
@endif
<label>Batas stok minimum<input type="number" name="minimum_stock" min="0" value="{{ old('minimum_stock', $barang->minimum_stock ?? 0) }}" required></label>
</div>
<p class="hint">Stok berjalan diubah melalui transaksi barang masuk/keluar agar riwayat pergerakan tetap tercatat.</p>
<div class="form-actions"><a class="btn btn-outline" href="{{ route('barang.index') }}">Batal</a><button class="btn btn-primary">Simpan</button></div>
</form></section>
@endsection
