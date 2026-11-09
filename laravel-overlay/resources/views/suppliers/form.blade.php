@extends('layouts.app')
@section('title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')
@section('content')
<div class="page-heading"><div><p class="eyebrow">DATA MASTER</p><h1>{{ isset($supplier) ? 'Edit supplier' : 'Tambah supplier' }}</h1></div><a class="btn btn-outline" href="{{ route('supplier.index') }}">Kembali</a></div>
<section class="panel form-panel"><form method="POST" action="{{ isset($supplier) ? route('supplier.update', $supplier) : route('supplier.store') }}" class="form-stack">
@csrf @if(isset($supplier)) @method('PUT') @endif
<label>Nama supplier<input name="name" value="{{ old('name', $supplier->name ?? '') }}" required maxlength="150"></label>
<div class="form-grid"><label>Nama kontak<input name="contact_person" value="{{ old('contact_person', $supplier->contact_person ?? '') }}"></label><label>Nomor telepon<input name="phone" value="{{ old('phone', $supplier->phone ?? '') }}"></label></div>
<label>Email<input type="email" name="email" value="{{ old('email', $supplier->email ?? '') }}"></label>
<label>Alamat<textarea name="address" rows="3">{{ old('address', $supplier->address ?? '') }}</textarea></label>
<div class="form-actions"><a class="btn btn-outline" href="{{ route('supplier.index') }}">Batal</a><button class="btn btn-primary">Simpan</button></div>
</form></section>
@endsection
