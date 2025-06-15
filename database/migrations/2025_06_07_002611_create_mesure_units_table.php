<?php

//use App\Models\Products\MeasureUnitCount;
use App\Models\Products\MeasureUnitName;
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
        Schema::create('measure_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('count');
            $table->integer('discount')->default(0);
            $table->double('sell_price')->nullable();
            $table->double('cost_price')->nullable();
            $table->foreignId('product_id')->constrained('products','id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measure_units');
    }
};
