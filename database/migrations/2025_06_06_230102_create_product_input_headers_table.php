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
        Schema::create('product_input_headers', function (Blueprint $table) {
            $table->id();
            $table->double('total_price')->default(0);
            $table->foreignId('vendor_id')->nullable();
            $table->integer('bill_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_input_headers');
    }
};
