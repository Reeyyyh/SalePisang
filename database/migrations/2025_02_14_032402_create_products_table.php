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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('product_name');
            $table->string('sku')->nullable(); // tetap dipakai buat kode unik
            $table->decimal('price', 15, 2); // harga per kg / per sisir
            $table->unsignedInteger('stock'); // stok jumlah (kg / sisir)
            $table->enum('status', ['available', 'out_of_stock'])->default('available');
            $table->decimal('weight', 8, 2)->nullable(); // berat per unit
            $table->boolean('is_featured')->default(false);
            $table->text('description')->nullable();

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
