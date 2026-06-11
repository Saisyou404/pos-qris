<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS QRIS — @yield('title', 'Dashboard')</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg:           #f0f4f8;
            --bg2:          #ffffff;
            --bg3:          #f8fafc;
            --border:       #e2e8f0;
            --border2:      #cbd5e1;
            --accent:       #2563eb;
            --accent-light: #eff6ff;
            --accent2:      #7c3aed;
            --success:      #16a34a;
            --success-light:#f0fdf4;
            --warning:      #d97706;
            --warning-light:#fffbeb;
            --danger:       #dc2626;
            --danger-light: #fef2f2;
            --text:         #0f172a;
            --text2:        #64748b;
            --text3:        #94a3b8;
            --sidebar-w:    230px;
            --header-h:     62px;
            --shadow:       0 1px 3px rgba(0,0,0,0.07), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:    0 4px 14px rgba(0,0,0,0.08);
            --radius:       12px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg2);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            box-shadow: var(--shadow);
        }

        .sidebar-logo {
            padding: 18px 16px 14px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }

        .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px;
            box-shadow: 0 3px 8px rgba(37,99,235,0.28);
            flex-shrink: 0;
        }

        .logo-text { font-weight: 800; font-size: 15px; letter-spacing: -0.3px; color: var(--text); }
        .logo-sub  { font-size: 10px; color: var(--text3); font-weight: 500; }

        .sidebar-section { padding: 12px 10px 4px; }

        .sidebar-label {
            font-size: 9.5px; font-weight: 700; letter-spacing: 1.2px;
            color: var(--text3); text-transform: uppercase;
            padding: 0 8px; margin-bottom: 4px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 10px;
            border-radius: 9px;
            cursor: pointer;
            font-size: 13px; font-weight: 500;
            color: var(--text2);
            transition: all 0.15s;
            text-decoration: none;
            margin-bottom: 1px;
        }

        .nav-item:hover  { background: var(--bg3); color: var(--text); }
        .nav-item.active { background: var(--accent-light); color: var(--accent); font-weight: 600; }

        .nav-icon {
            width: 30px; height: 30px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; flex-shrink: 0;
        }

        .nav-item.active .nav-icon { background: rgba(37,99,235,0.1); }

        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: #fff; font-size: 9px; font-weight: 700;
            padding: 2px 6px; border-radius: 99px;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 12px;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 10px;
            border-radius: 10px;
            background: var(--bg3);
            border: 1px solid var(--border);
        }

        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 800; color: white; flex-shrink: 0;
        }

        .user-name { font-size: 12px; font-weight: 700; color: var(--text); }
        .user-role { font-size: 10px; color: var(--text3); }

        /* ===== MAIN ===== */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== HEADER ===== */
        .header {
            height: var(--header-h);
            background: var(--bg2);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 24px; gap: 12px;
            position: sticky; top: 0; z-index: 50;
            box-shadow: var(--shadow);
        }

        .header-title { font-size: 15px; font-weight: 800; color: var(--text); }
        .header-sub   { font-size: 11px; color: var(--text3); margin-top: 1px; }
        .header-spacer { flex: 1; }

        .header-btn {
            width: 36px; height: 36px;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 14px; color: var(--text2);
            transition: all 0.15s;
            text-decoration: none;
        }

        .header-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }

        /* ===== PAGE CONTENT ===== */
        .page-content { padding: 24px; flex: 1; }

        /* ===== FLASH MESSAGES ===== */
        .flash {
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px; font-weight: 500;
            margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }

        .flash-success { background: var(--success-light); border: 1px solid #bbf7d0; color: var(--success); }
        .flash-error   { background: var(--danger-light);  border: 1px solid #fecaca; color: var(--danger); }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 4px; }

        @yield('styles')
    </style>

    @yield('head')
</head>
<body>

@if(session('user_id'))
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">🛒</div>
        <div>
            <div class="logo-text">POS QRIS</div>
            <div class="logo-sub">{{ session('user_role') === 'admin' ? 'Administrator' : 'Kasir' }}</div>
        </div>
    </div>

    @if(session('user_role') === 'kasir')
    <div class="sidebar-section">
        <div class="sidebar-label">Menu</div>
        <a href="/kasir/dashboard" class="nav-item {{ request()->is('kasir/dashboard') ? 'active' : '' }}">
            <div class="nav-icon">🏠</div> Dashboard
        </a>
        <a href="/kasir/transaksi" class="nav-item {{ request()->is('kasir/transaksi*') ? 'active' : '' }}">
            <div class="nav-icon">🏪</div> Kasir / POS
        </a>
    </div>
    @endif

    @if(session('user_role') === 'admin')
    <div class="sidebar-section">
        <div class="sidebar-label">Menu Utama</div>
        <a href="/admin/dashboard" class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <div class="nav-icon">🏠</div> Dashboard
        </a>
        <a href="/admin/produk" class="nav-item {{ request()->is('admin/produk*') ? 'active' : '' }}">
            <div class="nav-icon">📦</div> Barang & Stok
        </a>
        <a href="/admin/kategori" class="nav-item {{ request()->is('admin/kategori*') ? 'active' : '' }}">
            <div class="nav-icon">🏷️</div> Kategori
        </a>
        <a href="/admin/laporan" class="nav-item {{ request()->is('admin/laporan*') ? 'active' : '' }}">
            <div class="nav-icon">📊</div> Laporan
        </a>
    </div>
    <div class="sidebar-section">
        <div class="sidebar-label">Pengaturan</div>
        <a href="/admin/pengguna" class="nav-item {{ request()->is('admin/pengguna*') ? 'active' : '' }}">
            <div class="nav-icon">👥</div> Pengguna
        </a>
    </div>
    @endif

    <div class="sidebar-bottom">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(session('user_name', 'U'), 0, 2)) }}</div>
            <div>
                <div class="user-name">{{ session('user_name', 'Pengguna') }}</div>
                <div class="user-role">{{ ucfirst(session('user_role', '')) }}</div>
            </div>
        </div>
    </div>
</aside>
@endif

<div class="main" style="{{ !session('user_id') ? 'margin-left:0' : '' }}">

    @if(session('user_id'))
    <header class="header">
        <div>
            <div class="header-title">@yield('page_title', 'Dashboard')</div>
            <div class="header-sub">@yield('page_sub', 'POS QRIS System')</div>
        </div>
        <div class="header-spacer"></div>
        <a href="/logout" class="header-btn" title="Logout">🚪</a>
    </header>
    @endif

    <div class="page-content">
        @if(session('success'))
            <div class="flash flash-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash flash-error">❌ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

@yield('scripts')
</body>
</html>