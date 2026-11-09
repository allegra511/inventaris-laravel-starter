<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    private function adminOnly(): void { abort_unless(auth()->user()->isAdmin(), 403, 'Akses khusus Admin.'); }

    public function index()
    {
        $suppliers = Supplier::withCount('barangMasuk')->orderBy('name')->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create() { $this->adminOnly(); return view('suppliers.form'); }

    public function store(Request $request)
    {
        $this->adminOnly();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'contact_person' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);
        Supplier::create($data);
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier) { $this->adminOnly(); return view('suppliers.form', compact('supplier')); }

    public function update(Request $request, Supplier $supplier)
    {
        $this->adminOnly();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'contact_person' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);
        $supplier->update($data);
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $this->adminOnly();
        if ($supplier->barangMasuk()->exists()) return back()->with('error', 'Supplier sudah tercatat dalam transaksi dan tidak dapat dihapus.');
        $supplier->delete();
        return back()->with('success', 'Supplier berhasil dihapus.');
    }
}
