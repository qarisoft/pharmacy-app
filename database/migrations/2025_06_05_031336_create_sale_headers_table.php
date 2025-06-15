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
        Schema::create('sale_headers', function (Blueprint $table) {
            $table->id();
            $table->double('end_price')->default(0);
            $table->double('profit_price')->nullable();
            $table->double('cost_price')->nullable();
            $table->double('discount')->default(0);
            $table->double('addition')->default(0);
            $table->string('customer_name')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_headers');
    }
};
