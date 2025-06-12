<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductPrice;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $query = Production::query();

        // 🔍 Filter pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', '%' . $search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $search . '%')
                  ->orWhere('suplier', 'like', '%' . $search . '%');
            });
        }

        // 📅 Filter tanggal masuk
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_masuk', $request->input('tanggal'));
        }

        // ↕️ Sorting dinamis
        $allowedSorts = ['tanggal_masuk', 'harga_per_pcs', 'jumlah_barang', 'created_at'];
        $sortBy = in_array($request->input('sort_by'), $allowedSorts) ? $request->input('sort_by') : 'created_at';
        $sortDirection = $request->input('sort_direction') === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $sortDirection);

        // 📄 Pagination dengan query string tetap terbawa
        $productions = $query->paginate(10)->withQueryString();

        // Produk Price (jika ingin ditampilkan juga di tab lain)
        $productPrices = ProductPrice::orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('production', compact('productions', 'productPrices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk'   => 'required|date',
            'nama_produk'     => 'required|string|max:255',
            'kode_barang'     => 'required|string|max:100|unique:productions,kode_barang',
            'harga_per_pcs'   => 'required|integer|min:0',
            'jumlah'          => 'required|integer|min:1',
            'suplier'         => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
        ]);

        Production::create([
            'tanggal_masuk'   => $request->tanggal_masuk,
            'nama_produk'     => $request->nama_produk,
            'kode_barang'     => $request->kode_barang,
            'harga_per_pcs'   => $request->harga_per_pcs,
            'jumlah_barang'   => $request->jumlah,
            'suplier'         => $request->suplier,
            'deskripsi'       => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Data produksi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $production = Production::findOrFail($id);

        $request->validate([
            'tanggal_masuk'   => 'required|date',
            'nama_produk'     => 'required|string|max:255',
            'kode_barang'     => 'required|string|max:100|unique:productions,kode_barang,' . $production->id,
            'harga_per_pcs'   => 'required|integer|min:0',
            'jumlah'          => 'required|integer|min:1',
            'suplier'         => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
        ]);

        // 🚫 Validasi jumlah minimal
        $used = ProductPrice::where('kode_barang', $production->kode_barang)->sum('jumlah');
        if ($request->jumlah < $used) {
            return redirect()->back()->with('error', 'Jumlah produksi tidak boleh kurang dari jumlah yang sudah digunakan pada Product Price.');
        }

        $production->update([
            'tanggal_masuk'   => $request->tanggal_masuk,
            'nama_produk'     => $request->nama_produk,
            'kode_barang'     => $request->kode_barang,
            'harga_per_pcs'   => $request->harga_per_pcs,
            'jumlah_barang'   => $request->jumlah,
            'suplier'         => $request->suplier,
            'deskripsi'       => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Data produksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $production = Production::findOrFail($id);

        $hasRelation = ProductPrice::where('kode_barang', $production->kode_barang)->exists();
        if ($hasRelation) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus produksi karena ada data Product Price yang terkait.');
        }

        $production->delete();

        return redirect()->back()->with('success', 'Data produksi berhasil dihapus!');
    }
}
