<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cashier;

class CashierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Pastikan user login dulu
    }

    /**
     * Tampilkan daftar transaksi.
     */
    public function index(Request $request)
    {
        $query = Cashier::query();

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('kode_produk', 'like', "%{$search}%");
        }

        // Pengurutan
        if ($request->filled('sort_by') && in_array($request->sort_by, ['tanggal', 'nama_produk', 'harga', 'quantity'])) {
            $direction = $request->input('sort_direction', 'asc') === 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sort_by, $direction);
        } else {
            $query->orderBy('id', 'desc'); // Default: terbaru
        }

        $cashiers = $query->paginate(10)->withQueryString();

        return view('cashier', compact('cashiers'));
    }

    /**
     * Simpan transaksi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_produk' => 'required|string|max:255',
            'kode_produk' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        Cashier::create($request->only('tanggal', 'nama_produk', 'kode_produk', 'harga', 'quantity'));

        return redirect()->route('cashier.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    /**
     * Perbarui data transaksi.
     */
    public function update(Request $request, Cashier $cashier)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_produk' => 'required|string|max:255',
            'kode_produk' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $cashier->update($request->only('tanggal', 'nama_produk', 'kode_produk', 'harga', 'quantity'));

        return redirect()->route('cashier.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    /**
     * Hapus transaksi.
     */
    public function destroy(Cashier $cashier)
    {
        $cashier->delete();

        return redirect()->route('cashier.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}
