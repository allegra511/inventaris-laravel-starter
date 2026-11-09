@extends('layouts.app')
@section('title', 'Supplier')
@section('content')
<div class="page-heading"><div><p class="eyebrow">DATA MASTER</p><h1>Supplier</h1><p class="muted">Data mitra pemasok barang.</p></div>@if(auth()->user()->isAdmin())<a class="btn btn-primary" href="{{ route('supplier.create') }}">＋ Tambah supplier</a>@endif</div>
<section class="panel"><div class="table-wrap"><table><thead><tr><th>Nama supplier</th><th>Kontak</th><th>Email</th><th>Telepon</th><th>Transaksi masuk</th>@if(auth()->user()->isAdmin())<th>Aksi</th>@endif</tr></thead><tbody>
@forelse($suppliers as $supplier)<tr><td><strong>{{ $supplier->name }}</strong><small>{{ $supplier->address }}</small></td><td>{{ $supplier->contact_person ?: '—' }}</td><td>{{ $supplier->email ?: '—' }}</td><td>{{ $supplier->phone ?: '—' }}</td><td>{{ $supplier->barang_masuk_count }}</td>@if(auth()->user()->isAdmin())<td class="actions"><a class="text-link" href="{{ route('supplier.edit', $supplier) }}">Edit</a><form method="POST" action="{{ route('supplier.destroy', $supplier) }}" onsubmit="return confirm('Hapus supplier ini?')">@csrf @method('DELETE')<button class="link-danger">Hapus</button></form></td>@endif</tr>@empty<tr><td colspan="6" class="empty-state">Belum ada supplier.</td></tr>@endforelse
</tbody></table></div><div class="pagination">{{ $suppliers->links() }}</div></section>
@endsection
