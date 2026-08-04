<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir POS — POS QRIS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        ::-webkit-scrollbar { width: 5px; height: 4px; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
    </style>
</head>

<body class="font-sans bg-slate-100 text-slate-900 h-screen overflow-hidden flex flex-col m-0 p-0">

    {{-- HEADER --}}
    <div class="h-14.5 bg-white border-b border-slate-200 flex items-center px-5 gap-3 shadow-sm shrink-0">
        <div class="w-8.5 h-8.5 bg-linear-to-br from-blue-600 to-violet-600 rounded-[9px] flex items-center justify-center text-base shadow-[0_3px_8px_rgba(37,99,235,0.25)]">🛒</div>
        <div>
            <div class="text-[15px] font-extrabold text-slate-900">Kasir POS</div>
            <div class="text-[10px] text-slate-400">POS QRIS System</div>
        </div>
        <div class="flex-1"></div>
        <div class="font-mono text-[13px] font-medium text-slate-500 bg-slate-50 border border-slate-200 rounded-lg py-1.5 px-3">🕐 <span id="currentTime">--:--</span></div>
        <div class="text-xs font-semibold text-slate-500 bg-slate-50 border border-slate-200 rounded-lg py-1.5 px-3 flex items-center gap-1.5">👤 {{ session('user_name') }}</div>
        <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-1.5 py-1.5 px-3.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-500 no-underline text-xs font-semibold hover:border-blue-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">← Kembali</a>
    </div>

    {{-- MAIN --}}
    <div class="grid grid-cols-[1fr_400px] flex-1 overflow-hidden">

        {{-- PRODUCTS PANEL --}}
        <div class="bg-slate-100 p-4 overflow-y-auto flex flex-col gap-3.5">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm pointer-events-none">🔍</span>
                <input type="text" id="searchInput" placeholder="Cari produk..." onkeyup="searchProduct()"
                       class="w-full py-2.5 pl-9.5 pr-3.5 bg-white border border-slate-200 rounded-[10px] text-[13px] text-slate-900 outline-none shadow-sm focus:border-blue-600 transition-colors">
            </div>

            <div class="flex gap-2 overflow-x-auto pb-0.5">
                <button class="category-tab py-1.5 px-4 rounded-full text-xs font-semibold cursor-pointer border whitespace-nowrap transition-colors border-blue-600 bg-blue-600 text-white shadow-[0_3px_10px_rgba(37,99,235,0.3)]"
                        onclick="filterCategory('all', this)">Semua</button>
                @foreach($kategoris as $kategori)
                <button class="category-tab py-1.5 px-4 rounded-full text-xs font-semibold cursor-pointer border whitespace-nowrap shadow-sm transition-colors border-slate-200 bg-white text-slate-500 hover:border-blue-600 hover:text-blue-600"
                        onclick="filterCategory('{{ $kategori->id }}', this)">{{ $kategori->nama }}</button>
                @endforeach
            </div>

            <div class="grid gap-3 grid-cols-[repeat(auto-fill,minmax(148px,1fr))]" id="productsGrid">
                @foreach($produks as $produk)
                <div class="product-card bg-white border border-slate-200 rounded-xl py-3.5 px-3 cursor-pointer text-center shadow-sm hover:border-blue-600 hover:-translate-y-0.5 hover:shadow-[0_6px_20px_rgba(37,99,235,0.12)] transition-all"
                     data-category="{{ $produk->kategori_id }}"
                     data-id="{{ $produk->id }}"
                     data-nama="{{ $produk->nama }}"
                     data-harga="{{ $produk->harga }}"
                     data-stok="{{ $produk->stok }}"
                     onclick="addToCartFromCard(this)">
                    <div class="text-4xl mb-2 leading-none"></div>
                    <div class="product-name font-bold text-[12.5px] mb-1.5 text-slate-900 leading-snug">{{ $produk->nama }}</div>
                    <div class="text-blue-600 font-extrabold text-[13px]">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                    <div class="text-[10px] mt-1 {{ $produk->stok <= 5 ? 'text-red-600 font-semibold' : 'text-slate-400' }}">
                        Stok: {{ $produk->stok }}{{ $produk->stok <= 5 ? ' ⚠️' : '' }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- CART PANEL --}}
        <div class="bg-white border-l border-slate-200 flex flex-col h-full shadow-[-2px_0_8px_rgba(0,0,0,0.04)]">
            <div class="py-3.5 px-4.5 border-b border-slate-200 bg-slate-50">
                <div class="text-sm font-extrabold text-slate-900 mb-1">🛒 Keranjang belanja</div>
                <div class="flex justify-between text-[11px] text-slate-400 font-mono">
                    <span id="transactionNo">Transaksi #{{ $nomorTransaksi }}</span>
                    <span id="transactionTime">--:--</span>
                </div>
            </div>

            <div class="cart-items flex-1 overflow-y-auto p-3" id="cartItems">
                <div class="text-center py-10 px-5 text-slate-400">
                    <div class="text-5xl mb-2.5 opacity-40">🛒</div>
                    <p class="text-[13px] font-semibold mb-1">Keranjang masih kosong</p>
                    <small class="text-[11px]">Pilih produk untuk memulai transaksi</small>
                </div>
            </div>

            <div class="py-3.5 px-4.5 border-t border-slate-200 bg-slate-50">
                <div class="flex justify-between text-[12.5px] text-slate-500 mb-2">
                    <span>Subtotal (<span id="itemCount">0</span> item)</span>
                    <span id="subtotal">Rp 0</span>
                </div>
                <div class="flex justify-between text-base font-extrabold text-slate-900 pt-2.5 border-t border-slate-200">
                    <span>TOTAL</span>
                    <span id="totalAmount" class="text-blue-600">Rp 0</span>
                </div>
            </div>

            <div class="py-3.5 px-4.5 pb-4 border-t border-slate-200 bg-white">
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <button class="payment-btn py-2.5 border-[1.5px] rounded-[9px] cursor-pointer text-xs font-semibold flex flex-col items-center gap-1 transition-colors border-blue-600 bg-blue-50 text-blue-600 shadow-[0_2px_8px_rgba(37,99,235,0.15)]"
                            onclick="selectPayment('tunai', this)">
                        <span class="text-lg">💵</span> Tunai
                    </button>
                    <button class="payment-btn py-2.5 border-[1.5px] rounded-[9px] cursor-pointer text-xs font-semibold flex flex-col items-center gap-1 transition-colors border-slate-200 bg-slate-50 text-slate-500 hover:border-blue-600 hover:text-blue-600 hover:bg-blue-50"
                            onclick="selectPayment('qris', this)">
                        <span class="text-lg">📱</span> QRIS
                    </button>
                </div>
                <button id="checkoutBtn" onclick="processPayment()"
                        class="w-full py-3.5 bg-linear-to-br from-blue-600 to-violet-600 text-white border-0 rounded-[11px] text-sm font-bold cursor-pointer shadow-[0_4px_12px_rgba(37,99,235,0.3)] hover:-translate-y-px hover:shadow-[0_6px_18px_rgba(37,99,235,0.4)] transition-all disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none disabled:cursor-not-allowed disabled:translate-y-0 disabled:hover:translate-y-0">
                    Proses pembayaran →
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL: PAYMENT --}}
    <div class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-1000 items-center justify-center" id="paymentModal">
        <div class="bg-white border border-slate-200 rounded-[18px] p-7 w-105 max-w-[95vw] shadow-[0_20px_60px_rgba(0,0,0,0.15)]">
            <div class="text-center mb-5">
                <h3 class="text-lg font-extrabold text-slate-900">💳 Proses pembayaran</h3>
                <p class="text-xs text-slate-500 mt-1">Pastikan jumlah sudah sesuai sebelum konfirmasi</p>
            </div>

            <div class="bg-slate-50 border border-slate-200 py-4 rounded-xl text-center mb-4">
                <div class="text-[11px] text-slate-500 font-semibold mb-1">Total pembayaran</div>
                <div class="text-[26px] font-extrabold text-slate-900" id="modalTotal">Rp 0</div>
            </div>

            <div class="mb-3.5">
                <label class="block mb-1.5 text-xs font-bold text-slate-500">Metode pembayaran</label>
                <input type="text" id="paymentMethod" readonly
                       class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-[9px] text-sm text-slate-500 outline-none">
            </div>

            <div class="mb-3.5" id="cashInputGroup">
                <label class="block mb-1.5 text-xs font-bold text-slate-500">Jumlah uang diterima</label>
                <input type="number" id="cashReceived" placeholder="Masukkan jumlah uang..." oninput="calculateChange()"
                       class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-[9px] text-sm text-slate-900 outline-none focus:border-blue-600 transition-colors">
                <div class="hidden text-[11px] text-red-600 mt-1.5 font-semibold" id="insufficientCashWarning">⚠️ Jumlah uang yang diterima tidak mencukupi.</div>
            </div>

            <div class="hidden bg-green-50 border border-green-200 py-3.5 rounded-xl text-center mb-4" id="changeDisplay">
                <div class="text-[11px] text-slate-500 font-semibold mb-1">Kembalian</div>
                <div class="text-[26px] font-extrabold text-green-600" id="changeAmount">Rp 0</div>
            </div>

            <div class="flex gap-2.5 mt-1">
                <button onclick="closePaymentModal()"
                        class="flex-1 py-3 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] font-semibold text-slate-500 cursor-pointer hover:border-red-600 hover:text-red-600 transition-colors">Batal</button>
                <button id="confirmPayBtn" onclick="confirmPayment()" disabled
                        class="flex-1 py-3 bg-blue-600 text-white border-0 rounded-[9px] text-[13px] font-bold cursor-pointer shadow-[0_3px_10px_rgba(37,99,235,0.3)] hover:bg-blue-700 transition-colors disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none disabled:cursor-not-allowed">Konfirmasi bayar</button>
            </div>
        </div>
    </div>

    {{-- MODAL: SUCCESS --}}
    <div class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-1000 items-center justify-center" id="successModal">
        <div class="bg-white border border-slate-200 rounded-[18px] p-7 w-105 max-w-[95vw] shadow-[0_20px_60px_rgba(0,0,0,0.15)]">
            <div class="text-[56px] text-center mb-3">✅</div>
            <div class="text-center mb-5">
                <h3 class="text-lg font-extrabold text-slate-900">Transaksi berhasil!</h3>
                <p class="text-xs text-slate-500 mt-1">Pembayaran telah diterima</p>
            </div>

            <div class="bg-slate-50 border border-slate-200 py-4 rounded-xl text-center mb-4">
                <div class="text-[11px] text-slate-500 font-semibold mb-1">Total pembayaran</div>
                <div class="text-[26px] font-extrabold text-slate-900" id="successTotal">Rp 0</div>
            </div>

            <div class="hidden bg-green-50 border border-green-200 py-3.5 rounded-xl text-center mb-4" id="successChange">
                <div class="text-[11px] text-slate-500 font-semibold mb-1">Kembalian</div>
                <div class="text-[26px] font-extrabold text-green-600" id="successChangeAmount">Rp 0</div>
            </div>

            <div class="flex gap-2.5 mt-1">
                <button onclick="cetakStruk()"
                        class="flex-1 py-3 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] font-semibold text-slate-500 cursor-pointer hover:border-blue-600 hover:text-blue-600 transition-colors">
                    🖨 Cetak struk
                </button>
                <button onclick="newTransaction()"
                        class="flex-1 py-3 bg-blue-600 text-white border-0 rounded-[9px] text-[13px] font-bold cursor-pointer shadow-[0_3px_10px_rgba(37,99,235,0.3)] hover:bg-blue-700 transition-colors">
                    + Transaksi baru
                </button>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        let cart = [];
        let selectedPaymentMethod = 'tunai';
        let currentInvoice = '';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // ===== CLOCK =====
        function updateTime() {
            const now  = new Date();
            const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('currentTime').textContent     = time;
            document.getElementById('transactionTime').textContent = time;
        }
        updateTime();
        setInterval(updateTime, 1000);

        // ===== CART =====
        function addToCartFromCard(element) {
            const produk = {
                id:    parseInt(element.getAttribute('data-id')),
                nama:  element.getAttribute('data-nama'),
                harga: parseFloat(element.getAttribute('data-harga')),
                stok:  parseInt(element.getAttribute('data-stok'))
            };
            addToCart(produk);
        }

        function addToCart(produk) {
            const cartItem = cart.find(item => item.id === produk.id);
            if (cartItem) {
                if (cartItem.qty < produk.stok) { cartItem.qty++; }
                else { alert('Stok tidak mencukupi!'); return; }
            } else {
                cart.push({ id: produk.id, nama: produk.nama, harga: parseFloat(produk.harga), stok: produk.stok, qty: 1 });
            }
            updateCartDisplay();
        }

        function updateCartDisplay() {
            const container = document.getElementById('cartItems');

            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-10 px-5 text-slate-400">
                        <div class="text-5xl mb-2.5 opacity-40">🛒</div>
                        <p class="text-[13px] font-semibold mb-1">Keranjang masih kosong</p>
                        <small class="text-[11px]">Pilih produk untuk memulai transaksi</small>
                    </div>`;
            } else {
                container.innerHTML = cart.map((item, i) => `
                    <div class="bg-slate-50 border border-slate-200 py-2.5 px-3.5 rounded-[10px] mb-2 flex items-center gap-2.5 hover:border-blue-600 transition-colors">
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-[12.5px] text-slate-900 whitespace-nowrap overflow-hidden text-ellipsis mb-0.5">${item.nama}</div>
                            <div class="text-[11px] text-slate-500">Rp ${item.harga.toLocaleString('id-ID')} × ${item.qty}
                                = <strong>Rp ${(item.harga * item.qty).toLocaleString('id-ID')}</strong>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button onclick="decreaseQty(${i})" class="w-[26px] h-[26px] border border-slate-200 bg-white text-slate-500 rounded-[7px] cursor-pointer text-sm font-bold flex items-center justify-center hover:border-blue-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">−</button>
                            <span class="w-7 text-center font-extrabold text-[13px]">${item.qty}</span>
                            <button onclick="increaseQty(${i})" class="w-[26px] h-[26px] border border-slate-200 bg-white text-slate-500 rounded-[7px] cursor-pointer text-sm font-bold flex items-center justify-center hover:border-blue-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">+</button>
                            <button onclick="removeItem(${i})" class="w-[26px] h-[26px] bg-red-50 border border-red-200 text-red-600 rounded-[7px] cursor-pointer text-xs flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors">🗑</button>
                        </div>
                    </div>`).join('');
            }
            updateSummary();
        }

        function increaseQty(i) {
            if (cart[i].qty < cart[i].stok) { cart[i].qty++; updateCartDisplay(); }
            else { alert('Stok tidak mencukupi!'); }
        }

        function decreaseQty(i) {
            if (cart[i].qty > 1) { cart[i].qty--; updateCartDisplay(); }
        }

        function removeItem(i) {
            cart.splice(i, 1);
            updateCartDisplay();
        }

        function updateSummary() {
            const total     = cart.reduce((s, item) => s + item.harga * item.qty, 0);
            const itemCount = cart.reduce((s, item) => s + item.qty, 0);
            document.getElementById('itemCount').textContent   = itemCount;
            document.getElementById('subtotal').textContent    = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('totalAmount').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        // ===== PAYMENT METHOD TOGGLE =====
        const paymentBtnBase   = ['border-slate-200', 'bg-slate-50', 'text-slate-500'];
        const paymentBtnActive = ['border-blue-600', 'bg-blue-50', 'text-blue-600', 'shadow-[0_2px_8px_rgba(37,99,235,0.15)]'];

        function selectPayment(method, btn) {
            selectedPaymentMethod = method;
            document.querySelectorAll('.payment-btn').forEach(b => {
                b.classList.remove(...paymentBtnActive);
                b.classList.add(...paymentBtnBase);
            });
            btn.classList.remove(...paymentBtnBase);
            btn.classList.add(...paymentBtnActive);
        }

        // ===== CATEGORY TAB TOGGLE =====
        const catTabBase   = ['border-slate-200', 'bg-white', 'text-slate-500'];
        const catTabActive = ['border-blue-600', 'bg-blue-600', 'text-white', 'shadow-[0_3px_10px_rgba(37,99,235,0.3)]'];

        function filterCategory(category, btn) {
            document.querySelectorAll('.category-tab').forEach(b => {
                b.classList.remove(...catTabActive);
                b.classList.add(...catTabBase);
            });
            btn.classList.remove(...catTabBase);
            btn.classList.add(...catTabActive);

            document.querySelectorAll('.product-card').forEach(card => {
                card.style.display = (category === 'all' || card.dataset.category === category) ? 'block' : 'none';
            });
        }

        function processPayment() {
            if (cart.length === 0) {
                alert('Keranjang belanja masih kosong. Silakan pilih produk terlebih dahulu.');
                return;
            }

            const total = cart.reduce((s, item) => s + item.harga * item.qty, 0);
            document.getElementById('modalTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');

            const methodNames = { tunai: 'Tunai', qris: 'QRIS' };
            document.getElementById('paymentMethod').value = methodNames[selectedPaymentMethod];

            if (selectedPaymentMethod === 'tunai') {
                document.getElementById('cashInputGroup').classList.remove('hidden');
                document.getElementById('cashReceived').value = '';
                document.getElementById('changeDisplay').classList.add('hidden');
                document.getElementById('insufficientCashWarning').classList.add('hidden');
                document.getElementById('confirmPayBtn').disabled = true;
            } else {
                document.getElementById('cashInputGroup').classList.add('hidden');
                document.getElementById('changeDisplay').classList.add('hidden');
                document.getElementById('confirmPayBtn').disabled = false;
            }

            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
        }

        function calculateChange() {
            const total    = cart.reduce((s, item) => s + item.harga * item.qty, 0);
            const received = parseInt(document.getElementById('cashReceived').value) || 0;
            const change   = received - total;

            if (change >= 0) {
                document.getElementById('changeDisplay').classList.remove('hidden');
                document.getElementById('insufficientCashWarning').classList.add('hidden');
                document.getElementById('changeAmount').textContent = 'Rp ' + change.toLocaleString('id-ID');
                document.getElementById('confirmPayBtn').disabled = false;
            } else {
                document.getElementById('changeDisplay').classList.add('hidden');
                document.getElementById('confirmPayBtn').disabled = true;
                if (document.getElementById('cashReceived').value !== '') {
                    document.getElementById('insufficientCashWarning').classList.remove('hidden');
                } else {
                    document.getElementById('insufficientCashWarning').classList.add('hidden');
                }
            }
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            document.getElementById('paymentModal').classList.remove('flex');
        }

        // ===== QRIS STATUS POLLING =====
        const statusUrlTemplate = "{{ route('transaksi.status', ['invoice' => '__INVOICE__']) }}";

        function showStatusToast(message, type = 'info') {
            let toast = document.getElementById('qrisStatusToast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'qrisStatusToast';
                toast.style.cssText = `
                    position: fixed; top: 20px; right: 20px; z-index: 9999;
                    padding: 14px 20px; border-radius: 10px; font-size: 14px;
                    font-weight: 600; box-shadow: 0 4px 14px rgba(0,0,0,0.15);
                    max-width: 320px;
                `;
                document.body.appendChild(toast);
            }
            const colors = {
                info:    { bg: '#eff6ff', color: '#2563eb' },
                success: { bg: '#f0fdf4', color: '#16a34a' },
                warning: { bg: '#fffbeb', color: '#d97706' },
                error:   { bg: '#fef2f2', color: '#dc2626' },
            };
            const c = colors[type] || colors.info;
            toast.style.background = c.bg;
            toast.style.color = c.color;
            toast.textContent = message;
            toast.style.display = 'block';
            return toast;
        }

        function hideStatusToast() {
            const toast = document.getElementById('qrisStatusToast');
            if (toast) toast.style.display = 'none';
        }

        async function pollTransaksiStatus(nomorInvoice, { maxAttempts = 20, intervalMs = 3000 } = {}) {
            const url = statusUrlTemplate.replace('__INVOICE__', nomorInvoice);

            for (let attempt = 1; attempt <= maxAttempts; attempt++) {
                try {
                    const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const json = await res.json();

                    if (json.success && json.status === 'dibayar')    return 'dibayar';
                    if (json.success && json.status === 'dibatalkan') return 'dibatalkan';
                } catch (e) {
                    // gangguan jaringan sesaat, lanjut coba lagi
                }
                await new Promise(r => setTimeout(r, intervalMs));
            }
            return 'pending';
        }

        async function confirmAndWaitQris(nomorInvoice) {
            showStatusToast('Menunggu konfirmasi pembayaran QRIS...', 'info');

            const finalStatus = await pollTransaksiStatus(nomorInvoice);

            if (finalStatus === 'dibayar') {
                hideStatusToast();
                currentInvoice = nomorInvoice;
                document.getElementById('successTotal').textContent = 'Rp ' + cart.reduce((s, item) => s + item.harga * item.qty, 0).toLocaleString('id-ID');
                document.getElementById('successChange').classList.add('hidden');
                document.getElementById('successModal').classList.remove('hidden');
                document.getElementById('successModal').classList.add('flex');
            } else if (finalStatus === 'dibatalkan') {
                showStatusToast('Pembayaran QRIS dibatalkan/kedaluwarsa.', 'error');
            } else {
                showStatusToast('Pembayaran masih diproses. Cek status di Riwayat Transaksi beberapa saat lagi.', 'warning');
            }
        }

        async function confirmPayment() {
            const total    = cart.reduce((s, item) => s + item.harga * item.qty, 0);
            const received = selectedPaymentMethod === 'tunai'
                ? parseInt(document.getElementById('cashReceived').value)
                : total;

            const data = {
                items: cart.map(item => ({ produk_id: item.id, jumlah: item.qty, harga: item.harga })),
                total: total,
                metode_pembayaran: selectedPaymentMethod,
                uang_diterima: received
            };

            try {
                const response = await fetch('{{ route("kasir.transaksi.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (!result.success) { alert(result.message); return; }

                closePaymentModal();

                if (selectedPaymentMethod === 'qris') {
                    if (!result.data || !result.data.snap_token) { alert('Snap token tidak ditemukan'); return; }
                    const nomorInvoice = result.data.nomor_invoice;

                    snap.pay(result.data.snap_token, {
                        onSuccess: () => confirmAndWaitQris(nomorInvoice),
                        onPending: () => confirmAndWaitQris(nomorInvoice),
                        onError:   () => showStatusToast('Pembayaran QRIS gagal.', 'error'),
                        onClose:   () => showStatusToast('Pembayaran dibatalkan oleh pelanggan.', 'warning')
                    });
                } else {
                    currentInvoice = result.data.nomor_invoice;
                    document.getElementById('successTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
                    const change = received - total;
                    document.getElementById('successChange').classList.toggle('hidden', change <= 0);
                    document.getElementById('successChangeAmount').textContent = 'Rp ' + change.toLocaleString('id-ID');
                    document.getElementById('successModal').classList.remove('hidden');
                    document.getElementById('successModal').classList.add('flex');
                }

            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        }

        function cetakStruk() {
            if (currentInvoice) {
                window.open('/kasir/struk/' + currentInvoice, '_blank');
            } else {
                alert('Invoice tidak ditemukan.');
            }
        }

        function newTransaction() {
            cart = [];
            currentInvoice = '';
            updateCartDisplay();
            document.getElementById('successModal').classList.add('hidden');
            document.getElementById('successModal').classList.remove('flex');
        }

        // ===== SEARCH & FILTER =====
        function searchProduct() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.querySelector('.product-name').textContent.toLowerCase();
                card.style.display = name.includes(q) ? 'block' : 'none';
            });
        }
    </script>
</body>
</html>