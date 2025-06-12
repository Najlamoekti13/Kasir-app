<?php

namespace App\Http\Controllers;

use App\Models\ProductPrice;
use App\Models\Production;
use Illuminate\Http\Request;

class ProductPriceController extends Controller
{
    // Menampilkan data product price & produksi
    public function index(Request $request)
    {
        // Query untuk ProductPrice
        $query = ProductPrice::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('kode_barang', 'like', "%$searchTerm%")
                  ->orWhere('deskripsi', 'like', "%$searchTerm%")
                  ->orWhere('nama_produk', 'like', "%$searchTerm%")
                  ->orWhere('harga', 'like', "%$searchTerm%");
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Validasi kolom sort_by
        $allowedSortFields = ['tanggal', 'harga', 'jumlah', 'kode_barang'];
        $sortBy = in_array($request->sort_by, $allowedSortFields) ? $request->sort_by : 'tanggal';
        $sortDirection = ($request->sort_direction === 'desc') ? 'desc' : 'asc';

        $query->orderBy($sortBy, $sortDirection);

        // Ambil data dengan pagination
        $productPrices = $query->paginate(10)->withQueryString();

        // Query untuk Production (tambahkan pagination juga)
        $productionQuery = Production::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $productionQuery->where(function ($q) use ($searchTerm) {
                $q->where('kode_barang', 'like', "%$searchTerm%")
                  ->orWhere('nama_produk', 'like', "%$searchTerm%")
                  ->orWhere('suplier', 'like', "%$searchTerm%");
            });
        }

        if ($request->filled('tanggal')) {
            $productionQuery->whereDate('tanggal_masuk', $request->tanggal);
        }

        $productions = $productionQuery->orderBy('tanggal_masuk', 'desc')->paginate(10)->withQueryString();

        return view('production', compact('productPrices', 'productions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'kode_barang'  => 'required|exists:productions,kode_barang',
            'harga'        => 'required|integer|min:0',
            'jumlah'       => 'required|integer|min:1',
            'deskripsi'    => 'nullable|string',
        ]);

        $stok = Production::where('kode_barang', $request->kode_barang)->sum('jumlah_barang');
        $terpakai = ProductPrice::where('kode_barang', $request->kode_barang)->sum('jumlah');

        if (($terpakai + $request->jumlah) > $stok) {
            return back()->with('error', 'Jumlah melebihi stok produksi yang tersedia.');
        }

        $production = Production::where('kode_barang', $request->kode_barang)->first();
        if (!$production) {
            return back()->with('error', 'Data produksi tidak ditemukan untuk kode barang ini.');
        }

        ProductPrice::create([
            'tanggal'      => $request->tanggal,
            'kode_barang'  => $request->kode_barang,
            'nama_produk'  => $production->nama_produk,
            'harga'        => $request->harga,
            'jumlah'       => $request->jumlah,
            'deskripsi'    => $request->deskripsi,
        ]);

        return back()->with('success', 'Data harga berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $productPrice = ProductPrice::findOrFail($id);

        $request->validate([
            'tanggal'      => 'required|date',
            'kode_barang'  => 'required|exists:productions,kode_barang',
            'harga'        => 'required|integer|min:0',
            'jumlah'       => 'required|integer|min:1',
            'deskripsi'    => 'nullable|string',
        ]);

        $stok = Production::where('kode_barang', $request->kode_barang)->sum('jumlah_barang');
        $terpakai = ProductPrice::where('kode_barang', $request->kode_barang)
            ->where('id', '!=', $productPrice->id)
            ->sum('jumlah');

        if (($terpakai + $request->jumlah) > $stok) {
            return back()->with('error', 'Jumlah melebihi stok produksi yang tersedia.');
        }

        $production = Production::where('kode_barang', $request->kode_barang)->first();
        if (!$production) {
            return back()->with('error', 'Data produksi tidak ditemukan untuk kode barang ini.');
        }

        $productPrice->update([
            'tanggal'      => $request->tanggal,
            'kode_barang'  => $request->kode_barang,
            'nama_produk'  => $production->nama_produk,
            'harga'        => $request->harga,
            'jumlah'       => $request->jumlah,
            'deskripsi'    => $request->deskripsi,
        ]);

        return back()->with('success', 'Data harga berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $productPrice = ProductPrice::findOrFail($id);
        $productPrice->delete();

        return back()->with('success', 'Data harga berhasil dihapus.');
    }

    public function edit($id)
    {
        $productPrice = ProductPrice::findOrFail($id);
        return response()->json($productPrice);
    }
}
