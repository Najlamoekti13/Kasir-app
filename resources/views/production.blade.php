<x-app-layout>
    {{-- ✅ Header Umum Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📦 Production Management
        </h2>
    </x-slot>

    {{-- ✅ Container Umum untuk seluruh konten --}}
    <div class="container mx-auto p-6">
        {{-- ✅ Grid 2 kolom untuk Card Produksi dan Harga Produk --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- 🟦 === Card 1: Produksi === --}}
            <div class="bg-white shadow rounded-2xl p-4">
                <h2 class="text-xl font-semibold mb-2">📋 Input Produksi</h2>

                {{-- 🟦 Form untuk input data produksi baru --}}
                <form action="{{ route('productions.store') }}" method="POST" class="space-y-2">
                    @csrf
                    <input type="date" name="tanggal_masuk" class="w-full border rounded p-2" required>
                    <input type="text" name="nama_produk" placeholder="Nama Produk" class="w-full border rounded p-2" required>
                    <input type="text" name="kode_barang" placeholder="Kode Barang" class="w-full border rounded p-2" required>
                    <input type="number" name="harga_per_pcs" placeholder="Harga / Pcs" class="w-full border rounded p-2" required>
                    <input type="number" name="jumlah" placeholder="Jumlah" class="w-full border rounded p-2" required>
                    <input type="text" name="suplier" placeholder="Suplier" class="w-full border rounded p-2" required>
                    <textarea name="deskripsi" placeholder="Deskripsi" class="w-full border rounded p-2"></textarea>
                    <button type="submit" class="w-full bg-blue-600 text-white rounded p-2">Simpan Produksi</button>
                </form>

                <hr class="my-4">

                <h3 class="font-semibold mb-2">🔍 Filter & Sorting Produksi:</h3>
                <form method="GET" class="flex flex-col md:flex-row md:items-end gap-3 mb-4">
                    <div class="flex flex-col">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama / kode / suplier" class="border rounded p-2 w-full">
                    </div>
                    <div class="flex flex-col">
                        <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}" class="border rounded p-2 w-full">
                    </div>
                    <div class="flex flex-col">
                        <select name="sort_by" id="sort_by" class="border rounded p-2 w-full">
                            <option value="">-- Pilih --</option>
                            <option value="tanggal_masuk" {{ request('sort_by') == 'tanggal_masuk' ? 'selected' : '' }}>Tanggal</option>
                            <option value="harga_per_pcs" {{ request('sort_by') == 'harga_per_pcs' ? 'selected' : '' }}>Harga</option>
                            <option value="jumlah_barang" {{ request('sort_by') == 'jumlah_barang' ? 'selected' : '' }}>Jumlah</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <select name="sort_direction" id="sort_direction" class="border rounded p-2 w-full">
                            <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>ASC (Naik)</option>
                            <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>DESC (Turun)</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-1">Terapkan</button>
                    </div>
                </form>

                {{-- 🟦 Tabel daftar produksi --}}
                <div class="overflow-auto max-h-96">
                    <table class="w-full text-sm" id="productionTable">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th>
                                    <input type="checkbox" id="card1-select-all" class="rounded">
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Suplier</th>
                                <th>Tanggal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productions as $p)
                            <tr class="border-b">
                                <td>
                                    <input type="checkbox" class="card1-checkbox rounded" >
                                </td>
                                <td>{{ $p->nama_produk }}</td>
                                <td>{{ $p->kode_barang }}</td>
                                <td class="p-2 font-semibold text-blue-600">Rp {{ number_format($p->harga_per_pcs, 0, ',', '.') }}</td>
                                <td>{{ $p->jumlah_barang }}</td>
                                <td class="card1-subtotal" data-quantity="{{ $p->jumlah_barang }}" data-price="{{ $p->harga_per_pcs }}">
                                    Rp {{ number_format($p->jumlah_barang * $p->harga_per_pcs, 0, ',', '.') }}
                                </td>
                                <td>{{ $p->suplier }}</td>
                                <td>{{ $p->tanggal_masuk }}</td>
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center gap-x-2">
                                        {{-- 🔵 Tombol edit produksi  --}}
                                        <button
                                            class="text-blue-600 edit-production-btn"
                                            data-id="{{ $p->id }}"
                                            data-nama="{{ $p->nama_produk }}"
                                            data-kode="{{ $p->kode_barang }}"
                                            data-harga="{{ $p->harga_per_pcs }}"
                                            data-jumlah="{{ $p->jumlah_barang }}"
                                            data-suplier="{{ $p->suplier }}"
                                            data-deskripsi="{{ $p->deskripsi }}"
                                            data-tanggal="{{ $p->tanggal_masuk }}"
                                            title="Edit"
                                        >✏️</button>

                                        <form action="{{ route('productions.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600" title="Hapus">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-gray-500 py-2">Tidak ada data produksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- 🔢 Total Harga Terpilih --}}
                <div id="card1-total-container" class="mt-4 text-right text-lg font-semibold text-black-700">
                    Total Terpilih: <span id="card1-total" class="text-blue-600">Rp 0</span>
                </div>
            </div>

            {{-- 🟩 === Card 2: Harga Produk === --}}
            <div class="bg-white shadow rounded-2xl p-4">
                <h2 class="text-xl font-semibold mb-4">💲 Harga Produk</h2>

                {{-- 🟩 Form input harga produk baru --}}
                <form action="{{ route('product_prices.store') }}" method="POST" class="space-y-2 mb-4">
                    @csrf
                    <input type="date" name="tanggal" class="w-full border rounded p-2" required>
                    <select name="kode_barang" class="w-full border rounded p-2" required>
                        <option value="">-- Pilih Kode Barang --</option>
                        @foreach($productions as $p)
                            <option value="{{ $p->kode_barang }}">{{ $p->nama_produk }} - {{ $p->kode_barang }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="harga" placeholder="Harga Total" class="w-full border rounded p-2" required>
                    <input type="number" name="jumlah" placeholder="Jumlah" class="w-full border rounded p-2" required>
                    <textarea name="deskripsi" placeholder="Deskripsi" class="w-full border rounded p-2"></textarea>
                    <button type="submit" class="w-full bg-green-600 text-white rounded p-2">Simpan Harga</button>
                </form>

                <hr class="my-4">

                {{-- 🟩 Filter & Sorting Controls --}}
                <h3 class="font-semibold mb-3">🔍 Filter & Pencarian:</h3>

                {{-- Real-time Search --}}
                <div class="mb-3">
                    <input type="text" id="searchPrice" placeholder="🔍 Cari kode barang, harga, deskripsi..."
                           class="w-full border rounded p-2 focus:border-blue-500 focus:outline-none">
                </div>

                {{-- Server-side Filter Form --}}
                <form method="GET" action="{{ url()->current() }}" class="mb-4">
                    {{-- Preserve existing parameters for card 1 --}}
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                    <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                    <input type="hidden" name="sort_direction" value="{{ request('sort_direction') }}">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Filter Tanggal:</label>
                            <input type="date" name="tanggal_price" value="{{ request('tanggal_price') }}"
                                   class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Urutkan berdasarkan:</label>
                            <select name="sort_by_price" class="w-full border rounded p-2">
                                <option value="">-- Pilih --</option>
                                <option value="tanggal" {{ request('sort_by_price') == 'tanggal' ? 'selected' : '' }}>Tanggal</option>
                                <option value="harga" {{ request('sort_by_price') == 'harga' ? 'selected' : '' }}>Harga</option>
                                <option value="jumlah" {{ request('sort_by_price') == 'jumlah' ? 'selected' : '' }}>Jumlah</option>
                                <option value="kode_barang" {{ request('sort_by_price') == 'kode_barang' ? 'selected' : '' }}>Kode Barang</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Urutan:</label>
                            <select name="sort_direction_price" class="w-full border rounded p-2">
                                <option value="asc" {{ request('sort_direction_price') == 'asc' ? 'selected' : '' }}>ASC (Naik)</option>
                                <option value="desc" {{ request('sort_direction_price') == 'desc' ? 'selected' : '' }}>DESC (Turun)</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
                                Terapkan Filter
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ url()->current() }}?{{ http_build_query(request()->only(['search', 'tanggal', 'sort_by', 'sort_direction'])) }}"
                           class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                            Reset Filter Harga
                        </a>
                        <button type="button" id="clearSearch" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">
                            Clear Pencarian
                        </button>
                    </div>
                </form>

                {{-- 🟩 Info Status --}}
                <div id="searchStatus" class="mb-3 text-sm text-gray-600 hidden">
                    Menampilkan hasil pencarian untuk: <span id="searchKeyword" class="font-semibold"></span>
                </div>

                {{-- 🟩 Tabel daftar harga produk --}}
                <div class="overflow-auto max-h-96">
                    <table class="w-full text-sm" id="priceTable">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="p-2">
                                    <input type="checkbox" id="card2-select-all" class="rounded">
                                </th>
                                <th class="p-2">Kode</th>
                                <th class="p-2">Harga</th>
                                <th class="p-2">Jumlah</th>
                                <th class="p-2">Deskripsi</th>
                                <th class="p-2">Tanggal</th>
                                <th class="p-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="priceTableBody">
                            @forelse($productPrices as $item)
                            <tr class="border-b searchable-row hover:bg-gray-50" data-search-text="{{ strtolower($item->kode_barang . ' ' . $item->harga . ' ' . $item->jumlah . ' ' . ($item->deskripsi ?? '') . ' ' . $item->tanggal) }}">
                                <td class="p-2">
                                    <input type="checkbox" class="card2-checkbox rounded" data-total="{{ $item->harga }}">
                                </td>
                                <td class="p-2 font-medium">{{ $item->kode_barang }}</td>
                                <td class="p-2 font-semibold text-green-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="p-2">{{ $item->jumlah }}</td>
                                <td class="p-2">{{ $item->deskripsi ?? '-' }}</td>
                                <td class="p-2">{{ $item->tanggal }}</td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        {{-- 🟩 Tombol edit harga --}}
                                        <button class="edit-price-btn text-blue-600 hover:text-blue-800 text-lg" title="Edit"
                                                data-id="{{ $item->id }}"
                                                data-tanggal="{{ $item->tanggal }}"
                                                data-kode="{{ $item->kode_barang }}"
                                                data-harga="{{ $item->harga }}"
                                                data-jumlah="{{ $item->jumlah }}"
                                                data-deskripsi="{{ $item->deskripsi }}">
                                            ✏️
                                        </button>

                                        {{-- 🟩 Tombol hapus harga --}}
                                        <form action="{{ route('product_prices.destroy', $item->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:text-red-800 text-lg" title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="noDataRow">
                                <td colspan="7" class="text-center text-gray-500 py-4">Tidak ada data harga produk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- No results message for search --}}
                    <div id="noSearchResults" class="text-center text-gray-500 py-4 hidden">
                        Tidak ada hasil yang cocok dengan pencarian "<span id="noResultsKeyword"></span>"
                    </div>
                </div>

                {{-- 🟩 Total harga terpilih berdasarkan checkbox --}}
                <div class="mt-4 flex justify-between items-center">
                    <div id="searchInfo" class="text-sm text-gray-600">
                        Total data: <span id="totalRows">{{ $productPrices->total() ?? count($productPrices) }}</span> |
                        Ditampilkan: <span id="visibleRows">{{ $productPrices->count() ?? count($productPrices) }}</span>
                    </div>
                    <div class="text-right font-semibold text-lg">
                        Total Terpilih: <span id="card2-total" class="text-green-600">Rp 0</span>
                    </div>
                </div>

                {{-- Pagination --}}
                @if(method_exists($productPrices, 'links'))
                <div class="mt-4">
                    {{ $productPrices->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 🟦 Modal Edit Produksi (Card 1) --}}
    <div id="editProductionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded shadow-md w-[90%] max-w-md relative">
            <h2 class="text-lg font-bold mb-4">Edit Produksi</h2>
            <form method="POST" id="editProductionForm" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editProductionId">

                <div class="mb-3">
                    <label class="block mb-1">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" id="editProductionTanggal" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Nama Produk</label>
                    <input type="text" name="nama_produk" id="editProductionName" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Kode Barang</label>
                    <input type="text" name="kode_barang" id="editProductionKode" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Jumlah</label>
                    <input type="number" name="jumlah" id="editProductionQuantity" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Harga per pcs</label>
                    <input type="number" name="harga_per_pcs" id="editProductionPrice" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Suplier</label>
                    <input type="text" name="suplier" id="editProductionSuplier" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="editProductionDeskripsi" class="w-full border px-3 py-2 rounded"></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeProductionModal()" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 🟩 Modal Edit Harga Produk (Card 2) --}}
    <div id="editPriceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">✏️ Edit Harga Produk</h2>
            <form method="POST" id="editPriceForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editPriceId" name="id">

                <div class="mb-3">
                    <label class="block mb-2">Tanggal</label>
                    <input type="date" id="editPriceTanggal" name="tanggal" class="w-full border rounded p-2" required>
                </div>

                <div class="mb-3">
                    <label class="block mb-2">Kode Barang</label>
                    <select name="kode_barang" id="editPriceKode" class="w-full border rounded p-2" required>
                        <option value="">-- Pilih Kode Barang --</option>
                        @foreach($productions as $p)
                            <option value="{{ $p->kode_barang }}">{{ $p->nama_produk }} - {{ $p->kode_barang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block mb-2">Harga</label>
                    <input type="number" id="editPriceHarga" name="harga" class="w-full border rounded p-2" required>
                </div>

                <div class="mb-3">
                    <label class="block mb-2">Jumlah</label>
                    <input type="number" id="editPriceJumlah" name="jumlah" class="w-full border rounded p-2" required>
                </div>

                <div class="mb-3">
                    <label class="block mb-2">Deskripsi</label>
                    <textarea id="editPriceDeskripsi" name="deskripsi" class="w-full border rounded p-2"></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closePriceModal()" class="px-4 py-2 bg-gray-400 text-white rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 📜 JavaScript Scripts --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // ===== CARD 1: PRODUKSI SCRIPTS =====
            const card1Checkboxes = document.querySelectorAll(".card1-checkbox");
            const card1SelectAll = document.getElementById("card1-select-all");
            const card1TotalDisplay = document.getElementById("card1-total");

            // Event untuk checkbox per baris Card 1
            card1Checkboxes.forEach(cb => {
                cb.addEventListener("change", calculateCard1Total);
            });

            // Event untuk checkbox pilih semua Card 1
            if (card1SelectAll) {
                card1SelectAll.addEventListener("change", function () {
                    card1Checkboxes.forEach(cb => {
                        cb.checked = card1SelectAll.checked;
                    });
                    calculateCard1Total();
                });
            }

            function calculateCard1Total() {
                let total = 0;
                card1Checkboxes.forEach(cb => {
                    if (cb.checked) {
                        const row = cb.closest("tr");
                        const subtotalEl = row.querySelector(".card1-subtotal");
                        const quantity = parseFloat(subtotalEl.dataset.quantity);
                        const price = parseFloat(subtotalEl.dataset.price);
                        total += quantity * price;
                    }
                });
                card1TotalDisplay.textContent = "Rp " + new Intl.NumberFormat('id-ID').format(total);
            }

            // ===== CARD 2: HARGA PRODUK SCRIPTS =====
            const searchPriceInput = document.getElementById('searchPrice');
            const priceTableBody = document.getElementById('priceTableBody');
            const searchStatus = document.getElementById('searchStatus');
            const searchKeyword = document.getElementById('searchKeyword');
            const noSearchResults = document.getElementById('noSearchResults');
            const noResultsKeyword = document.getElementById('noResultsKeyword');
            const clearSearchBtn = document.getElementById('clearSearch');
            const totalRows = document.getElementById('totalRows');
            const visibleRows = document.getElementById('visibleRows');

            let allRows = [];
            let currentVisibleRows = [];

            // Initialize rows data
            function initializeRowsData() {
                const rows = document.querySelectorAll('#priceTableBody tr.searchable-row');
                allRows = Array.from(rows);
                currentVisibleRows = [...allRows];
                updateRowsCount();
            }

            // Update rows count display
            function updateRowsCount() {
                if (totalRows) totalRows.textContent = allRows.length;
                if (visibleRows) visibleRows.textContent = currentVisibleRows.length;
            }

            // Real-time search functionality
            if (searchPriceInput) {
                searchPriceInput.addEventListener('input', function() {
                    const keyword = this.value.toLowerCase().trim();

                    if (keyword === '') {
                        // Show all rows
                        allRows.forEach(row => {
                            row.style.display = '';
                        });
                        currentVisibleRows = [...allRows];

                        // Hide search status
                        searchStatus.classList.add('hidden');
                        noSearchResults.classList.add('hidden');
                    } else {
                        // Filter rows
                        currentVisibleRows = [];
                        let hasResults = false;

                        allRows.forEach(row => {
                            const searchText = row.dataset.searchText || '';
                            const shouldShow = searchText.includes(keyword);

                            if (shouldShow) {
                                row.style.display = '';
                                currentVisibleRows.push(row);
                                hasResults = true;
                            } else {
                                row.style.display = 'none';
                            }
                        });

                        // Update search status
                        searchKeyword.textContent = keyword;
                        searchStatus.classList.remove('hidden');

                        // Show/hide no results message
                        if (!hasResults) {
                            noResultsKeyword.textContent = keyword;
                            noSearchResults.classList.remove('hidden');
                        } else {
                            noSearchResults.classList.add('hidden');
                        }
                    }

                    updateRowsCount();
                    updateCheckboxEvents();
                    calculateCard2Total();
                });
            }

            // Clear search button
            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function() {
                    searchPriceInput.value = '';
                    searchPriceInput.dispatchEvent(new Event('input'));
                    searchPriceInput.focus();
                });
            }

            // Checkbox functionality
            const card2SelectAll = document.getElementById("card2-select-all");
            const card2TotalDisplay = document.getElementById("card2-total");

            function updateCheckboxEvents() {
                // Get all visible checkboxes
                const visibleCheckboxes = currentVisibleRows.map(row =>
                    row.querySelector('.card2-checkbox')
                ).filter(cb => cb !== null);

                // Update individual checkbox events
                visibleCheckboxes.forEach(cb => {
                    cb.removeEventListener('change', calculateCard2Total); // Remove old listener
                    cb.addEventListener('change', calculateCard2Total); // Add new listener
                });

                // Update select all functionality
                if (card2SelectAll) {
                    // Remove old event listeners
                    const newSelectAll = card2SelectAll.cloneNode(true);
                    card2SelectAll.parentNode.replaceChild(newSelectAll, card2SelectAll);

                                        // Add new event listener
                    newSelectAll.addEventListener('change', function() {
                        const isChecked = this.checked;
                        visibleCheckboxes.forEach(cb => {
                            cb.checked = isChecked;
                        });
                        calculateCard2Total();
                    });
                }
            }

            // Calculate total for selected items in Card 2
            function calculateCard2Total() {
                let total = 0;
                const checkboxes = document.querySelectorAll('.card2-checkbox:checked');

                checkboxes.forEach(cb => {
                    const rowTotal = parseFloat(cb.dataset.total) || 0;
                    total += rowTotal;
                });

                card2TotalDisplay.textContent = "Rp " + new Intl.NumberFormat('id-ID').format(total);
            }

            // Initialize rows data when page loads
            initializeRowsData();
            updateCheckboxEvents();

            // ===== MODAL FUNCTIONS =====
            // Production Modal Functions
            const editProductionBtns = document.querySelectorAll('.edit-production-btn');
            const editProductionModal = document.getElementById('editProductionModal');
            const editProductionForm = document.getElementById('editProductionForm');

            editProductionBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const route = "{{ route('productions.update', ':id') }}".replace(':id', id);

                    editProductionForm.action = route;
                    document.getElementById('editProductionId').value = id;
                    document.getElementById('editProductionTanggal').value = this.dataset.tanggal;
                    document.getElementById('editProductionName').value = this.dataset.nama;
                    document.getElementById('editProductionKode').value = this.dataset.kode;
                    document.getElementById('editProductionPrice').value = this.dataset.harga;
                    document.getElementById('editProductionQuantity').value = this.dataset.jumlah;
                    document.getElementById('editProductionSuplier').value = this.dataset.suplier;
                    document.getElementById('editProductionDeskripsi').value = this.dataset.deskripsi;

                    editProductionModal.classList.remove('hidden');
                });
            });

            function closeProductionModal() {
                editProductionModal.classList.add('hidden');
            }

            // Price Modal Functions
            const editPriceBtns = document.querySelectorAll('.edit-price-btn');
            const editPriceModal = document.getElementById('editPriceModal');
            const editPriceForm = document.getElementById('editPriceForm');

            editPriceBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const route = "{{ route('product_prices.update', ':id') }}".replace(':id', id);

                    editPriceForm.action = route;
                    document.getElementById('editPriceId').value = id;
                    document.getElementById('editPriceTanggal').value = this.dataset.tanggal;
                    document.getElementById('editPriceKode').value = this.dataset.kode;
                    document.getElementById('editPriceHarga').value = this.dataset.harga;
                    document.getElementById('editPriceJumlah').value = this.dataset.jumlah;
                    document.getElementById('editPriceDeskripsi').value = this.dataset.deskripsi || '';

                    editPriceModal.classList.remove('hidden');
                });
            });

            function closePriceModal() {
                editPriceModal.classList.add('hidden');
            }

            // Close modals when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === editProductionModal) {
                    closeProductionModal();
                }
                if (event.target === editPriceModal) {
                    closePriceModal();
                }
            });

            // Success message fade out
            setTimeout(() => {
                const successMessage = document.querySelector('.alert-success');
                if (successMessage) {
                    successMessage.style.transition = 'opacity 1s';
                    successMessage.style.opacity = '0';
                    setTimeout(() => successMessage.remove(), 1000);
                }
            }, 3000);
        });
    </script>
</x-app-layout>
