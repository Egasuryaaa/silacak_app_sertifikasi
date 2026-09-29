<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tracking_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('status', 50); // MANIFEST, ON_TRANSIT, OUT_FOR_DELIVERY, DELIVERED
            $table->text('description');
            $table->timestamp('recorded_at')->index();
            $table->timestamps();

            // Indeks untuk memotong latensi riwayat status resi
            $table->index(['shipment_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_histories');
    }
};