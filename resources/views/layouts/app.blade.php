<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS QRIS — @yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>

    @yield('styles')
    @yield('head')
</head>
<body class="font-sans bg-slate-100 text-slate-900 min-h-screen flex m-0 p-0">

@if(session('user_id'))
<aside class="w-[230px] bg-white border-r border-slate-200 flex flex-col fixed top-0 left-0 bottom-0 z-[100] shadow-sm">
    <div class="px-4 pt-[18px] pb-3.5 border-b border-slate-200 flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-[10px] bg-gradient-to-br from-blue-600 to-violet-600 flex items-center justify-center text-[17px] shadow-md shadow-blue-600/30 shrink-0">🛒</div>
        <div>
            <div class="font-extrabold text-[15px] tracking-tight text-slate-900">POS QRIS</div>
            <div class="text-[10px] text-slate-400 font-medium">{{ session('user_role') === 'admin' ? 'Administrator' : 'Kasir' }}</div>
        </div>
    </div>

    @if(session('user_role') === 'kasir')
    <div class="px-2.5 pt-3 pb-1">
        <div class="text-[9.5px] font-bold tracking-[1.2px] text-slate-400 uppercase px-2 mb-1">Menu</div>
        <a href="/kasir/dashboard" class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-[9px] text-[13px] font-medium no-underline mb-0.5 transition-colors {{ request()->is('kasir/dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-[30px] h-[30px] rounded-lg flex items-center justify-center text-sm shrink-0 {{ request()->is('kasir/dashboard') ? 'bg-blue-600/10' : '' }}">🏠</div> Dashboard
        </a>
        <a href="/kasir/transaksi" class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-[9px] text-[13px] font-medium no-underline mb-0.5 transition-colors {{ request()->is('kasir/transaksi*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-[30px] h-[30px] rounded-lg flex items-center justify-center text-sm shrink-0 {{ request()->is('kasir/transaksi*') ? 'bg-blue-600/10' : '' }}">🏪</div> Kasir / POS
        </a>
    </div>
    @endif

    @if(session('user_role') === 'admin')
    <div class="px-2.5 pt-3 pb-1">
        <div class="text-[9.5px] font-bold tracking-[1.2px] text-slate-400 uppercase px-2 mb-1">Menu utama</div>
        <a href="/admin/dashboard" class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-[9px] text-[13px] font-medium no-underline mb-0.5 transition-colors {{ request()->is('admin/dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-[30px] h-[30px] rounded-lg flex items-center justify-center text-sm shrink-0 {{ request()->is('admin/dashboard') ? 'bg-blue-600/10' : '' }}">🏠</div> Dashboard
        </a>
        <a href="/admin/produk" class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-[9px] text-[13px] font-medium no-underline mb-0.5 transition-colors {{ request()->is('admin/produk*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-[30px] h-[30px] rounded-lg flex items-center justify-center text-sm shrink-0 {{ request()->is('admin/produk*') ? 'bg-blue-600/10' : '' }}">📦</div> Barang & Stok
        </a>
        <a href="/admin/kategori" class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-[9px] text-[13px] font-medium no-underline mb-0.5 transition-colors {{ request()->is('admin/kategori*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-[30px] h-[30px] rounded-lg flex items-center justify-center text-sm shrink-0 {{ request()->is('admin/kategori*') ? 'bg-blue-600/10' : '' }}">🏷️</div> Kategori
        </a>
        <a href="/admin/laporan" class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-[9px] text-[13px] font-medium no-underline mb-0.5 transition-colors {{ request()->is('admin/laporan*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-[30px] h-[30px] rounded-lg flex items-center justify-center text-sm shrink-0 {{ request()->is('admin/laporan*') ? 'bg-blue-600/10' : '' }}">📊</div> Laporan
        </a>
    </div>
    <div class="px-2.5 pt-3 pb-1">
        <div class="text-[9.5px] font-bold tracking-[1.2px] text-slate-400 uppercase px-2 mb-1">Pengaturan</div>
        <a href="/admin/pengguna" class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-[9px] text-[13px] font-medium no-underline mb-0.5 transition-colors {{ request()->is('admin/pengguna*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-[30px] h-[30px] rounded-lg flex items-center justify-center text-sm shrink-0 {{ request()->is('admin/pengguna*') ? 'bg-blue-600/10' : '' }}">👥</div> Pengguna
        </a>
    </div>
    @endif

    <div class="mt-auto p-3 border-t border-slate-200">
        <div class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-[10px] bg-slate-50 border border-slate-200">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-red-500 flex items-center justify-center text-xs font-extrabold text-white shrink-0">{{ strtoupper(substr(session('user_name', 'U'), 0, 2)) }}</div>
            <div>
                <div class="text-xs font-bold text-slate-900">{{ session('user_name', 'Pengguna') }}</div>
                <div class="text-[10px] text-slate-400">{{ ucfirst(session('user_role', '')) }}</div>
            </div>
        </div>
    </div>
</aside>
@endif

<div class="flex-1 flex flex-col min-h-screen {{ session('user_id') ? 'ml-[230px]' : '' }}">

    @if(session('user_id'))
    <header class="h-[62px] bg-white border-b border-slate-200 flex items-center px-6 gap-3 sticky top-0 z-50 shadow-sm">
        <div>
            <div class="text-[15px] font-extrabold text-slate-900">@yield('page_title', 'Dashboard')</div>
            <div class="text-[11px] text-slate-400 mt-0.5">@yield('page_sub', 'POS QRIS System')</div>
        </div>
        <div class="flex-1"></div>
        <a href="/logout" class="w-9 h-9 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-center text-sm text-slate-500 hover:border-blue-600 hover:text-blue-600 hover:bg-blue-50 transition-colors no-underline" title="Logout">🚪</a>
    </header>
    @endif

    <div class="p-6 flex-1">
        @if(session('success'))
            <div class="rounded-[10px] px-4 py-3 text-[13px] font-medium mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-600">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rounded-[10px] px-4 py-3 text-[13px] font-medium mb-4 flex items-center gap-2 bg-red-50 border border-red-200 text-red-600">❌ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

@yield('scripts')
</body>
</html>