@extends('layouts.staf')

@section('title', 'Kasir POS - Penjualan Langsung (K-07)')

@section('styles')
<style>
    .pos-layout { display: grid; grid-template-columns: 1.6fr 1.2fr; gap: 20px; align-items: start; }
    .kategori-tabs { display: flex; gap: 8px; margin-bottom: 16px; overflow-x: auto; padding-bottom: 6px; }
    .kategori-tab { padding: 6px 14px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 20px; text-decoration: none; font-size: 13px; font-weight: 600; color: #475569; white-space: nowrap; }
    .kategori-tab.active { background: #6aa332; color: #ffffff; border-color: #6aa332; }

    .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 14px; }
    .menu-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; cursor: pointer; transition: 0.15s; position: relative; }
    .menu-card:hover { border-color: #6aa332; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .menu-card.out-of-stock { opacity: 0.55; cursor: not-allowed; }
    .menu-nama { font-weight: 700; font-size: 14px; color: #0f172a; margin-bottom: 4px; }
    .menu-harga { font-weight: 800; font-size: 15px; color: #6aa332; margin-bottom: 8px; }
    .menu-stok { font-size: 11px; font-weight: 600; }
    .stok-tersedia { color: #16a34a; }
    .stok-menipis { color: #d97706; }
    .stok-habis { color: #dc2626; }

    .cart-box { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; position: sticky; top: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.04); }
    .cart-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
    .cart-item-name { font-weight: 700; color: #0f172a; }
    .cart-item-price { color: #64748b; font-size: 12px; }
    .cart-qty-ctrl { display: flex; align-items: center; gap: 6px; }
    .btn-qty { width: 26px; height: 26px; background: #e2e8f0; border: none; border-radius: 4px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .btn-qty:hover { background: #cbd5e1; }
    
    .quick-cash { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 6px; }
    .btn-cash { padding: 4px 8px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 11px; font-weight: 600; cursor: pointer; }
    .btn-cash:hover { background: #e2e8f0; }
</style>
@endsection

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
        <div>
            <h1 style="margin: 0; font-size: 22px;">Kasir POS - Penjualan Langsung (K-07)</h1>
            <p style="margin: 4px 0 0; color: #64748b; font-size: 14px;">Melayani pesanan langsung di outlet, kalkulasi kembalian, dan pemotongan stok otomatis.</p>
        </div>
    </div>

    <div class="pos-layout">
        <!-- Kolom Kiri: Menu Jus & Produk -->
        <div>
            <!-- Kategori Tabs -->
            <div class="kategori-tabs">
                <a href="{{ route('panel.staf.kasir.index') }}" class="kategori-tab {{ !request('kategori_id') ? 'active' : '' }}">
                    Semua Kategori
                </a>
                @foreach($kategoriList as $kat)
                    <a href="{{ route('panel.staf.kasir.index', ['kategori_id' => $kat->id]) }}" class="kategori-tab {{ request('kategori_id') == $kat->id ? 'active' : '' }}">
                        {{ $kat->nama }}
                    </a>
                @endforeach
            </div>

            <!-- Pencarian Produk -->
            <div style="margin-bottom: 14px;">
                <form method="GET" action="{{ route('panel.staf.kasir.index') }}">
                    @if(request('kategori_id'))
                        <input type="hidden" name="kategori_id" value="{{ request('kategori_id') }}">
                    @endif
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Ketik nama jus / menu..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px;">
                </form>
            </div>

            <!-- Grid Menu -->
            <div class="menu-grid">
                @forelse($daftarProduk as $produk)
                    @php
                        $isHabis = $produk->stok <= 0;
                    @endphp
                    <div class="menu-card {{ $isHabis ? 'out-of-stock' : '' }}" 
                         onclick="{{ !$isHabis ? "tambahKeKeranjang({$produk->id}, '{$produk->nama}', {$produk->harga}, {$produk->stok})" : '' }}">
                        <div>
                            <div class="menu-nama">{{ $produk->nama }}</div>
                            <div class="menu-harga">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                        </div>
                        <div class="menu-stok {{ $isHabis ? 'stok-habis' : ($produk->stok <= 5 ? 'stok-menipis' : 'stok-tersedia') }}">
                            @if($isHabis)
                                ✕ Stok Habis
                            @elseif($produk->stok <= 5)
                                ⚠ Sisa {{ $produk->stok }} cup
                            @else
                                ✓ Stok: {{ $produk->stok }} cup
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; padding: 40px; text-align: center; color: #94a3b8; background: #fff; border-radius: 10px;">
                        Tidak ada menu yang sesuai dengan pencarian atau kategori ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Kanan: Keranjang Kasir (POS Cart) -->
        <div>
            <div class="cart-box">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 12px;">
                    <h3 style="margin: 0; font-size: 16px; color: #0f172a;">🛒 Pesanan Aktif</h3>
                    <button type="button" onclick="kosongkanKeranjang()" style="background: none; border: none; color: #ef4444; font-size: 12px; font-weight: 600; cursor: pointer;">Kosongkan</button>
                </div>

                <form method="POST" action="{{ route('panel.staf.kasir.simpan') }}" id="formKasir">
                    @csrf

                    <!-- Item List Container -->
                    <div id="cartItemsList" style="max-height: 280px; overflow-y: auto; margin-bottom: 14px;">
                        <p id="cartEmptyText" style="text-align: center; color: #94a3b8; padding: 30px 0; font-size: 13px;">
                            Pilih menu di sebelah kiri untuk ditambahkan ke kasir.
                        </p>
                    </div>

                    <!-- Hidden Inputs Container -->
                    <div id="hiddenInputs"></div>

                    <!-- Rincian Total -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; border-radius: 8px; margin-bottom: 14px;">
                        <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 800; color: #0f172a;">
                            <span>TOTAL BAYAR:</span>
                            <span id="labelTotal">Rp 0</span>
                        </div>
                    </div>

                    <!-- Form Data Pembeli (Opsional) -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 12px;">
                        <input type="text" name="nama_pembeli" placeholder="Nama Pelanggan" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px;">
                        <input type="text" name="telepon_pembeli" placeholder="No. WA (Opsional)" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px;">
                    </div>

                    <!-- Metode Pembayaran -->
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #475569;">Metode Pembayaran:</label>
                        <select name="metode_bayar" id="selectMetodeBayar" onchange="toggleMetodeBayar()" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; font-weight: 600;">
                            <option value="tunai">💵 Uang Tunai (Cash)</option>
                            <option value="qris">📱 QRIS OMYU</option>
                            <option value="transfer">🏦 Transfer Bank</option>
                        </select>
                    </div>

                    <!-- Input Tunai & Kembalian -->
                    <div id="boxTunai" style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #475569;">Uang Diterima (Rp):</label>
                        <input type="number" name="uang_diterima" id="inputUangDiterima" placeholder="Contoh: 50000" oninput="hitungKembalian()" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; font-weight: 700;">
                        
                        <div class="quick-cash">
                            <button type="button" class="btn-cash" onclick="isiUangPas()">Uang Pas</button>
                            <button type="button" class="btn-cash" onclick="tambahUang(10000)">+10.000</button>
                            <button type="button" class="btn-cash" onclick="tambahUang(20000)">+20.000</button>
                            <button type="button" class="btn-cash" onclick="tambahUang(50000)">+50.000</button>
                            <button type="button" class="btn-cash" onclick="tambahUang(100000)">+100.000</button>
                        </div>

                        <div style="margin-top: 10px; display: flex; justify-content: space-between; font-size: 14px; font-weight: 700; color: #166534;">
                            <span>KEMBALIAN:</span>
                            <span id="labelKembalian">Rp 0</span>
                        </div>
                    </div>

                    <!-- Tombol Simpan Transaksi -->
                    <button type="submit" id="btnSimpan" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 15px; font-weight: 800; text-align: center;" disabled>
                        ✓ SIMPAN & SELESAIKAN TRANSAKSI
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let keranjang = {};

    function tambahKeKeranjang(id, nama, harga, maxStok) {
        if (!keranjang[id]) {
            keranjang[id] = { id: id, nama: nama, harga: harga, qty: 1, maxStok: maxStok };
        } else {
            if (keranjang[id].qty < maxStok) {
                keranjang[id].qty += 1;
            } else {
                alert('Jumlah melebihi stok yang tersedia (' + maxStok + ' cup).');
            }
        }
        renderKeranjang();
    }

    function ubahQty(id, delta) {
        if (!keranjang[id]) return;
        keranjang[id].qty += delta;
        if (keranjang[id].qty <= 0) {
            delete keranjang[id];
        } else if (keranjang[id].qty > keranjang[id].maxStok) {
            keranjang[id].qty = keranjang[id].maxStok;
            alert('Maksimal stok tercapai.');
        }
        renderKeranjang();
    }

    function kosongkanKeranjang() {
        keranjang = {};
        renderKeranjang();
    }

    function renderKeranjang() {
        const listContainer = document.getElementById('cartItemsList');
        const hiddenContainer = document.getElementById('hiddenInputs');
        const emptyText = document.getElementById('cartEmptyText');
        const labelTotal = document.getElementById('labelTotal');
        const btnSimpan = document.getElementById('btnSimpan');

        const keys = Object.keys(keranjang);

        if (keys.length === 0) {
            listContainer.innerHTML = '<p id="cartEmptyText" style="text-align: center; color: #94a3b8; padding: 30px 0; font-size: 13px;">Pilih menu di sebelah kiri untuk ditambahkan ke kasir.</p>';
            hiddenContainer.innerHTML = '';
            labelTotal.innerText = 'Rp 0';
            btnSimpan.disabled = true;
            hitungKembalian();
            return;
        }

        let html = '';
        let hiddenHtml = '';
        let total = 0;
        let index = 0;

        keys.forEach(k => {
            const item = keranjang[k];
            const lineTotal = item.harga * item.qty;
            total += lineTotal;

            html += `
                <div class="cart-item">
                    <div>
                        <div class="cart-item-name">${item.nama}</div>
                        <div class="cart-item-price">Rp ${item.harga.toLocaleString('id-ID')} &times; ${item.qty} = <strong>Rp ${lineTotal.toLocaleString('id-ID')}</strong></div>
                    </div>
                    <div class="cart-qty-ctrl">
                        <button type="button" class="btn-qty" onclick="ubahQty(${item.id}, -1)">-</button>
                        <span style="font-weight: 700; min-width: 20px; text-align: center;">${item.qty}</span>
                        <button type="button" class="btn-qty" onclick="ubahQty(${item.id}, 1)">+</button>
                    </div>
                </div>
            `;

            hiddenHtml += `
                <input type="hidden" name="items[${index}][id]" value="${item.id}">
                <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
            `;
            index++;
        });

        listContainer.innerHTML = html;
        hiddenContainer.innerHTML = hiddenHtml;
        labelTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
        btnSimpan.disabled = false;

        hitungKembalian();
    }

    function getTotalBelanja() {
        let total = 0;
        Object.keys(keranjang).forEach(k => {
            total += (keranjang[k].harga * keranjang[k].qty);
        });
        return total;
    }

    function hitungKembalian() {
        const total = getTotalBelanja();
        const uangInput = parseFloat(document.getElementById('inputUangDiterima').value) || 0;
        const kembalian = Math.max(0, uangInput - total);
        document.getElementById('labelKembalian').innerText = 'Rp ' + kembalian.toLocaleString('id-ID');
    }

    function isiUangPas() {
        const total = getTotalBelanja();
        document.getElementById('inputUangDiterima').value = total;
        hitungKembalian();
    }

    function tambahUang(nominal) {
        let current = parseFloat(document.getElementById('inputUangDiterima').value) || 0;
        document.getElementById('inputUangDiterima').value = current + nominal;
        hitungKembalian();
    }

    function toggleMetodeBayar() {
        const metode = document.getElementById('selectMetodeBayar').value;
        const boxTunai = document.getElementById('boxTunai');
        if (metode === 'tunai') {
            boxTunai.style.display = 'block';
        } else {
            boxTunai.style.display = 'none';
        }
    }
</script>
@endsection

