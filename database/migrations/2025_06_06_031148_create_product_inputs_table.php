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
        Schema::create('product_inputs', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->foreignId('product_id');
            $table->foreignId('header_id')->nullable();
            $table->foreignId('vendor_id')->nullable();
            $table->enum('payment_type', ['cash', 'debit'])->default('cash');
            $table->double('total_cost_price');
            $table->double('unit_cost_price');
            $table->timestamp('expire_date')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_inputs');
    }
};
