<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiLacak - Loket Transaksi Cabang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen">
    <header class="bg-slate-900 text-white shadow py-3 px-6">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-bold tracking-wide">SiLacak Desk</h1>
                <span class="text-xs bg-slate-800 text-slate-400 px-2 py-0.5 rounded">Loket Cabang</span>
            </div>
            <a href="{{ route('tracking.index') }}" class="text-xs text-slate-300 hover:text-white underline">Halaman Lacak Publik &rarr;</a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Transaksi Paket -->
        <section class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h2 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-3 mb-4">Input Pengiriman Baru</h2>
            
            <form id="shipmentForm" class="space-y-4">
                <!-- Rute Cabang -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Cabang Asal</label>
                        <select id="origin_branch_id" name="origin_branch_id" required class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500">
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->code }} - {{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Cabang Tujuan</label>
                        <select id="dest_branch_id" name="dest_branch_id" required class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500">
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->code }} - {{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Pengirim & Member -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Pelanggan Terdaftar (Member 10%)</label>
                        <select id="customer_id" name="customer_id" class="w-full px-3 py-2 text-sm border rounded-lg">
                            <option value="">-- Non-Member / Umum --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" data-member="{{ $c->is_member ? '1' : '0' }}" data-name="{{ $c->name }}" data-phone="{{ $c->phone }}">{{ $c->name }} ({{ $c->phone }}) {{ $c->is_member ? '[MEMBER]' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Pengirim</label>
                        <input type="text" id="sender_name" name="sender_name" required class="w-full px-3 py-2 text-sm border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">No. Telp Pengirim</label>
                        <input type="text" id="sender_phone" name="sender_phone" required class="w-full px-3 py-2 text-sm border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Penerima</label>
                        <input type="text" id="receiver_name" name="receiver_name" required class="w-full px-3 py-2 text-sm border rounded-lg">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">No. Telp & Alamat Lengkap Penerima</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <input type="text" id="receiver_phone" name="receiver_phone" placeholder="Telp Penerima" required class="px-3 py-2 text-sm border rounded-lg">
                            <input type="text" id="receiver_address" name="receiver_address" placeholder="Alamat Tujuan Penerima" required class="md:col-span-2 px-3 py-2 text-sm border rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Dimensi & Layanan -->
                <div class="border-t pt-4 space-y-3">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Layanan</label>
                            <select id="service_code" name="service_code" required class="w-full px-3 py-2 text-sm border rounded-lg font-bold">
                                <option value="REG">Reguler (Rp9rb)</option>
                                <option value="EXP">Express (Rp15rb)</option>
                                <option value="KGO">Kargo (Rp6rb, min 10kg)</option>
                                <option value="SD">Same Day (Rp25rb)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Berat Aktual (Kg)</label>
                            <input type="number" step="0.1" id="actual_weight" name="actual_weight" value="1.0" required class="w-full px-3 py-2 text-sm border rounded-lg">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Dimensi: P x L x T (cm)</label>
                            <div class="flex gap-2">
                                <input type="number" id="length_cm" name="length_cm" value="10" placeholder="P" class="w-1/3 px-2 py-2 text-sm border rounded-lg text-center">
                                <input type="number" id="width_cm" name="width_cm" value="10" placeholder="L" class="w-1/3 px-2 py-2 text-sm border rounded-lg text-center">
                                <input type="number" id="height_cm" name="height_cm" value="10" placeholder="T" class="w-1/3 px-2 py-2 text-sm border rounded-lg text-center">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nilai Barang (Rp) - Asuransi 0.2% jika > Rp1.000.000</label>
                        <input type="number" id="goods_value" name="goods_value" value="0" class="w-full px-3 py-2 text-sm border rounded-lg">
                    </div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" id="btnHitung" class="w-1/2 bg-slate-700 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl transition text-sm">Hitung Simulasi</button>
                    <button type="submit" id="btnSimpan" class="w-1/2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition text-sm">Daftarkan & Cetak</button>
                </div>
            </form>
        </section>

        <!-- Panel Ringkasan Kalkulasi Ongkir -->
        <section class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 h-fit space-y-4">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Kalkulasi Ongkos Kirim</h3>
            
            <div class="space-y-3 text-sm divide-y">
                <div class="flex justify-between pt-2">
                    <span class="text-slate-500">Berat Tagih:</span>
                    <span id="resChargeableWeight" class="font-bold text-slate-800">- Kg</span>
                </div>
                <div class="flex justify-between pt-2">
                    <span class="text-slate-500">Tarif Dasar:</span>
                    <span id="resBaseFare" class="font-medium text-slate-700">Rp 0</span>
                </div>
                <div class="flex justify-between pt-2">
                    <span class="text-slate-500">Diskon Member (10%):</span>
                    <span id="resDiscount" class="font-medium text-emerald-600">- Rp 0</span>
                </div>
                <div class="flex justify-between pt-2">
                    <span class="text-slate-500">Asuransi (0.2%):</span>
                    <span id="resInsurance" class="font-medium text-slate-700">Rp 0</span>
                </div>
                <div class="flex justify-between pt-3 text-base">
                    <span class="font-bold text-slate-900">Total Ongkir:</span>
                    <span id="resTotal" class="font-black text-blue-600">Rp 0</span>
                </div>
            </div>

            <div id="printSection" class="hidden p-4 bg-emerald-50 border border-emerald-200 rounded-xl space-y-2 text-center">
                <p class="text-xs text-emerald-700 font-bold">Resi Baru Berhasil Diterbitkan!</p>
                <a id="btnDownloadLabel" href="#" target="_blank" class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2 rounded-lg transition">Buka Label PDF (Termal)</a>
            </div>
        </section>
    </main>

    <script>
        const customerSelect = document.getElementById('customer_id');
        customerSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt.value) {
                document.getElementById('sender_name').value = opt.getAttribute('data-name');
                document.getElementById('sender_phone').value = opt.getAttribute('data-phone');
            }
            hitungTarif();
        });

        async function hitungTarif() {
            const payload = {
                service_code: document.getElementById('service_code').value,
                actual_weight: parseFloat(document.getElementById('actual_weight').value) || 0,
                length: parseInt(document.getElementById('length_cm').value) || 1,
                width: parseInt(document.getElementById('width_cm').value) || 1,
                height: parseInt(document.getElementById('height_cm').value) || 1,
                goods_value: parseFloat(document.getElementById('goods_value').value) || 0,
                customer_id: document.getElementById('customer_id').value || null
            };

            try {
                const res = await fetch('/api/shipping/calculate', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                if (result.status === 'success') {
                    const d = result.data;
                    document.getElementById('resChargeableWeight').textContent = d.chargeable_weight + ' Kg';
                    document.getElementById('resBaseFare').textContent = 'Rp ' + Number(d.base_fare).toLocaleString('id-ID');
                    document.getElementById('resDiscount').textContent = '- Rp ' + Number(d.discount_amount).toLocaleString('id-ID');
                    document.getElementById('resInsurance').textContent = 'Rp ' + Number(d.insurance_fee).toLocaleString('id-ID');
                    document.getElementById('resTotal').textContent = 'Rp ' + Number(d.total_fee).toLocaleString('id-ID');
                }
            } catch (e) {
                console.error('Gagal kalkulasi:', e);
            }
        }

        document.getElementById('btnHitung').addEventListener('click', hitungTarif);

        document.getElementById('shipmentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSimpan');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const payload = {
                origin_branch_id: document.getElementById('origin_branch_id').value,
                dest_branch_id: document.getElementById('dest_branch_id').value,
                customer_id: document.getElementById('customer_id').value || null,
                sender_name: document.getElementById('sender_name').value,
                sender_phone: document.getElementById('sender_phone').value,
                receiver_name: document.getElementById('receiver_name').value,
                receiver_phone: document.getElementById('receiver_phone').value,
                receiver_address: document.getElementById('receiver_address').value,
                service_code: document.getElementById('service_code').value,
                actual_weight: parseFloat(document.getElementById('actual_weight').value),
                length_cm: parseInt(document.getElementById('length_cm').value),
                width_cm: parseInt(document.getElementById('width_cm').value),
                height_cm: parseInt(document.getElementById('height_cm').value),
                goods_value: parseFloat(document.getElementById('goods_value').value) || 0
            };

            try {
                const res = await fetch('/api/v1/shipments', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                if (result.status === 'success') {
                    const printBox = document.getElementById('printSection');
                    const link = document.getElementById('btnDownloadLabel');
                    link.href = result.data.label_url;
                    printBox.classList.remove('hidden');
                    window.open(result.data.label_url, '_blank');
                } else {
                    alert('Gagal mendaftar: ' + (result.message || 'Terjadi galat input.'));
                }
            } catch (err) {
                alert('Terjadi kesalahan koneksi server.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Daftarkan & Cetak';
            }
        });
    </script>
</body>
</html>