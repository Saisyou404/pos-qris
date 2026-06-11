<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir POS — POS QRIS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:           #f0f4f8;
            --bg2:          #ffffff;
            --bg3:          #f8fafc;
            --border:       #e2e8f0;
            --accent:       #2563eb;
            --accent-light: #eff6ff;
            --success:      #16a34a;
            --success-light:#f0fdf4;
            --warning:      #d97706;
            --danger:       #dc2626;
            --danger-light: #fef2f2;
            --text:         #0f172a;
            --text2:        #64748b;
            --text3:        #94a3b8;
            --header-h:     58px;
            --shadow:       0 1px 3px rgba(0,0,0,0.07), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:    0 4px 14px rgba(0,0,0,0.08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* ===== HEADER ===== */
        .header {
            height: var(--header-h);
            background: var(--bg2);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 20px; gap: 12px;
            box-shadow: var(--shadow);
            flex-shrink: 0;
        }

        .header-logo {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            box-shadow: 0 3px 8px rgba(37,99,235,0.25);
        }

        .header-title  { font-size: 15px; font-weight: 800; color: var(--text); }
        .header-sub    { font-size: 10px; color: var(--text3); }
        .header-spacer { flex: 1; }

        .header-time {
            font-family: 'DM Mono', monospace;
            font-size: 13px; font-weight: 500;
            color: var(--text2);
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 6px 12px;
        }

        .header-user {
            font-size: 12px; font-weight: 600;
            color: var(--text2);
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 6px 12px;
            display: flex; align-items: center; gap: 6px;
        }

        .back-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 7px 14px;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text2);
            text-decoration: none;
            font-size: 12px; font-weight: 600;
            transition: all 0.15s;
        }
        .back-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }

        /* ===== MAIN LAYOUT ===== */
        .main-container {
            display: grid;
            grid-template-columns: 1fr 400px;
            flex: 1;
            overflow: hidden;
        }

        /* ===== PRODUCTS PANEL ===== */
        .products-panel {
            background: var(--bg);
            padding: 16px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .products-panel::-webkit-scrollbar { width: 4px; }
        .products-panel::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        /* Search */
        .search-wrap { position: relative; }
        .search-wrap input {
            width: 100%;
            padding: 10px 14px 10px 38px;
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 10px;
            font-family: inherit; font-size: 13px;
            color: var(--text);
            outline: none;
            transition: border-color 0.15s;
            box-shadow: var(--shadow);
        }
        .search-wrap input:focus { border-color: var(--accent); }
        .search-icon {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            font-size: 14px; pointer-events: none;
        }

        /* Category Tabs */
        .category-tabs {
            display: flex; gap: 8px;
            overflow-x: auto; padding-bottom: 2px;
        }
        .category-tabs::-webkit-scrollbar { height: 3px; }
        .category-tabs::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        .category-tab {
            padding: 7px 16px;
            border-radius: 99px;
            font-family: inherit;
            font-size: 12px; font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border);
            background: var(--bg2);
            color: var(--text2);
            white-space: nowrap;
            transition: all 0.15s;
            box-shadow: var(--shadow);
        }
        .category-tab:hover  { border-color: var(--accent); color: var(--accent); }
        .category-tab.active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
            box-shadow: 0 3px 10px rgba(37,99,235,0.3);
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(148px, 1fr));
            gap: 12px;
        }

        .product-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 12px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            box-shadow: var(--shadow);
        }
        .product-card:hover {
            border-color: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.12);
        }

        .product-icon {
            font-size: 36px;
            margin-bottom: 8px;
            line-height: 1;
        }

        .product-name {
            font-weight: 700; font-size: 12.5px;
            margin-bottom: 5px; color: var(--text);
            line-height: 1.35;
        }

        .product-price {
            color: var(--accent);
            font-weight: 800; font-size: 13px;
        }

        .product-stock {
            font-size: 10px; color: var(--text3);
            margin-top: 4px;
        }

        .product-stock.low { color: var(--danger); font-weight: 600; }

        /* ===== CART PANEL ===== */
        .cart-panel {
            background: var(--bg2);
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: -2px 0 8px rgba(0,0,0,0.04);
        }

        .cart-header {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            background: var(--bg3);
        }

        .cart-title {
            font-size: 14px; font-weight: 800;
            color: var(--text); margin-bottom: 4px;
        }

        .transaction-info {
            display: flex; justify-content: space-between;
            font-size: 11px; color: var(--text3);
            font-family: 'DM Mono', monospace;
        }

        .cart-items {
            flex: 1; overflow-y: auto;
            padding: 12px;
        }
        .cart-items::-webkit-scrollbar { width: 3px; }
        .cart-items::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        .empty-cart {
            text-align: center; padding: 40px 20px;
            color: var(--text3);
        }
        .empty-cart-icon { font-size: 48px; margin-bottom: 10px; opacity: 0.4; }
        .empty-cart p    { font-size: 13px; font-weight: 600; margin-bottom: 4px; }
        .empty-cart small{ font-size: 11px; }

        .cart-item {
            background: var(--bg3);
            border: 1px solid var(--border);
            padding: 11px 13px;
            border-radius: 10px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: border-color 0.15s;
        }
        .cart-item:hover { border-color: var(--accent); }

        .item-info { flex: 1; min-width: 0; }
        .item-name {
            font-weight: 700; font-size: 12.5px;
            color: var(--text);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            margin-bottom: 3px;
        }
        .item-price { font-size: 11px; color: var(--text2); }

        .item-controls { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }

        .qty-btn {
            width: 26px; height: 26px;
            border: 1px solid var(--border);
            background: var(--bg2);
            color: var(--text2);
            border-radius: 7px;
            cursor: pointer;
            font-size: 14px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s;
        }
        .qty-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }

        .qty-display {
            width: 28px; text-align: center;
            font-weight: 800; font-size: 13px;
        }

        .delete-btn {
            width: 26px; height: 26px;
            background: var(--danger-light);
            border: 1px solid #fecaca;
            color: var(--danger);
            border-radius: 7px;
            cursor: pointer; font-size: 12px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s;
        }
        .delete-btn:hover { background: var(--danger); color: white; }

        /* Cart Summary */
        .cart-summary {
            padding: 14px 18px;
            border-top: 1px solid var(--border);
            background: var(--bg3);
        }

        .summary-row {
            display: flex; justify-content: space-between;
            font-size: 12.5px; color: var(--text2);
            margin-bottom: 8px;
        }
        .summary-row.total {
            font-size: 16px; font-weight: 800;
            color: var(--text);
            padding-top: 10px;
            border-top: 1px solid var(--border);
            margin-bottom: 0; margin-top: 2px;
        }
        .summary-row.total span:last-child { color: var(--accent); }

        /* Payment Section */
        .payment-section {
            padding: 14px 18px 16px;
            border-top: 1px solid var(--border);
            background: var(--bg2);
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px; margin-bottom: 12px;
        }

        .payment-btn {
            padding: 10px;
            border: 1.5px solid var(--border);
            background: var(--bg3);
            border-radius: 9px;
            cursor: pointer;
            font-family: inherit;
            font-size: 12px; font-weight: 600;
            color: var(--text2);
            transition: all 0.15s;
            display: flex; flex-direction: column;
            align-items: center; gap: 3px;
        }
        .payment-btn .pay-icon { font-size: 18px; }
        .payment-btn:hover  { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }
        .payment-btn.active {
            border-color: var(--accent);
            background: var(--accent-light);
            color: var(--accent);
            box-shadow: 0 2px 8px rgba(37,99,235,0.15);
        }

        .checkout-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: white; border: none;
            border-radius: 11px;
            font-family: inherit;
            font-size: 14px; font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        }
        .checkout-btn:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37,99,235,0.4);
        }
        .checkout-btn:disabled {
            background: var(--border);
            color: var(--text3);
            box-shadow: none; cursor: not-allowed;
        }

        /* ===== MODALS ===== */
        .modal {
            display: none;
            position: fixed; inset: 0;
            background: rgba(15,23,42,0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center; justify-content: center;
        }
        .modal.active { display: flex; }

        .modal-content {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 28px;
            width: 420px; max-width: 95vw;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .modal-header {
            text-align: center; margin-bottom: 20px;
        }
        .modal-header h3 { font-size: 18px; font-weight: 800; color: var(--text); }
        .modal-header p  { font-size: 12px; color: var(--text2); margin-top: 3px; }

        .amount-display {
            background: var(--bg3);
            border: 1px solid var(--border);
            padding: 16px; border-radius: 12px;
            text-align: center; margin-bottom: 16px;
        }
        .amount-label { font-size: 11px; color: var(--text2); font-weight: 600; margin-bottom: 4px; }
        .amount-value { font-size: 26px; font-weight: 800; color: var(--text); }

        .change-display {
            background: var(--success-light);
            border: 1px solid #bbf7d0;
            padding: 14px; border-radius: 12px;
            text-align: center; margin-bottom: 16px;
        }
        .change-display .amount-value { color: var(--success); }

        .form-group { margin-bottom: 14px; }
        .form-group label {
            display: block; margin-bottom: 6px;
            font-size: 12px; font-weight: 700;
            color: var(--text2);
        }
        .form-group input {
            width: 100%;
            padding: 11px 14px;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 9px;
            font-family: inherit; font-size: 14px;
            color: var(--text); outline: none;
            transition: border-color 0.15s;
        }
        .form-group input:focus  { border-color: var(--accent); }
        .form-group input[readonly] { color: var(--text2); }

        .modal-actions { display: flex; gap: 10px; margin-top: 4px; }

        .btn-secondary {
            flex: 1; padding: 12px;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 9px;
            font-family: inherit; font-size: 13px; font-weight: 600;
            color: var(--text2); cursor: pointer;
            transition: all 0.15s;
        }
        .btn-secondary:hover { border-color: var(--danger); color: var(--danger); }

        .btn-primary {
            flex: 1; padding: 12px;
            background: var(--accent);
            color: white; border: none;
            border-radius: 9px;
            font-family: inherit; font-size: 13px; font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
            box-shadow: 0 3px 10px rgba(37,99,235,0.3);
        }
        .btn-primary:hover:not(:disabled) { background: #1d4ed8; }
        .btn-primary:disabled { background: var(--border); color: var(--text3); box-shadow: none; cursor: not-allowed; }

        .success-icon { font-size: 56px; text-align: center; margin-bottom: 12px; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
    </style>
</head>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
</script>

<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="header-logo">🛒</div>
        <div>
            <div class="header-title">Kasir POS</div>
            <div class="header-sub">POS QRIS System</div>
        </div>
        <div class="header-spacer"></div>
        <div class="header-time">🕐 <span id="currentTime">--:--</span></div>
        <div class="header-user">👤 {{ session('user_name') }}</div>
        <a href="{{ route('kasir.dashboard') }}" class="back-btn">← Kembali</a>
    </div>

    {{-- MAIN --}}
    <div class="main-container">

        {{-- PRODUCTS PANEL --}}
        <div class="products-panel">

            <div class="search-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" placeholder="Cari produk..." onkeyup="searchProduct()">
            </div>

            <div class="category-tabs">
                <button class="category-tab active" onclick="filterCategory('all', this)">Semua</button>
                @foreach($kategoris as $kategori)
                <button class="category-tab" onclick="filterCategory('{{ $kategori->id }}', this)">{{ $kategori->nama }}</button>
                @endforeach
            </div>

            <div class="products-grid" id="productsGrid">
                @foreach($produks as $produk)
                <div class="product-card"
                     data-category="{{ $produk->kategori_id }}"
                     data-id="{{ $produk->id }}"
                     data-nama="{{ $produk->nama }}"
                     data-harga="{{ $produk->harga }}"
                     data-stok="{{ $produk->stok }}"
                     onclick="addToCartFromCard(this)">
                    <div class="product-icon"></div>
                    <div class="product-name">{{ $produk->nama }}</div>
                    <div class="product-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                    <div class="product-stock {{ $produk->stok <= 5 ? 'low' : '' }}">
                        Stok: {{ $produk->stok }}{{ $produk->stok <= 5 ? ' ⚠️' : '' }}
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        {{-- CART PANEL --}}
        <div class="cart-panel">

            <div class="cart-header">
                <div class="cart-title">🛒 Keranjang Belanja</div>
                <div class="transaction-info">
                    <span id="transactionNo">Transaksi #{{ $nomorTransaksi }}</span>
                    <span id="transactionTime">--:--</span>
                </div>
            </div>

            <div class="cart-items" id="cartItems">
                <div class="empty-cart">
                    <div class="empty-cart-icon">🛒</div>
                    <p>Keranjang masih kosong</p>
                    <small>Pilih produk untuk memulai transaksi</small>
                </div>
            </div>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>Subtotal (<span id="itemCount">0</span> item)</span>
                    <span id="subtotal">Rp 0</span>
                </div>
                <div class="summary-row total">
                    <span>TOTAL</span>
                    <span id="totalAmount">Rp 0</span>
                </div>
            </div>

            <div class="payment-section">
                <div class="payment-methods">
                    <button class="payment-btn active" onclick="selectPayment('tunai', this)">
                        <span class="pay-icon">💵</span> Tunai
                    </button>
                    <button class="payment-btn" onclick="selectPayment('qris', this)">
                        <span class="pay-icon">📱</span> QRIS
                    </button>
                </div>
                <button class="checkout-btn" id="checkoutBtn" onclick="processPayment()" disabled>
                    Proses Pembayaran →
                </button>
            </div>

        </div>
    </div>

    {{-- MODAL: PAYMENT --}}
    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>💳 Proses Pembayaran</h3>
                <p>Pastikan jumlah sudah sesuai sebelum konfirmasi</p>
            </div>

            <div class="amount-display">
                <div class="amount-label">Total Pembayaran</div>
                <div class="amount-value" id="modalTotal">Rp 0</div>
            </div>

            <div class="form-group">
                <label>Metode Pembayaran</label>
                <input type="text" id="paymentMethod" readonly>
            </div>

            <div class="form-group" id="cashInputGroup">
                <label>Jumlah Uang Diterima</label>
                <input type="number" id="cashReceived" placeholder="Masukkan jumlah uang..." oninput="calculateChange()">
            </div>

            <div class="change-display" id="changeDisplay" style="display:none">
                <div class="amount-label">Kembalian</div>
                <div class="amount-value" id="changeAmount">Rp 0</div>
            </div>

            <div class="modal-actions">
                <button class="btn-secondary" onclick="closePaymentModal()">Batal</button>
                <button class="btn-primary" id="confirmPayBtn" onclick="confirmPayment()" disabled>Konfirmasi Bayar</button>
            </div>
        </div>
    </div>

    {{-- MODAL: SUCCESS --}}
    <div class="modal" id="successModal">
        <div class="modal-content">
            <div class="success-icon">✅</div>
            <div class="modal-header">
                <h3>Transaksi Berhasil!</h3>
                <p>Pembayaran telah diterima</p>
            </div>

            <div class="amount-display">
                <div class="amount-label">Total Pembayaran</div>
                <div class="amount-value" id="successTotal">Rp 0</div>
            </div>

            <div class="change-display" id="successChange">
                <div class="amount-label">Kembalian</div>
                <div class="amount-value" id="successChangeAmount">Rp 0</div>
            </div>

            <div class="modal-actions">
                <button class="btn-primary" onclick="newTransaction()" style="flex:1">
                    + Transaksi Baru
                </button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];
        let selectedPaymentMethod = 'tunai';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // ===== CLOCK =====
        function updateTime() {
            const now  = new Date();
            const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('currentTime').textContent    = time;
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
                    <div class="empty-cart">
                        <div class="empty-cart-icon">🛒</div>
                        <p>Keranjang masih kosong</p>
                        <small>Pilih produk untuk memulai transaksi</small>
                    </div>`;
                document.getElementById('checkoutBtn').disabled = true;
            } else {
                container.innerHTML = cart.map((item, i) => `
                    <div class="cart-item">
                        <div class="item-info">
                            <div class="item-name"> ${item.nama}</div>
                            <div class="item-price">Rp ${item.harga.toLocaleString('id-ID')} × ${item.qty}
                                = <strong>Rp ${(item.harga * item.qty).toLocaleString('id-ID')}</strong>
                            </div>
                        </div>
                        <div class="item-controls">
                            <button class="qty-btn" onclick="decreaseQty(${i})">−</button>
                            <span class="qty-display">${item.qty}</span>
                            <button class="qty-btn" onclick="increaseQty(${i})">+</button>
                            <button class="delete-btn" onclick="removeItem(${i})">🗑</button>
                        </div>
                    </div>`).join('');
                document.getElementById('checkoutBtn').disabled = false;
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
            document.getElementById('itemCount').textContent    = itemCount;
            document.getElementById('subtotal').textContent     = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('totalAmount').textContent  = 'Rp ' + total.toLocaleString('id-ID');
        }

        // ===== PAYMENT =====
        function selectPayment(method, btn) {
            selectedPaymentMethod = method;
            document.querySelectorAll('.payment-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        function processPayment() {
            const total = cart.reduce((s, item) => s + item.harga * item.qty, 0);
            document.getElementById('modalTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');

            const methodNames = { tunai: 'Tunai', qris: 'QRIS' };
            document.getElementById('paymentMethod').value = methodNames[selectedPaymentMethod];

            if (selectedPaymentMethod === 'tunai') {
                document.getElementById('cashInputGroup').style.display = 'block';
                document.getElementById('cashReceived').value = '';
                document.getElementById('changeDisplay').style.display = 'none';
                document.getElementById('confirmPayBtn').disabled = true;
            } else {
                document.getElementById('cashInputGroup').style.display = 'none';
                document.getElementById('changeDisplay').style.display = 'none';
                document.getElementById('confirmPayBtn').disabled = false;
            }

            document.getElementById('paymentModal').classList.add('active');
        }

        function calculateChange() {
            const total    = cart.reduce((s, item) => s + item.harga * item.qty, 0);
            const received = parseInt(document.getElementById('cashReceived').value) || 0;
            const change   = received - total;

            if (change >= 0) {
                document.getElementById('changeDisplay').style.display = 'block';
                document.getElementById('changeAmount').textContent = 'Rp ' + change.toLocaleString('id-ID');
                document.getElementById('confirmPayBtn').disabled = false;
            } else {
                document.getElementById('changeDisplay').style.display = 'none';
                document.getElementById('confirmPayBtn').disabled = true;
            }
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.remove('active');
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
                    snap.pay(result.data.snap_token, {
                        onSuccess: () => { alert('Pembayaran QRIS berhasil'); location.reload(); },
                        onPending: () => { alert('Silakan selesaikan pembayaran QRIS'); },
                        onError:   () => { alert('Pembayaran QRIS gagal'); },
                        onClose:   () => { alert('Pembayaran dibatalkan'); }
                    });
                } else {
                    document.getElementById('successTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
                    const change = received - total;
                    document.getElementById('successChange').style.display = 'block';
                    document.getElementById('successChangeAmount').textContent = 'Rp ' + change.toLocaleString('id-ID');
                    document.getElementById('successModal').classList.add('active');
                }

            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        }

        function newTransaction() {
            cart = [];
            updateCartDisplay();
            document.getElementById('successModal').classList.remove('active');
        }

        // ===== SEARCH & FILTER =====
        function searchProduct() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.querySelector('.product-name').textContent.toLowerCase();
                card.style.display = name.includes(q) ? 'block' : 'none';
            });
        }

        function filterCategory(category, btn) {
            document.querySelectorAll('.category-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.product-card').forEach(card => {
                card.style.display = (category === 'all' || card.dataset.category === category) ? 'block' : 'none';
            });
        }
    </script>
</body>
</html>