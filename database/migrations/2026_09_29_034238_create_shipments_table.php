<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number', 30)->unique(); // Indeks unik mengatasi lambatnya pencarian
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('origin_branch_id')->constrained('branches');
            $table->foreignId('dest_branch_id')->constrained('branches');
            
            // Detail Pengirim & Penerima
            $table->string('sender_name', 100);
            $table->string('sender_phone', 20);
            $table->string('receiver_name', 100);
            $table->string('receiver_phone', 20);
            $table->text('receiver_address');

            // Data Paket & Dimensi
            $table->string('service_code', 10)->index(); // REG, EXP, KGO, SD
            $table->decimal('actual_weight', 8, 2);
            $table->unsignedInteger('length_cm');
            $table->unsignedInteger('width_cm');
            $table->unsignedInteger('height_cm');
            $table->unsignedInteger('chargeable_weight'); // Disimpan dalam Kg integer (ceil)

            // Komponen Biaya
            $table->decimal('goods_value', 14, 2)->default(0);
            $table->decimal('base_fare', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('insurance_fee', 12, 2)->default(0);
            $table->decimal('total_fee', 12, 2);

            // Status Pengiriman
            $table->string('current_status', 30)->default('MANIFEST')->index();
            $table->timestamps();

            // Indeks komposit untuk reporting cabang
            $table->index(['origin_branch_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};