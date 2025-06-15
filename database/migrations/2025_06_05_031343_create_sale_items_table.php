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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->foreignId('header_id')->nullable();
            $table->integer('quantity');
            $table->double('end_price');
            $table->double('cost_price')->nullable();
            $table->double('unit_cost_price')->nullable();
            $table->double('product_price')->nullable();
            $table->double('discount')->nullable();
            $table->double('profit')->default(0);
            $table->integer('unit_id')->default(1);
            $table->integer('unit_count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
