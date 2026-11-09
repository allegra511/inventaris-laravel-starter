@extends('layouts.app')
@section('title', isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori')
@section('content')
<div class="page-heading"><div><p class="eyebrow">DATA MASTER</p><h1>{{ isset($kategori) ? 'Edit kategori' : 'Tambah kategori' }}</h1></div><a class="btn btn-outline" href="{{ route('kategori.index') }}">Kembali</a></div>
<section class="panel form-panel"><form method="POST" action="{{ isset($kategori) ? route('kategori.update', $kategori) : route('kategori.store') }}" class="form-stack">
@csrf @if(isset($kategori)) @method('PUT') @endif
<label>Nama kategori<input name="name" value="{{ old('name', $kategori->name ?? '') }}" maxlength="100" required></label>
<label>Deskripsi<textarea name="description" rows="4">{{ old('description', $kategori->description ?? '') }}</textarea></label>
<div class="form-actions"><a class="btn btn-outline" href="{{ route('kategori.index') }}">Batal</a><button class="btn btn-primary">Simpan</button></div>
</form></section>
@endsection
