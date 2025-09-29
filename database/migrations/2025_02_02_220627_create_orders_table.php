<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('invoice_number')->unique(); // sebaiknya string, bukan uuid
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_amount', 15, 2);
            $table->text('shipping_address');

            // status order internal
            $table->enum('orders_status', [
                'pending',
                'paid',
                'processing',
                'shipped',
                'delivered',
                'canceled',
                'expired'
            ])->default('pending');

            // 🔹 field tambahan untuk integrasi Tripay
            $table->string('tripay_reference')->nullable();   // kode unik transaksi dari Tripay
            $table->string('payment_method')->nullable();     // channel pembayaran (BRIVA, QRIS, dll)
            $table->string('payment_url')->nullable();        // link checkout Tripay
            $table->timestamp('paid_at')->nullable();         // waktu ketika pembayaran berhasil
            $table->timestamp('payment_expired_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
