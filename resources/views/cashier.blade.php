<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cashier') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Tambah Transaksi -->
        <form action="{{ route('cashier.store') }}" method="POST" class="mb-6 bg-white p-6 rounded shadow">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="date" name="tanggal" class="border rounded p-2" required placeholder="Tanggal">
                <input type="text" name="nama_produk" class="border rounded p-2" required placeholder="Nama Produk">
                <input type="text" name="kode_produk" class="border rounded p-2" required placeholder="Kode Produk">
                <input type="number" name="harga" class="border rounded p-2" required placeholder="Harga">
                <input type="number" name="quantity" class="border rounded p-2" required placeholder="Kuantitas">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah</button>
            </div>
        </form>

        <!-- Pencarian & Sorting -->
        <form method="GET" class="mb-4 flex gap-2 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="border rounded p-2">
            <select name="sort_by" class="border rounded p-2">
                <option value="">Urutkan</option>
                <option value="tanggal">Tanggal</option>
                <option value="nama_produk">Nama Produk</option>
                <option value="harga">Harga</option>
                <option value="quantity">Kuantitas</option>
            </select>
            <select name="sort_direction" class="border rounded p-2">
                <option value="asc">Naik</option>
                <option value="desc">Turun</option>
            </select>
            <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded">Terapkan</button>
        </form>

        <!-- Tabel Transaksi -->
        <div class="overflow-x-auto bg-white p-4 rounded shadow">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="w-8">Pilih</th>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Produk</th>
                        <th>Kode</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cashiers as $index => $item)
                        <tr class="text-center border-t">
                            <td class="text-center">
                                <input type="checkbox"
                                       class="item-checkbox"
                                       data-total="{{ $item->harga * $item->quantity }}"
                                       onchange="updateSelectedTotal()">
                            </td>
                            <td>{{ $index + $cashiers->firstItem() }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->nama_produk }}</td>
                            <td>{{ $item->kode_produk }}</td>
                            <td>Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="item-total">Rp{{ number_format($item->harga * $item->quantity, 0, ',', '.') }}</td>
                            <td class="flex justify-center gap-2 mt-1">
                                <button onclick="openEditModal({{ $item->id }})" class="text-blue-500">Edit</button>
                                <form action="{{ route('cashier.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t">
                        <td colspan="7" class="text-right font-bold pr-4">Total Terpilih:</td>
                        <td id="selected-total" class="text-center font-bold">Rp0</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-4">
                {{ $cashiers->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Edit Transaksi</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editId">

                <input type="date" id="editTanggal" name="tanggal" class="w-full mb-3 border rounded p-2" required>
                <input type="text" id="editNama" name="nama_produk" class="w-full mb-3 border rounded p-2" required>
                <input type="text" id="editKode" name="kode_produk" class="w-full mb-3 border rounded p-2" required>
                <input type="number" id="editHarga" name="harga" class="w-full mb-3 border rounded p-2" required>
                <input type="number" id="editQuantity" name="quantity" class="w-full mb-3 border rounded p-2" required>

                <div class="flex justify-between">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS untuk modal dan perhitungan total -->
    <script>
        function openEditModal(id) {
            const item = @json($cashiers->keyBy('id'));
            const data = item[id];

            document.getElementById('editId').value = id;
            document.getElementById('editTanggal').value = data.tanggal;
            document.getElementById('editNama').value = data.nama_produk;
            document.getElementById('editKode').value = data.kode_produk;
            document.getElementById('editHarga').value = data.harga;
            document.getElementById('editQuantity').value = data.quantity;

            document.getElementById('editForm').action = `/cashier/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        function updateSelectedTotal() {
            const checkboxes = document.querySelectorAll('.item-checkbox:checked');
            let total = 0;

            checkboxes.forEach(checkbox => {
                total += parseFloat(checkbox.dataset.total);
            });

            document.getElementById('selected-total').textContent = 'Rp' + formatNumber(total);
        }

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }

        // Optional: Add select all functionality
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.createElement('input');
            selectAllCheckbox.type = 'checkbox';
            selectAllCheckbox.onchange = function() {
                const checkboxes = document.querySelectorAll('.item-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateSelectedTotal();
            };

            const th = document.querySelector('thead tr th:first-child');
            th.appendChild(selectAllCheckbox);
        });
    </script>
</x-app-layout>
