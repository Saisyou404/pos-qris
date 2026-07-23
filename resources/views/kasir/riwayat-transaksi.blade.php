@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi')
@section('page_sub', 'Daftar semua transaksi Anda')

@section('content')

{{-- Filter --}}
<form method="GET" action="{{ route('kasir.riwayat') }}">
<div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 mb-4.5 flex items-end gap-3 flex-wrap shadow-sm">
    <div class="flex flex-col gap-1.5">
        <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wide">Tanggal mulai</span>
        <input type="date" name="start_date" value="{{ $startDate }}"
               class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-[12.5px] text-slate-900 outline-none focus:border-blue-600 transition-colors">
    </div>
    <div class="flex flex-col gap-1.5">
        <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wide">Tanggal akhir</span>
        <input type="date" name="end_date" value="{{ $endDate }}"
               class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-[12.5px] text-slate-900 outline-none focus:border-blue-600 transition-colors">
    </div>
    <div class="flex flex-col gap-1.5">
        <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wide">Status</span>
        <select name="status" class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-[12.5px] text-slate-900 outline-none focus:border-blue-600 transition-colors">
            <option value="all"        {{ $status === 'all'        ? 'selected' : '' }}>Semua status</option>
            <option value="dibayar"    {{ $status === 'dibayar'    ? 'selected' : '' }}>Dibayar</option>
            <option value="pending"    {{ $status === 'pending'    ? 'selected' : '' }}>Pending</option>
            <option value="dibatalkan" {{ $status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
    </div>
    <div class="flex flex-col gap-1.5">
        <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wide">Metode</span>
        <select name="metode" class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-[12.5px] text-slate-900 outline-none focus:border-blue-600 transition-colors">
            <option value="all"   {{ $metode === 'all'   ? 'selected' : '' }}>Semua metode</option>
            <option value="tunai" {{ $metode === 'tunai' ? 'selected' : '' }}>💵 Tunai</option>
            <option value="qris"  {{ $metode === 'qris'  ? 'selected' : '' }}>📱 QRIS</option>
        </select>
    </div>
    <button type="submit" class="self-end inline-flex items-center gap-1.5 py-2 px-4 rounded-lg text-xs font-semibold cursor-pointer bg-blue-600 text-white shadow-[0_2px_8px_rgba(37,99,235,0.25)] hover:bg-blue-700 transition-colors">🔍 Filter</button>
    <a href="{{ route('kasir.riwayat') }}" class="self-end inline-flex items-center gap-1.5 py-2 px-4 rounded-lg text-xs font-semibold no-underline bg-slate-50 text-slate-500 border border-slate-200 hover:border-blue-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">↺ Reset</a>
</div>
</form>

{{-- Summary --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4.5">
    <div class="bg-white border border-slate-200 rounded-xl px-4.5 py-3.5 shadow-sm flex items-center gap-3">
        <div class="w-9.5 h-9.5 rounded-[10px] flex items-center justify-center text-[17px] shrink-0 bg-blue-50">🛒</div>
        <div>
            <div class="text-[10.5px] text-slate-500 font-semibold mb-1">Total transaksi</div>
            <div class="text-lg font-extrabold leading-none text-blue-600">{{ $transaksi->total() }}</div>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl px-4.5 py-3.5 shadow-sm flex items-center gap-3">
        <div class="w-9.5 h-9.5 rounded-[10px] flex items-center justify-center text-[17px] shrink-0 bg-green-50">💰</div>
        <div>
            <div class="text-[10.5px] text-slate-500 font-semibold mb-1">Total pendapatan</div>
            <div class="text-lg font-extrabold leading-none text-green-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl px-4.5 py-3.5 shadow-sm flex items-center gap-3">
        <div class="w-9.5 h-9.5 rounded-[10px] flex items-center justify-center text-[17px] shrink-0 bg-amber-50">📊</div>
        <div>
            <div class="text-[10.5px] text-slate-500 font-semibold mb-1">Rata-rata / transaksi</div>
            <div class="text-lg font-extrabold leading-none text-slate-900">
                Rp {{ $transaksi->total() > 0 ? number_format($totalPendapatan / $transaksi->total(), 0, ',', '.') : 0 }}
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    <table class="w-full border-collapse">
        <thead>
            <tr>
                <th class="py-2.5 px-4 text-left text-[10px] font-bold tracking-wide uppercase text-slate-400 bg-slate-50 border-b border-slate-200 whitespace-nowrap">No. invoice</th>
                <th class="py-2.5 px-4 text-left text-[10px] font-bold tracking-wide uppercase text-slate-400 bg-slate-50 border-b border-slate-200 whitespace-nowrap">Tanggal & waktu</th>
                <th class="py-2.5 px-4 text-left text-[10px] font-bold tracking-wide uppercase text-slate-400 bg-slate-50 border-b border-slate-200 whitespace-nowrap">Item</th>
                <th class="py-2.5 px-4 text-left text-[10px] font-bold tracking-wide uppercase text-slate-400 bg-slate-50 border-b border-slate-200 whitespace-nowrap">Total</th>
                <th class="py-2.5 px-4 text-left text-[10px] font-bold tracking-wide uppercase text-slate-400 bg-slate-50 border-b border-slate-200 whitespace-nowrap">Metode</th>
                <th class="py-2.5 px-4 text-left text-[10px] font-bold tracking-wide uppercase text-slate-400 bg-slate-50 border-b border-slate-200 whitespace-nowrap">Status</th>
                <th class="py-2.5 px-4 text-center text-[10px] font-bold tracking-wide uppercase text-slate-400 bg-slate-50 border-b border-slate-200 whitespace-nowrap">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $t)
            <tr class="hover:bg-slate-50">
                <td class="py-3.5 px-4 text-[12.5px] border-b border-slate-200 align-middle">
                    <span class="text-[11.5px] font-semibold text-slate-500" style="font-family:'DM Mono',monospace">{{ $t->nomor_invoice }}</span>
                </td>
                <td class="py-3.5 px-4 text-[12.5px] border-b border-slate-200 align-middle">
                    <div class="font-semibold text-[12.5px] text-slate-900">
                        {{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d M Y') }}
                    </div>
                    <div class="text-[11px] text-slate-400">
                        {{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('H:i') }} WIB
                    </div>
                </td>
                <td class="py-3.5 px-4 text-[12.5px] border-b border-slate-200 align-middle">
                    <span class="text-xs font-semibold text-slate-900">{{ $t->detailTransaksi->count() }} item</span>
                </td>
                <td class="py-3.5 px-4 text-[12.5px] border-b border-slate-200 align-middle">
                    <span class="font-extrabold text-blue-600">Rp {{ number_format($t->total_pembayaran, 0, ',', '.') }}</span>
                </td>
                <td class="py-3.5 px-4 text-[12.5px] border-b border-slate-200 align-middle">
                    <span class="inline-flex items-center gap-1 py-0.5 px-2.5 rounded-full text-[10.5px] font-semibold bg-slate-50 border border-slate-200 text-slate-500">
                        {{ $t->metode_pembayaran === 'tunai' ? '💵' : '📱' }}
                        {{ ucfirst($t->metode_pembayaran) }}
                    </span>
                </td>
                <td class="py-3.5 px-4 text-[12.5px] border-b border-slate-200 align-middle">
                    @if($t->status === 'dibayar')
                        <span class="inline-flex items-center gap-1 py-0.5 px-2.5 rounded-full text-[10.5px] font-bold bg-green-50 text-green-600">● Dibayar</span>
                    @elseif($t->status === 'pending')
                        <span class="inline-flex items-center gap-1 py-0.5 px-2.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-600">● Pending</span>
                    @else
                        <span class="inline-flex items-center gap-1 py-0.5 px-2.5 rounded-full text-[10.5px] font-bold bg-red-50 text-red-600">● {{ ucfirst($t->status) }}</span>
                    @endif
                </td>
                <td class="py-3.5 px-4 text-[12.5px] border-b border-slate-200 align-middle text-center">
                    <button onclick="showDetail({{ $t->id }})"
                            class="inline-flex items-center gap-1 py-1.5 px-3 rounded-lg text-[11.5px] font-semibold cursor-pointer bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 transition-colors">
                        🔍 Detail
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="text-center py-13 px-5 text-slate-400">
                        <div class="text-[40px] mb-2.5 opacity-35">🛒</div>
                        <div class="text-sm font-bold mb-1 text-slate-500">Belum ada transaksi</div>
                        <div class="text-xs">Tidak ada transaksi pada periode yang dipilih</div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="py-3 px-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between text-[11.5px] text-slate-500">
        <span>Menampilkan <strong>{{ $transaksi->count() }}</strong> dari <strong>{{ $transaksi->total() }}</strong> transaksi</span>
        <div>{{ $transaksi->withQueryString()->links() }}</div>
    </div>
</div>

{{-- Modal Detail --}}
<div class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-1000 items-center justify-center" id="detailModal">
    <div class="bg-white border border-slate-200 rounded-[18px] w-120 max-w-[96vw] max-h-[90vh] overflow-y-auto shadow-[0_20px_60px_rgba(0,0,0,0.15)]">
        <div class="py-5 px-6 pb-4 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-1">
            <div class="text-[15px] font-extrabold text-slate-900">🧾 Detail transaksi</div>
            <button onclick="closeModal()"
                    class="w-7.5 h-7.5 border border-slate-200 bg-slate-50 rounded-lg cursor-pointer text-sm text-slate-500 flex items-center justify-center hover:border-red-600 hover:text-red-600 hover:bg-red-50 transition-colors">✕</button>
        </div>
        <div class="p-6" id="modalContent">
            <div class="text-center py-10 text-slate-400">Memuat data...</div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const transaksiData = {
        @foreach($transaksi as $t)
        {{ $t->id }}: {
            invoice : "{{ $t->nomor_invoice }}",
            tanggal : "{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->isoFormat('D MMMM Y, HH:mm') }}",
            metode  : "{{ ucfirst($t->metode_pembayaran) }}",
            icon    : "{{ $t->metode_pembayaran === 'tunai' ? '💵' : '📱' }}",
            status  : "{{ $t->status }}",
            total   : {{ $t->total_pembayaran }},
            items   : [
                @foreach($t->detailTransaksi as $d)
                {
                    nama     : "{{ addslashes($d->produk->nama ?? 'Produk dihapus') }}",
                    harga    : {{ $d->harga_satuan }},
                    qty      : {{ $d->jumlah }},
                    subtotal : {{ $d->subtotal }}
                },
                @endforeach
            ]
        },
        @endforeach
    };

    function showDetail(id) {
        const t = transaksiData[id];
        if (!t) return;

        const statusMap = {
            'dibayar'    : '<span class="bg-green-50 text-green-600 py-0.5 px-2.5 rounded-full text-[11px] font-bold">● Dibayar</span>',
            'pending'    : '<span class="bg-amber-50 text-amber-600 py-0.5 px-2.5 rounded-full text-[11px] font-bold">● Pending</span>',
            'dibatalkan' : '<span class="bg-red-50 text-red-600 py-0.5 px-2.5 rounded-full text-[11px] font-bold">● Dibatalkan</span>',
        };

        const items = t.items.map(item => `
            <div class="flex items-center gap-3 py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-[10px] mb-2 last:mb-0">
                <div class="w-8.5 h-8.5 bg-blue-50 border border-blue-200 rounded-[9px] flex items-center justify-center text-[15px] shrink-0">📦</div>
                <div class="flex-1 min-w-0">
                    <div class="text-[12.5px] font-bold text-slate-900 mb-0.5">${item.nama}</div>
                    <div class="text-[11px] text-slate-500">Rp ${item.harga.toLocaleString('id-ID')} × ${item.qty}</div>
                </div>
                <div class="ml-auto text-[13px] font-extrabold text-slate-900 whitespace-nowrap">Rp ${item.subtotal.toLocaleString('id-ID')}</div>
            </div>
        `).join('');

        document.getElementById('modalContent').innerHTML = `
            <div class="grid grid-cols-2 gap-2.5 mb-4.5">
                <div class="bg-slate-50 border border-slate-200 rounded-[10px] py-2.5 px-3.5">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">No. invoice</div>
                    <div class="text-[13px] font-bold text-slate-900" style="font-family:'DM Mono',monospace">${t.invoice}</div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-[10px] py-2.5 px-3.5">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Tanggal & waktu</div>
                    <div class="text-[13px] font-bold text-slate-900">${t.tanggal}</div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-[10px] py-2.5 px-3.5">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Metode pembayaran</div>
                    <div class="text-[13px] font-bold text-slate-900">${t.icon} ${t.metode}</div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-[10px] py-2.5 px-3.5">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Status</div>
                    <div class="text-[13px] font-bold text-slate-900">${statusMap[t.status] || t.status}</div>
                </div>
            </div>
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wide mb-2.5">Detail produk (${t.items.length} item)</div>
            ${items}
            <div class="bg-blue-50 border border-blue-200 rounded-[10px] py-3.5 px-4.5 flex items-center justify-between mt-4">
                <span class="text-xs font-bold text-blue-600">Total pembayaran</span>
                <span class="text-xl font-extrabold text-blue-600">Rp ${t.total.toLocaleString('id-ID')}</span>
            </div>
            <div class="flex gap-2 mt-3.5">
                <button onclick="window.print()" class="flex-1 py-2.5 bg-slate-50 border border-slate-200 rounded-[9px] text-xs font-semibold cursor-pointer text-slate-500 hover:border-blue-600 hover:text-blue-600 transition-colors">
                    🖨️ Cetak struk
                </button>
                <button onclick="closeModal()" class="flex-1 py-2.5 bg-blue-600 text-white border-0 rounded-[9px] text-xs font-bold cursor-pointer hover:bg-blue-700 transition-colors">
                    Tutup
                </button>
            </div>
        `;

        document.getElementById('detailModal').classList.remove('hidden');
        document.getElementById('detailModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('detailModal').classList.add('hidden');
        document.getElementById('detailModal').classList.remove('flex');
    }

    document.getElementById('detailModal').addEventListener('click', e => {
        if (e.target === document.getElementById('detailModal')) closeModal();
    });
</script>
@endsection