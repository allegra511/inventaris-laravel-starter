@extends('layouts.app')
@section('title', 'Kategori')
@section('content')
<div class="page-heading"><div><p class="eyebrow">DATA MASTER</p><h1>Kategori Barang</h1><p class="muted">Kelompokkan barang agar lebih mudah dikelola.</p></div>@if(auth()->user()->isAdmin())<a class="btn btn-primary" href="{{ route('kategori.create') }}">＋ Tambah kategori</a>@endif</div>
<section class="panel"><div class="table-wrap"><table><thead><tr><th>Nama kategori</th><th>Deskripsi</th><th>Jumlah barang</th>@if(auth()->user()->isAdmin())<th>Aksi</th>@endif</tr></thead><tbody>
@forelse($kategoris as $kategori)<tr><td><strong>{{ $kategori->name }}</strong></td><td>{{ $kategori->description ?: '—' }}</td><td>{{ $kategori->barang_count }}</td>@if(auth()->user()->isAdmin())<td class="actions"><a class="text-link" href="{{ route('kategori.edit', $kategori) }}">Edit</a><form method="POST" action="{{ route('kategori.destroy', $kategori) }}" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')<button class="link-danger">Hapus</button></form></td>@endif</tr>@empty<tr><td colspan="4" class="empty-state">Belum ada kategori.</td></tr>@endforelse
</tbody></table></div><div class="pagination">{{ $kategoris->links() }}</div></section>
@endsection
