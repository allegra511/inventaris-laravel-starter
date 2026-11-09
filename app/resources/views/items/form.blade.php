@extends('layouts.app')
@section('title', isset($barang) ? 'Edit Barang' : 'Tambah Barang')
@section('content')
<div class="page-heading"><div><p class="eyebrow">DATA MASTER</p><h1>{{ isset($barang) ? 'Edit barang' : 'Tambah barang' }}</h1><p class="muted">Kode barang dibuat otomatis oleh sistem.</p></div><a class="btn btn-outline" href="{{ route('barang.index') }}">Kembali</a></div>
<section class="panel form-panel"><form method="POST" action="{{ isset($barang) ? route('barang.update', $barang) : route('barang.store') }}" class="form-stack">
@csrf @if(isset($barang)) @method('PUT') @endif
<div class="form-grid">
    <label>Kode barang
        <input value="{{ $barang->code ?? 'Dibuat otomatis setelah disimpan' }}" readonly>
        <small class="hint">Nomor unik dibuat otomatis; tidak perlu diketik.</small>
    </label>
    <label>Nama barang<input name="name" value="{{ old('name', $barang->name ?? '') }}" required maxlength="150"></label>
</div>
<label>Kategori<select name="category_id" required><option value="">Pilih kategori</option>@foreach($kategoris as $kategori)<option value="{{ $kategori->id }}" @selected(old('category_id', $barang->category_id ?? '') == $kategori->id)>{{ $kategori->name }}</option>@endforeach</select></label>
<div class="form-grid"><label>Lokasi penyimpanan<input name="location" value="{{ old('location', $barang->location ?? '') }}" placeholder="Contoh: Rak A1"></label><label>Satuan<input name="unit" value="{{ old('unit', $barang->unit ?? 'pcs') }}" required placeholder="pcs, rim, box"></label></div>
<div class="form-grid">
@if(isset($barang))
    <label>Stok saat ini<input value="{{ $barang->stock }} {{ $barang->unit }}" readonly></label>
@else
    <label>Stok awal<input value="0 (otomatis)" readonly><small class="hint">Stok awal dicatat melalui transaksi barang masuk agar riwayat tetap akurat.</small></label>
@endif
    <label>Batas stok minimum<input type="number" name="minimum_stock" min="0" value="{{ old('minimum_stock', $barang->minimum_stock ?? 0) }}" required></label>
</div>
<label>Target stok ideal<input type="number" name="target_stock" min="0" value="{{ old('target_stock', $barang->target_stock ?? 10) }}" required><small class="hint">Sistem menghitung saran jumlah restock = target stok ideal dikurangi stok saat ini.</small></label>
<p class="hint">Stok berjalan hanya diubah melalui transaksi barang masuk/keluar. Ini mencegah perubahan stok tanpa riwayat.</p>
<div class="form-actions"><a class="btn btn-outline" href="{{ route('barang.index') }}">Batal</a><button class="btn btn-primary">Simpan</button></div>
</form></section>
@endsection
