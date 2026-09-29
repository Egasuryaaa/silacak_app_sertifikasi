<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Shipment;
use App\Models\TrackingHistory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Cabang Representatif
        $jakarta = Branch::create(['code' => 'CGK-01', 'name' => 'Cabang Jakarta Pusat', 'city' => 'Jakarta']);
        $cikarang = Branch::create(['code' => 'CKR-01', 'name' => 'Cabang Cikarang DRC', 'city' => 'Bekasi']);

        // 2. Seed Customer (Member & Non-Member)
        $member = Customer::create(['name' => 'PT Mitra Sentosa', 'phone' => '08123456789', 'is_member' => true]);
        $nonMember = Customer::create(['name' => 'Ahmad Fauzi', 'phone' => '08987654321', 'is_member' => false]);

        // 3. Seed Paket Uji dengan riwayat status
        $shipment = Shipment::create([
            'tracking_number'   => 'SLC0012601019999',
            'customer_id'       => $member->id,
            'origin_branch_id'  => $jakarta->id,
            'dest_branch_id'    => $cikarang->id,
            'sender_name'       => 'PT Mitra Sentosa',
            'sender_phone'      => '08123456789',
            'receiver_name'     => 'Budi Santoso',
            'receiver_phone'    => '08111222333',
            'receiver_address'  => 'Kawasan Industri GIIC Cikarang',
            'service_code'      => 'EXP',
            'actual_weight'     => 1.3,
            'length_cm'         => 10,
            'width_cm'          => 10,
            'height_cm'         => 10,
            'chargeable_weight' => 2, // Hasil ceil dari 1.3 kg
            'goods_value'       => 1500000,
            'base_fare'         => 30000, // 2 kg * Rp 15.000
            'discount_amount'   => 3000,  // Diskon 10%
            'insurance_fee'     => 3000,  // Asuransi 0.2% dari Rp 1.500.000
            'total_fee'         => 30000,
            'current_status'    => 'ON_TRANSIT',
        ]);

        TrackingHistory::create([
            'shipment_id' => $shipment->id,
            'branch_id'   => $jakarta->id,
            'status'      => 'MANIFEST',
            'description' => 'Paket masuk di Cabang Jakarta Pusat.',
            'recorded_at' => now()->subHours(4),
        ]);

        TrackingHistory::create([
            'shipment_id' => $shipment->id,
            'branch_id'   => $jakarta->id,
            'status'      => 'ON_TRANSIT',
            'description' => 'Paket diberangkatkan menuju Cikarang.',
            'recorded_at' => now()->subHours(2),
        ]);
    }
}