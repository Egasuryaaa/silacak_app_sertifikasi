<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiLacak - Lacak Status Kiriman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen">
    <header class="bg-blue-700 text-white shadow py-4 px-6 sticky top-0 z-50">
        <div class="max-w-2xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-black tracking-wider">SiLacak</h1>
            <a href="{{ route('desk.index') }}" class="text-xs bg-blue-800 hover:bg-blue-900 px-3 py-1.5 rounded-lg border border-blue-600 transition">Loket Cabang &rarr;</a>
        </div>
    </header>

    <main class="max-w-2xl mx-auto p-4 space-y-6">
        <!-- Form Pencarian Resi -->
        <section class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <form action="{{ route('tracking.search') }}" method="GET" class="space-y-3">
                <label for="resi" class="block text-sm font-semibold text-slate-600">Lacak Pengiriman Anda</label>
                <div class="flex gap-2">
                    <input type="text" id="resi" name="resi" value="{{ $trackingNumber }}" required placeholder="Masukkan Nomor Resi (mis: SLC0012601019999)" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm uppercase">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl transition shadow">Lacak</button>
                </div>
            </form>
            @if($error)
                <p class="mt-3 text-sm text-red-600 font-medium">{{ $error }}</p>
            @endif
        </section>

        <!-- Hasil Pencarian Resi -->
        @if($trackingData)
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 p-4 border-b border-slate-200 flex justify-between items-center">
                <div>
                    <span class="text-xs text-slate-500 block">No. Resi</span>
                    <span class="font-mono font-bold text-lg text-slate-900">{{ $trackingData['tracking_number'] }}</span>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase {{ $trackingData['status'] === 'DELIVERED' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $trackingData['status'] }}
                    </span>
                    <span class="block text-[11px] font-bold text-slate-500 mt-1">Layanan: {{ $trackingData['service'] }} ({{ $trackingData['chargeable_weight'] }} Kg)</span>
                </div>
            </div>

            <!-- Detail Asal & Tujuan -->
            <div class="grid grid-cols-2 gap-4 p-4 border-b border-slate-100 bg-slate-50/50 text-xs">
                <div>
                    <span class="text-slate-400 block uppercase font-bold text-[10px]">Dari</span>
                    <span class="font-semibold text-slate-700">{{ $trackingData['sender'] }}</span>
                    <span class="text-slate-500 block">{{ $trackingData['origin'] }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block uppercase font-bold text-[10px]">Tujuan</span>
                    <span class="font-semibold text-slate-700">{{ $trackingData['receiver'] }}</span>
                    <span class="text-slate-500 block">{{ $trackingData['destination'] }}</span>
                </div>
            </div>

            <!-- Timeline Status -->
            <div class="p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Riwayat Perjalanan Paket</h3>
                <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    @forelse($trackingData['histories'] as $history)
                    <div class="relative">
                        <div class="absolute -left-6 top-1 w-2.5 h-2.5 rounded-full bg-blue-600 ring-4 ring-white"></div>
                        <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($history['timestamp'])->format('d M Y, H:i') }} WIB</div>
                        <div class="font-bold text-sm text-slate-800 mt-0.5">{{ $history['status'] }} &bull; <span class="font-normal text-slate-600">{{ $history['location'] }}</span></div>
                        <p class="text-xs text-slate-500 mt-1">{{ $history['description'] }}</p>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400">Belum ada riwayat tercatat.</p>
                    @endforelse
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <a href="{{ route('shipments.print-label', $trackingData['tracking_number']) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs bg-slate-800 hover:bg-slate-900 text-white font-medium px-4 py-2 rounded-lg transition">
                    Cetak Ulang Label PDF &rarr;
                </a>
            </div>
        </section>
        @endif
    </main>
</body>
</html>