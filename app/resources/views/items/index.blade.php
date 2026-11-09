@extends('layouts.app')
@section('title', 'Data Barang')
@section('content')
<div class="page-heading"><div><p class="eyebrow">DATA MASTER</p><h1>Data Barang</h1><p class="muted">Daftar barang dan kondisi stok terkini.</p></div>@if(auth()->user()->isAdmin())<a class="btn btn-primary" href="{{ route('barang.create') }}">＋ Tambah barang</a>@endif</div>
<section class="panel"><form method="GET" class="search-form"><input name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama barang..."><button class="btn btn-outline">Cari</button>@if(request('q'))<a href="{{ route('barang.index') }}" class="text-link">Reset</a>@endif</form>
<div class="table-wrap"><table><thead><tr><th>Kode / Nama</th><th>Kategori</th><th>Lokasi</th><th>Stok</th><th>Batas minimum</th>@if(auth()->user()->isAdmin())<th>Aksi</th>@endif</tr></thead><tbody>
@forelse($barangs as $barang)<tr><td><strong>{{ $barang->name }}</strong><small>{{ $barang->code }}</small></td><td>{{ $barang->kategori?->name ?? '—' }}</td><td>{{ $barang->location ?: '—' }}</td><td><span class="status {{ $barang->isStokMinimum() ? 'status-danger' : 'status-success' }}">{{ $barang->stock }} {{ $barang->unit }}</span></td><td>{{ $barang->minimum_stock }} {{ $barang->unit }}</td>@if(auth()->user()->isAdmin())<td class="actions"><a class="text-link" href="{{ route('barang.edit', $barang) }}">Edit</a><form method="POST" action="{{ route('barang.destroy', $barang) }}" onsubmit="return confirm('Hapus barang ini?')">@csrf @method('DELETE')<button class="link-danger">Hapus</button></form></td>@endif</tr>@empty<tr><td colspan="6" class="empty-state">Barang tidak ditemukan.</td></tr>@endforelse
</tbody></table></div><div class="pagination">{{ $barangs->links() }}</div></section>
@endsection
