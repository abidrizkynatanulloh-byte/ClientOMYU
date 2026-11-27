@extends('layouts.staf')

@section('title', 'Kasir POS - Penjualan Langsung')

@section('styles')
<style>
    .kasir-head { display: flex; flex-wrap: wrap; gap: 10px; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; }
    .kasir-head h1 { font-size: 20px; font-weight: 900; color: #0f172a; }
    .kasir-head p { font-size: 12px; color: #64748b; margin-top: 3px; }
    .head-link { font-size: 12px; font-weight: 800; color: #15803d; text-decoration: none; background: #eaf8ef; border-radius: 10px; padding: 8px 12px; }

    .pos-layout { display: grid; gap: 16px; align-items: start; }
    .menu-panel, .cart-panel { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 14px; }

    .status-pesan { min-height: 18px; margin-top: 8px; font-size: 12px; font-weight: 700; color: #64748b; }
    .status-pesan.sukses { color: #15803d; }
    .status-pesan.error { color: #b91c1c; }

    .kategori-tabs { display: flex; gap: 7px; overflow-x: auto; padding-bottom: 6px; margin: 12px 0; scrollbar-width: none; }
    .kategori-tabs::-webkit-scrollbar { display: none; }
    .kategori-tab { flex-shrink: 0; text-decoration: none; font-size: 11px; font-weight: 800; color: #475569; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 999px; padding: 6px 12px; }
    .kategori-tab.active { background: #198754; color: #ffffff; border-color: #198754; }

    .cari-form { display: flex; gap: 8px; margin-bottom: 12px; }
    .cari-form input { flex: 1; min-width: 0; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 13px; outline: none; font-family: inherit; }
    .cari-form input:focus { border-color: #198754; }
    .cari-form button { border: 0; border-radius: 10px; background: #eaf8ef; color: #166534; padding: 0 14px; font-size: 12px; font-weight: 800; cursor: pointer; }

    .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; }
    .menu-card { text-align: left; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 13px; padding: 10px; cursor: pointer; font-family: inherit; transition: .15s; }
    .menu-card:hover:not(.menu-card-habis) { border-color: #198754; box-shadow: 0 4px 12px rgba(15, 81, 50, .08); }
    .menu-card-habis { opacity: .55; cursor: not-allowed; }
    .menu-img { width: 100%; height: 88px; object-fit: cover; border-radius: 9px; margin-bottom: 8px; background: #f1f5f9; }
    .menu-nama { font-size: 13px; font-weight: 800; color: #0f172a; line-height: 1.3; }
    .menu-harga { font-size: 14px; font-weight: 900; color: #15803d; margin-top: 6px; }
    .varian-select { width: 100%; margin-top: 7px; border: 1px solid #cbd5e1; border-radius: 8px; padding: 5px 7px; font-size: 11px; font-weight: 700; color: #334155; background: #f8fafc; font-family: inherit; outline: none; }
    .varian-select:focus { border-color: #198754; }
    .menu-stok { font-size: 10px; font-weight: 800; margin-top: 4px; }
    .stok-tersedia { color: #16a34a; }
    .stok-menipis { color: #d97706; }
    .stok-habis { color: #dc2626; }

    .cart-head { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 10px; }
    .cart-head h2 { font-size: 15px; font-weight: 900; color: #0f172a; }
    .btn-kosongkan { border: 0; background: none; color: #dc2626; font-size: 11px; font-weight: 800; cursor: pointer; }
    #cartItemsList { min-height: 70px; max-height: 280px; overflow-y: auto; }
    .cart-empty { text-align: center; color: #94a3b8; font-size: 12px; padding: 28px 0; }
    .cart-item { display: flex; justify-content: space-between; gap: 8px; padding: 9px 0; border-bottom: 1px solid #f1f5f9; }
    .cart-item-name { font-size: 12px; font-weight: 800; color: #0f172a; }
    .cart-item-price { font-size: 11px; color: #64748b; margin-top: 2px; }
    .cart-qty { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
    .btn-qty { width: 25px; height: 25px; border: 0; border-radius: 7px; background: #e8f5e9; color: #166534; font-weight: 900; cursor: pointer; }
    .cart-qty span { min-width: 18px; text-align: center; font-size: 12px; font-weight: 800; }

    .ringkasan { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 11px; padding: 11px; margin: 12px 0; }
    .ringkasan-row { display: flex; justify-content: space-between; font-size: 12px; font-weight: 700; color: #475569; padding: 3px 0; }
    .ringkasan-row.final { font-size: 16px; font-weight: 900; color: #0f5132; border-top: 1px dashed #cbd5e1; margin-top: 6px; padding-top: 8px; }

    .field { margin-bottom: 10px; }
    .field label { display: block; font-size: 11px; font-weight: 800; color: #475569; margin-bottom: 4px; }
    .field input, .field select, .field textarea { width: 100%; border: 1px solid #cbd5e1; border-radius: 9px; padding: 9px 10px; font-size: 13px; font-family: inherit; outline: none; }
    .field input:focus, .field select:focus, .field textarea:focus { border-color: #198754; }
    .field-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .wajib { color: #dc2626; }
    .quick-cash { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 7px; }
    .btn-cash { border: 1px solid #cbd5e1; background: #f8fafc; border-radius: 8px; padding: 5px 9px; font-size: 11px; font-weight: 800; cursor: pointer; }
    .kembalian-row { display: flex; justify-content: space-between; margin-top: 9px; font-size: 13px; font-weight: 900; color: #166534; }
    .form-error { display: none; background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; border-radius: 9px; padding: 9px 11px; font-size: 12px; font-weight: 700; margin-bottom: 10px; }
    .form-error.show { display: block; }
    .btn-simpan { width: 100%; border: 0; border-radius: 11px; background: #198754; color: #ffffff; padding: 12px; font-size: 14px; font-weight: 900; cursor: pointer; }
    .btn-simpan:disabled { background: #cbd5e1; cursor: not-allowed; }

    @media (min-width: 768px) {
        .pos-layout { grid-template-columns: 1.35fr 1fr; gap: 20px; }
        .menu-panel, .cart-panel { padding: 18px; }
        .cart-panel { position: sticky; top: 20px; }
    }
</style>
@endsection

@section('content')
<div class="kasir-head">
    <div>
        <h1>Kasir POS - Penjualan Langsung</h1>
        <p>Catat penjualan di outlet, hitung kembalian, dan potong stok secara otomatis.</p>
    </div>
    <a href="{{ route('panel.staf.pesanan.index') }}" class="head-link">Pesanan Masuk</a>
</div>

<div class="pos-layout">
    <section class="menu-panel">
        <div class="status-pesan" id="statusPesan" role="status"></div>

        <div class="kategori-tabs">
            <a href="{{ route('panel.staf.kasir.index') }}" class="kategori-tab {{ ! request('kategori_id') ? 'active' : '' }}">Semua Kategori</a>
            @foreach($kategoriList as $kategori)
                <a href="{{ route('panel.staf.kasir.index', ['kategori_id' => $kategori->id]) }}" class="kategori-tab {{ request('kategori_id') == $kategori->id ? 'active' : '' }}">{{ $kategori->nama }}</a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('panel.staf.kasir.index') }}" class="cari-form">
            @if(request('kategori_id'))
                <input type="hidden" name="kategori_id" value="{{ request('kategori_id') }}">
            @endif
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama menu...">
            <button type="submit">Cari</button>
        </form>

        <div class="menu-grid" id="menuGrid">
            @forelse($daftarProduk as $produk)
                @php
                    $stokHabis = $produk->stok <= 0;
                    $kelasStok = $stokHabis ? 'stok-habis' : ($produk->stok <= 5 ? 'stok-menipis' : 'stok-tersedia');
                @endphp
                <div class="menu-card {{ $stokHabis ? 'menu-card-habis' : '' }}" role="button"
                     tabindex="{{ $stokHabis ? -1 : 0 }}" aria-disabled="{{ $stokHabis ? 'true' : 'false' }}"
                     data-id="{{ $produk->id }}" data-nama="{{ $produk->nama }}" data-harga="{{ $produk->harga }}" data-stok="{{ $produk->stok }}">
                    @if($produk->gambar)
                        <img class="menu-img" src="{{ Storage::url($produk->gambar) }}" alt="{{ $produk->nama }}">
                    @endif
                    <div class="menu-nama">{{ $produk->nama }}</div>
                    @if($produk->varian->isNotEmpty())
                        <select class="varian-select" aria-label="Varian {{ $produk->nama }}" {{ $stokHabis ? 'disabled' : '' }}>
                            @foreach($produk->varian as $varian)
                                <option value="{{ $varian->id }}" data-nama="{{ $varian->nama }}" data-harga="{{ $varian->harga }}">
                                    {{ $varian->nama }} — Rp {{ number_format($varian->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    <div class="menu-harga">Rp {{ number_format($produk->varian->first()?->harga ?: $produk->harga, 0, ',', '.') }}</div>
                    <div class="menu-stok {{ $kelasStok }}">
                        @if($stokHabis)
                            Stok habis
                        @else
                            Sisa {{ $produk->stok }} cup
                        @endif
                    </div>
                </div>
            @empty
                <div class="cart-empty" style="grid-column: 1 / -1;">Tidak ada menu yang sesuai dengan pencarian atau kategori ini.</div>
            @endforelse
        </div>
    </section>

    <section class="cart-panel">
        <form method="POST" action="{{ route('panel.staf.kasir.simpan') }}" id="formKasir" enctype="multipart/form-data">
            @csrf
            <div class="cart-head">
                <h2>Pesanan Aktif</h2>
                <button type="button" class="btn-kosongkan" id="kosongkanKeranjang">Kosongkan</button>
            </div>

            <div id="cartItemsList">
                <div class="cart-empty" id="cartEmptyText">Pilih menu untuk mulai transaksi.</div>
            </div>
            <div id="hiddenInputs"></div>

            <div class="ringkasan">
                <div class="ringkasan-row"><span>Subtotal</span><span id="labelSubtotal">Rp 0</span></div>
                <div class="ringkasan-row final"><span>Total bayar</span><span id="labelTotal">Rp 0</span></div>
            </div>

            <div class="field-2">
                <div class="field">
                    <label for="namaPembeli">Nama pelanggan</label>
                    <input type="text" id="namaPembeli" name="nama_pembeli" maxlength="100" placeholder="Opsional">
                </div>
                <div class="field">
                    <label for="teleponPembeli">No. WhatsApp</label>
                    <input type="text" id="teleponPembeli" name="telepon_pembeli" maxlength="20" placeholder="Opsional">
                </div>
            </div>

            <div class="field">
                <label for="catatanPembeli">Catatan</label>
                <textarea id="catatanPembeli" name="catatan" rows="2" maxlength="255" placeholder="Contoh: Less ice, tambah granat"></textarea>
            </div>

            <div class="field">
                <label for="metodeBayar">Metode pembayaran</label>
                <select id="metodeBayar" name="metode_bayar">
                    <option value="tunai">Tunai</option>
                    <option value="qris">QRIS</option>
                    <option value="transfer">Transfer bank</option>
                </select>
            </div>

            <div id="boxTunai">
                <div class="field">
                    <label for="uangDiterima">Uang diterima (Rp)</label>
                    <input type="number" id="uangDiterima" name="uang_diterima" min="0" step="1" inputmode="numeric" placeholder="0">
                </div>
                <div class="quick-cash">
                    <button type="button" class="btn-cash" data-nominal="pas">Uang pas</button>
                    <button type="button" class="btn-cash" data-nominal="10000">+10.000</button>
                    <button type="button" class="btn-cash" data-nominal="20000">+20.000</button>
                    <button type="button" class="btn-cash" data-nominal="50000">+50.000</button>
                    <button type="button" class="btn-cash" data-nominal="100000">+100.000</button>
                </div>
                <div class="kembalian-row"><span>Kembalian</span><span id="labelKembalian">Rp 0</span></div>
            </div>

            <div id="boxReferensi" style="display: none;">
                <div class="field">
                    <label for="referensiBayar">Referensi pembayaran <span class="wajib">*wajib</span></label>
                    <input type="text" id="referensiBayar" name="referensi_bayar" maxlength="100" placeholder="ID transaksi QRIS / nomor transfer">
                </div>
                <div class="field">
                    <label for="buktiBayar">Bukti pembayaran (opsional)</label>
                    <input type="file" id="buktiBayar" name="bukti_bayar" accept="image/jpeg,image/png,image/webp">
                </div>
            </div>

            <div class="form-error" id="formError" role="alert"></div>
            <button type="submit" class="btn-simpan" id="btnSimpan" disabled
                    data-confirm="Simpan transaksi kasir ini dan selesaikan pembayaran?"
                    data-confirm-title="Simpan Transaksi Kasir?"
                    data-confirm-btn="Ya, Simpan Transaksi"
                    data-confirm-type="success">Simpan & selesaikan transaksi</button>
        </form>
    </section>
</div>
@endsection

@section('scripts')
<script>
(function () {
    const rupaUang = new Intl.NumberFormat('id-ID');
    const keranjang = Object.create(null);
    const menuGrid = document.getElementById('menuGrid');
    const cartItemsList = document.getElementById('cartItemsList');
    const hiddenInputs = document.getElementById('hiddenInputs');
    const labelSubtotal = document.getElementById('labelSubtotal');
    const labelTotal = document.getElementById('labelTotal');
    const labelKembalian = document.getElementById('labelKembalian');
    const btnSimpan = document.getElementById('btnSimpan');
    const metodeBayar = document.getElementById('metodeBayar');
    const uangDiterima = document.getElementById('uangDiterima');
    const referensiBayar = document.getElementById('referensiBayar');
    const formError = document.getElementById('formError');
    const statusPesan = document.getElementById('statusPesan');

    function escapeHtml(nilai) {
        return String(nilai).replace(/[&<>"']/g, function (karakter) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[karakter];
        });
    }

    function tampilPesan(pesan, tipe) {
        statusPesan.textContent = pesan;
        statusPesan.className = 'status-pesan' + (tipe ? ' ' + tipe : '');
    }

    function tampilError(pesan) {
        formError.textContent = pesan;
        formError.classList.add('show');
    }

    function totalKeranjang() {
        return Object.keys(keranjang).reduce(function (total, id) {
            return total + (keranjang[id].harga * keranjang[id].qty);
        }, 0);
    }

    function hitungKembalian() {
        const total = totalKeranjang();
        const dibayar = parseFloat(uangDiterima.value) || 0;
        labelKembalian.textContent = 'Rp ' + rupaUang.format(Math.max(0, dibayar - total));
    }

    function tambahKeKeranjang(produk) {
        const id = Number(produk.id);
        const varianId = produk.varianId ? Number(produk.varianId) : null;
        const kunci = id + '_' + (varianId || '0');
        const harga = Number(produk.harga);
        const stok = Number(produk.stok);

        if (!id || !Number.isFinite(harga) || !Number.isFinite(stok) || stok < 1) {
            tampilPesan('Data produk tidak valid.', 'error');
            return;
        }

        const sudahDipakai = Object.values(keranjang)
            .filter(function (item) { return item.id === id; })
            .reduce(function (total, item) { return total + item.qty; }, 0);

        if (sudahDipakai >= stok) {
            tampilPesan('Stok ' + produk.nama + ' hanya tersedia ' + stok + ' cup untuk semua varian.', 'error');
            return;
        }

        if (!keranjang[kunci]) {
            keranjang[kunci] = {
                id: id,
                varianId: varianId,
                varianNama: produk.varianNama || 'Reguler',
                nama: produk.nama,
                harga: harga,
                stok: stok,
                qty: 1
            };
        } else if (keranjang[kunci].qty >= keranjang[kunci].stok) {
            tampilPesan('Stok ' + keranjang[kunci].nama + ' hanya tersedia ' + keranjang[kunci].stok + ' cup.', 'error');
            return;
        } else {
            keranjang[kunci].qty += 1;
        }

        renderKeranjang();
    }

    function renderKeranjang() {
        const ids = Object.keys(keranjang);
        let html = '';
        let hidden = '';
        let index = 0;

        if (ids.length === 0) {
            cartItemsList.innerHTML = '<div class="cart-empty">Pilih menu untuk mulai transaksi.</div>';
            hiddenInputs.innerHTML = '';
        } else {
            ids.forEach(function (kunci) {
                const item = keranjang[kunci];
                const subtotal = item.harga * item.qty;
                const namaTampil = item.nama + (item.varianNama && item.varianNama !== 'Reguler' ? ' · ' + item.varianNama : '');

                html += '<div class="cart-item">' +
                    '<div><div class="cart-item-name">' + escapeHtml(namaTampil) + '</div>' +
                    '<div class="cart-item-price">Rp ' + rupaUang.format(item.harga) + ' × ' + item.qty + ' = <strong>Rp ' + rupaUang.format(subtotal) + '</strong></div></div>' +
                    '<div class="cart-qty">' +
                    '<button type="button" class="btn-qty" data-key="' + kunci + '" data-delta="-1">−</button>' +
                    '<span>' + item.qty + '</span>' +
                    '<button type="button" class="btn-qty" data-key="' + kunci + '" data-delta="1">+</button>' +
                    '</div></div>';

                hidden += '<input type="hidden" name="items[' + index + '][id]" value="' + item.id + '">' +
                    '<input type="hidden" name="items[' + index + '][varian_id]" value="' + (item.varianId || '') + '">' +
                    '<input type="hidden" name="items[' + index + '][qty]" value="' + item.qty + '">';
                index += 1;
            });

            cartItemsList.innerHTML = html;
            hiddenInputs.innerHTML = hidden;
        }

        const total = totalKeranjang();
        labelSubtotal.textContent = 'Rp ' + rupaUang.format(total);
        labelTotal.textContent = 'Rp ' + rupaUang.format(total);
        btnSimpan.disabled = ids.length === 0;
        hitungKembalian();
    }

    menuGrid.addEventListener('change', function (event) {
        const pilihan = event.target.closest('.varian-select');
        if (!pilihan) {
            return;
        }

        const kartu = pilihan.closest('.menu-card');
        const opsi = pilihan.selectedOptions[0];
        const labelHarga = kartu?.querySelector('.menu-harga');

        if (labelHarga && opsi) {
            labelHarga.textContent = 'Rp ' + rupaUang.format(Number(opsi.dataset.harga || kartu.dataset.harga));
        }
    });

    menuGrid.addEventListener('click', function (event) {
        if (event.target.closest('.varian-select')) {
            return;
        }

        const kartu = event.target.closest('.menu-card');
        if (!kartu || kartu.classList.contains('menu-card-habis') || kartu.getAttribute('aria-disabled') === 'true') {
            return;
        }

        const pilihan = kartu.querySelector('.varian-select');
        const opsi = pilihan?.selectedOptions[0];

        tambahKeKeranjang({
            id: kartu.dataset.id,
            nama: kartu.dataset.nama,
            varianId: pilihan?.value || null,
            varianNama: opsi?.dataset.nama || 'Reguler',
            harga: opsi?.dataset.harga || kartu.dataset.harga,
            stok: kartu.dataset.stok
        });
        tampilPesan(kartu.dataset.nama + ' (' + (opsi?.dataset.nama || 'Reguler') + ') ditambahkan ke keranjang.', 'sukses');
    });

    menuGrid.addEventListener('keydown', function (event) {
        if (event.key !== 'Enter' && event.key !== ' ') {
            return;
        }

        const kartu = event.target.closest('.menu-card');
        if (!kartu || event.target.closest('.varian-select')) {
            return;
        }

        event.preventDefault();
        kartu.click();
    });

    cartItemsList.addEventListener('click', function (event) {
        const tombol = event.target.closest('.btn-qty');
        if (!tombol) {
            return;
        }

        const kunci = tombol.dataset.key;
        if (!keranjang[kunci]) {
            return;
        }

        const delta = Number(tombol.dataset.delta);
        if (delta < 0) {
            keranjang[kunci].qty -= 1;
            if (keranjang[kunci].qty < 1) {
                delete keranjang[kunci];
            }
        } else {
            const sudahDipakai = Object.values(keranjang)
                .filter(function (item) { return item.id === keranjang[kunci].id; })
                .reduce(function (total, item) { return total + item.qty; }, 0);

            if (sudahDipakai >= keranjang[kunci].stok) {
                tampilPesan('Stok ' + keranjang[kunci].nama + ' hanya tersedia ' + keranjang[kunci].stok + ' cup untuk semua varian.', 'error');
                return;
            }

            keranjang[kunci].qty += 1;
        }

        renderKeranjang();
    });

    document.getElementById('kosongkanKeranjang').addEventListener('click', function () {
        Object.keys(keranjang).forEach(function (id) { delete keranjang[id]; });
        renderKeranjang();
        tampilPesan('Keranjang dikosongkan.');
    });

    document.querySelectorAll('.btn-cash').forEach(function (tombol) {
        tombol.addEventListener('click', function () {
            const nominal = tombol.dataset.nominal;
            if (nominal === 'pas') {
                uangDiterima.value = totalKeranjang();
            } else {
                uangDiterima.value = (parseFloat(uangDiterima.value) || 0) + Number(nominal);
            }
            hitungKembalian();
            formError.classList.remove('show');
        });
    });

    uangDiterima.addEventListener('input', function () {
        hitungKembalian();
        formError.classList.remove('show');
    });

    function sinkronMetodeBayar() {
        const metode = metodeBayar.value;
        const tunai = metode === 'tunai';
        document.getElementById('boxTunai').style.display = tunai ? 'block' : 'none';
        document.getElementById('boxReferensi').style.display = tunai ? 'none' : 'block';
        uangDiterima.disabled = !tunai;
        referensiBayar.required = !tunai;
        if (!tunai) {
            uangDiterima.value = '';
            hitungKembalian();
        }
        formError.classList.remove('show');
    }

    metodeBayar.addEventListener('change', sinkronMetodeBayar);

    document.getElementById('formKasir').addEventListener('submit', function (event) {
        formError.classList.remove('show');

        if (Object.keys(keranjang).length === 0) {
            event.preventDefault();
            tampilError('Pilih minimal satu menu sebelum menyimpan transaksi.');
            return;
        }

        const metode = metodeBayar.value;
        const total = totalKeranjang();

        if (metode === 'tunai') {
            const dibayar = parseFloat(uangDiterima.value);
            if (!Number.isFinite(dibayar) || dibayar < total) {
                event.preventDefault();
                tampilError('Uang diterima kurang dari total pembayaran (' + rupaUang.format(total) + ').');
                uangDiterima.focus();
                return;
            }
        } else if (!referensiBayar.value.trim()) {
            event.preventDefault();
            tampilError('Referensi pembayaran wajib diisi untuk QRIS/transfer.');
            referensiBayar.focus();
        }
    });

    sinkronMetodeBayar();
    renderKeranjang();
})();
</script>
@endsection
