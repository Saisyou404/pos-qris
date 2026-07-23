@extends('layouts.app')

@section('title', 'Input Laporan Harian')
@section('page_title', 'Input Laporan Harian')
@section('page_sub', 'Submit laporan shift Anda hari ini')

@section('content')

{{-- Sudah Submit Banner --}}
@if($sudahSubmit)
<div class="bg-green-50 border border-green-200 rounded-2xl px-6 py-5 flex items-center gap-4 mb-4.5">
    <div class="text-4xl">✅</div>
    <div>
        <div class="text-[15px] font-extrabold text-green-600 mb-1">Laporan sudah disubmit</div>
        <div class="text-xs text-slate-500">
            Anda telah mengirim laporan hari ini pada
            {{ \Carbon\Carbon::parse($laporanHariIni->created_at)->format('H:i') }} WIB.
            Terima kasih!
        </div>
    </div>
    <a href="{{ route('kasir.laporan.riwayat') }}" class="ml-auto inline-flex items-center gap-1.5 py-2.5 px-4.5 rounded-[9px] text-[13px] font-bold no-underline bg-slate-50 text-slate-500 border border-slate-200 hover:border-red-600 hover:text-red-600 transition-colors whitespace-nowrap">
        📋 Lihat riwayat
    </a>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-4.5 items-start">

    {{-- Kiri: Form --}}
    <div>
        @if(!$sudahSubmit)
        <form action="{{ route('kasir.laporan.store') }}" method="POST" id="laporanForm">
        @csrf
        @endif

        {{-- Informasi Shift --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm mb-4.5">
            <div class="py-3.5 px-5 border-b border-slate-200 bg-slate-50 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-[9px] flex items-center justify-center text-[15px] bg-blue-50">⏰</div>
                <div>
                    <div class="text-[13px] font-bold text-slate-900">Informasi shift</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">Data otomatis dari sistem</div>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 gap-3.5 mb-3.5">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Tanggal</label>
                        <div class="relative">
                            <input type="text" value="{{ now()->isoFormat('D MMMM Y') }}" readonly
                                   class="w-full py-2.5 px-3.5 pr-16 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] text-slate-500 outline-none">
                            <span class="absolute right-2.5 top-1/2 -translate-y-1/2 bg-green-50 text-green-600 border border-green-200 text-[9px] font-bold tracking-wide py-0.5 px-1.5 rounded-full uppercase pointer-events-none">Auto</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Kasir</label>
                        <div class="relative">
                            <input type="text" value="{{ session('user_name') }}" readonly
                                   class="w-full py-2.5 px-3.5 pr-16 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] text-slate-500 outline-none">
                            <span class="absolute right-2.5 top-1/2 -translate-y-1/2 bg-green-50 text-green-600 border border-green-200 text-[9px] font-bold tracking-wide py-0.5 px-1.5 rounded-full uppercase pointer-events-none">Auto</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3.5">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Jam mulai shift</label>
                        <input type="time" name="jam_mulai"
                               value="{{ $sudahSubmit ? $laporanHariIni->jam_mulai : '08:00' }}"
                               {{ $sudahSubmit ? 'readonly' : '' }} required
                               class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 focus:bg-white transition-colors">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Jam selesai shift</label>
                        <input type="time" name="jam_selesai"
                               value="{{ $sudahSubmit ? $laporanHariIni->jam_selesai : now()->format('H:i') }}"
                               {{ $sudahSubmit ? 'readonly' : '' }} required
                               class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 focus:bg-white transition-colors">
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan Penjualan --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm mb-4.5">
            <div class="py-3.5 px-5 border-b border-slate-200 bg-slate-50 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-[9px] flex items-center justify-center text-[15px] bg-green-50">💰</div>
                <div>
                    <div class="text-[13px] font-bold text-slate-900">Ringkasan penjualan</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">Data diambil otomatis dari sistem</div>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 gap-3.5 mb-3.5">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Total transaksi</label>
                        <div class="relative">
                            <input type="number" name="total_transaksi"
                                   value="{{ $sudahSubmit ? $laporanHariIni->total_transaksi : $statsHariIni['total_transaksi'] }}"
                                   readonly
                                   class="w-full py-2.5 px-3.5 pr-16 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] text-slate-500 outline-none">
                            <span class="absolute right-2.5 top-1/2 -translate-y-1/2 bg-green-50 text-green-600 border border-green-200 text-[9px] font-bold tracking-wide py-0.5 px-1.5 rounded-full uppercase pointer-events-none">Auto</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Total pendapatan (Rp)</label>
                        <div class="relative">
                            <input type="number" name="total_pendapatan"
                                   value="{{ $sudahSubmit ? $laporanHariIni->total_pendapatan : $statsHariIni['total_pendapatan'] }}"
                                   readonly
                                   class="w-full py-2.5 px-3.5 pr-16 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] text-slate-500 outline-none">
                            <span class="absolute right-2.5 top-1/2 -translate-y-1/2 bg-green-50 text-green-600 border border-green-200 text-[9px] font-bold tracking-wide py-0.5 px-1.5 rounded-full uppercase pointer-events-none">Auto</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3.5">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Pendapatan tunai (Rp)</label>
                        <div class="relative">
                            <input type="number" name="pendapatan_tunai"
                                   value="{{ $sudahSubmit ? $laporanHariIni->pendapatan_tunai : $statsHariIni['tunai'] }}"
                                   readonly
                                   class="w-full py-2.5 px-3.5 pr-16 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] text-slate-500 outline-none">
                            <span class="absolute right-2.5 top-1/2 -translate-y-1/2 bg-green-50 text-green-600 border border-green-200 text-[9px] font-bold tracking-wide py-0.5 px-1.5 rounded-full uppercase pointer-events-none">Auto</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Pendapatan QRIS (Rp)</label>
                        <div class="relative">
                            <input type="number" name="pendapatan_qris"
                                   value="{{ $sudahSubmit ? $laporanHariIni->pendapatan_qris : $statsHariIni['qris'] }}"
                                   readonly
                                   class="w-full py-2.5 px-3.5 pr-16 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] text-slate-500 outline-none">
                            <span class="absolute right-2.5 top-1/2 -translate-y-1/2 bg-green-50 text-green-600 border border-green-200 text-[9px] font-bold tracking-wide py-0.5 px-1.5 rounded-full uppercase pointer-events-none">Auto</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kondisi & Catatan --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm mb-4.5">
            <div class="py-3.5 px-5 border-b border-slate-200 bg-slate-50 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-[9px] flex items-center justify-center text-[15px] bg-violet-50">📝</div>
                <div>
                    <div class="text-[13px] font-bold text-slate-900">Kondisi & catatan</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">Isi kondisi toko dan catatan hari ini</div>
                </div>
            </div>
            <div class="p-5">

                <div class="flex flex-col gap-1.5 mb-3.5">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Kondisi toko hari ini</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" id="kondisiBaik"
                                onclick="setKondisi('baik')" {{ $sudahSubmit ? 'disabled' : '' }}
                                class="py-2.5 px-1.5 border-[1.5px] rounded-[9px] text-xs font-semibold cursor-pointer text-center flex flex-col items-center gap-1 transition-colors">
                            <span class="text-lg">😊</span> Baik
                        </button>
                        <button type="button" id="kondisiSedang"
                                onclick="setKondisi('sedang')" {{ $sudahSubmit ? 'disabled' : '' }}
                                class="py-2.5 px-1.5 border-[1.5px] rounded-[9px] text-xs font-semibold cursor-pointer text-center flex flex-col items-center gap-1 transition-colors">
                            <span class="text-lg">😐</span> Sedang
                        </button>
                        <button type="button" id="kondisiBuruk"
                                onclick="setKondisi('buruk')" {{ $sudahSubmit ? 'disabled' : '' }}
                                class="py-2.5 px-1.5 border-[1.5px] rounded-[9px] text-xs font-semibold cursor-pointer text-center flex flex-col items-center gap-1 transition-colors">
                            <span class="text-lg">😟</span> Buruk
                        </button>
                    </div>
                    <input type="hidden" name="kondisi_toko" id="kondisiInput"
                           value="{{ $sudahSubmit ? $laporanHariIni->kondisi_toko : 'baik' }}">
                </div>

                <div class="flex flex-col gap-1.5 mb-3.5">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Catatan kejadian / kendala</label>
                    <textarea name="catatan_kejadian"
                              placeholder="Contoh: Mesin kasir sempat hang, listrik mati 10 menit, dsb. (opsional)"
                              {{ $sudahSubmit ? 'readonly' : '' }}
                              class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 focus:bg-white transition-colors resize-y min-h-22.5 leading-normal">{{ $sudahSubmit ? $laporanHariIni->catatan_kejadian : old('catatan_kejadian') }}</textarea>
                    <div class="text-[10.5px] text-slate-400">Kosongkan jika tidak ada kendala hari ini</div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Saran / masukan untuk manajemen</label>
                    <textarea name="saran"
                              placeholder="Masukan, saran, atau hal yang ingin disampaikan ke admin (opsional)"
                              {{ $sudahSubmit ? 'readonly' : '' }}
                              class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-[9px] text-[13px] text-slate-900 outline-none focus:border-blue-600 focus:bg-white transition-colors resize-y min-h-22.5 leading-normal">{{ $sudahSubmit ? $laporanHariIni->saran : old('saran') }}</textarea>
                </div>

            </div>
        </div>

        {{-- Submit --}}
        @if(!$sudahSubmit)
        <div class="flex gap-2.5">
            <button type="submit" class="inline-flex items-center gap-1.5 py-3 px-5 rounded-[9px] text-[13px] font-bold cursor-pointer bg-linear-to-br from-blue-600 to-violet-600 text-white shadow-[0_3px_12px_rgba(37,99,235,0.3)] hover:-translate-y-px hover:shadow-[0_5px_18px_rgba(37,99,235,0.4)] transition-all">
                ✅ Submit laporan
            </button>
            <a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center gap-1.5 py-3 px-5 rounded-[9px] text-[13px] font-bold no-underline bg-slate-50 text-slate-500 border border-slate-200 hover:border-red-600 hover:text-red-600 transition-colors">
                ← Kembali
            </a>
        </div>
        </form>
        @else
        <a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center gap-1.5 py-3 px-5 rounded-[9px] text-[13px] font-bold no-underline bg-slate-50 text-slate-500 border border-slate-200 hover:border-red-600 hover:text-red-600 transition-colors">← Kembali ke dashboard</a>
        @endif

    </div>

    {{-- Kanan: Ringkasan shift --}}
    <div>
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm sticky top-20">
            <div class="py-3.5 px-4.5 bg-linear-to-br from-blue-600 to-violet-600 text-white">
                <div class="text-[13px] font-extrabold mb-0.5">📊 Ringkasan shift hari ini</div>
                <div class="text-[11px] opacity-75">{{ now()->isoFormat('D MMMM Y') }}</div>
            </div>
            <div class="p-3.5">
                <div class="flex items-center gap-3 py-3 px-3.5 bg-slate-50 border border-slate-200 rounded-[10px] mb-2">
                    <div class="text-xl">🛒</div>
                    <div>
                        <div class="text-[11px] text-slate-500 font-medium mb-0.5">Total transaksi</div>
                        <div class="text-[15px] font-extrabold text-blue-600">{{ $statsHariIni['total_transaksi'] }} transaksi</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 py-3 px-3.5 bg-slate-50 border border-slate-200 rounded-[10px] mb-2">
                    <div class="text-xl">💰</div>
                    <div>
                        <div class="text-[11px] text-slate-500 font-medium mb-0.5">Total pendapatan</div>
                        <div class="text-[15px] font-extrabold text-green-600">Rp {{ number_format($statsHariIni['total_pendapatan'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 py-3 px-3.5 bg-slate-50 border border-slate-200 rounded-[10px] mb-2">
                    <div class="text-xl">💵</div>
                    <div>
                        <div class="text-[11px] text-slate-500 font-medium mb-0.5">Tunai</div>
                        <div class="text-[15px] font-extrabold text-slate-900">Rp {{ number_format($statsHariIni['tunai'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 py-3 px-3.5 bg-slate-50 border border-slate-200 rounded-[10px] mb-2">
                    <div class="text-xl">📱</div>
                    <div>
                        <div class="text-[11px] text-slate-500 font-medium mb-0.5">QRIS</div>
                        <div class="text-[15px] font-extrabold text-slate-900">Rp {{ number_format($statsHariIni['qris'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 py-3 px-3.5 bg-slate-50 border border-slate-200 rounded-[10px]">
                    <div class="text-xl">📦</div>
                    <div>
                        <div class="text-[11px] text-slate-500 font-medium mb-0.5">Item terjual</div>
                        <div class="text-[15px] font-extrabold text-slate-900">{{ $statsHariIni['total_item'] }} pcs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    const kondisiStyles = {
        base:   ['border-slate-200', 'bg-slate-50', 'text-slate-500'],
        baik:   ['border-green-600', 'bg-green-50', 'text-green-600'],
        sedang: ['border-amber-600', 'bg-amber-50', 'text-amber-600'],
        buruk:  ['border-red-600', 'bg-red-50', 'text-red-600'],
    };

    function setKondisi(val) {
        ['baik', 'sedang', 'buruk'].forEach(key => {
            const btn = document.getElementById('kondisi' + key.charAt(0).toUpperCase() + key.slice(1));
            if (!btn) return;
            btn.classList.remove(...kondisiStyles.base, ...kondisiStyles.baik, ...kondisiStyles.sedang, ...kondisiStyles.buruk);
            btn.classList.add(...(key === val ? kondisiStyles[key] : kondisiStyles.base));
        });
        document.getElementById('kondisiInput').value = val;
    }

    // Set tampilan awal sesuai nilai kondisi_toko saat ini
    setKondisi(document.getElementById('kondisiInput').value);

    // Konfirmasi sebelum submit
    document.getElementById('laporanForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        if (confirm('Yakin ingin mengirimkan laporan harian? Data tidak dapat diubah setelah disubmit.')) {
            this.submit();
        }
    });
</script>
@endsection