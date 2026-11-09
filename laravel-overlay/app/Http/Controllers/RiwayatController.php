<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->validateFilters($request);
        $movements = $this->filteredMovements($filters);

        return view('history.index', compact('movements', 'filters'));
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $this->validateFilters($request);
        $movements = $this->filteredMovements($filters);

        return response()->streamDownload(function () use ($movements) {
            $output = fopen('php://output', 'w');
            // BOM agar karakter UTF-8 terbaca dengan baik oleh Excel.
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['No. Transaksi', 'Tanggal', 'Jenis', 'Kode Barang', 'Nama Barang', 'Jumlah', 'Supplier', 'Petugas', 'Keterangan']);

            foreach ($movements as $movement) {
                fputcsv($output, [
                    $movement->transactionCode,
                    $movement->date->format('Y-m-d'),
                    $movement->type,
                    $movement->code,
                    $movement->name,
                    $movement->quantity,
                    $movement->partner,
                    $movement->userName,
                    $movement->note,
                ]);
            }
            fclose($output);
        }, 'laporan-inventaris-' . now()->format('Ymd-His') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function validateFilters(Request $request): array
    {
        return $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
            'type' => ['nullable', 'in:masuk,keluar'],
        ]);
    }

    private function filteredMovements(array $filters): Collection
    {
        $masukQuery = BarangMasuk::with(['barang', 'supplier', 'user']);
        $keluarQuery = BarangKeluar::with(['barang', 'user']);

        foreach ([$masukQuery, $keluarQuery] as $query) {
            if (!empty($filters['from_date'])) {
                $query->whereDate('date', '>=', $filters['from_date']);
            }
            if (!empty($filters['to_date'])) {
                $query->whereDate('date', '<=', $filters['to_date']);
            }
        }

        $masuk = ($filters['type'] ?? null) === 'keluar'
            ? collect()
            : $masukQuery->get()->map(fn ($row) => (object) [
                'id' => $row->id,
                'transactionCode' => $row->transaction_code ?? '—',
                'type' => 'Barang Masuk',
                'date' => $row->date,
                'code' => $row->barang?->code ?? '-',
                'name' => $row->barang?->name ?? 'Barang tidak tersedia',
                'quantity' => $row->quantity,
                'partner' => $row->supplier?->name ?? '-',
                'userName' => $row->user?->name ?? '-',
                'note' => $row->note ?? '-',
            ]);

        $keluar = ($filters['type'] ?? null) === 'masuk'
            ? collect()
            : $keluarQuery->get()->map(fn ($row) => (object) [
                'id' => $row->id,
                'transactionCode' => $row->transaction_code ?? '—',
                'type' => 'Barang Keluar',
                'date' => $row->date,
                'code' => $row->barang?->code ?? '-',
                'name' => $row->barang?->name ?? 'Barang tidak tersedia',
                'quantity' => -$row->quantity,
                'partner' => '-',
                'userName' => $row->user?->name ?? '-',
                'note' => $row->note ?? '-',
            ]);

        return $masuk->concat($keluar)
            ->sortByDesc(fn ($row) => $row->date->format('Y-m-d') . '-' . str_pad((string) $row->id, 12, '0', STR_PAD_LEFT))
            ->values();
    }
}
