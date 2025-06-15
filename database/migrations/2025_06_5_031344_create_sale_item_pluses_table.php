<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('sale_item_pluses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('header_id')->constrained('headers')->cascadeOnDelete();
            $table->double('price')->default(0);
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('sale_item_pluses');
    }
};
