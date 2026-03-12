<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('glass_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('glass_item_id')->constrained('glass_items')->onDelete('cascade');

            $table->decimal('width_meter', 8, 2);   
            $table->decimal('length_meter', 8, 2);

            $table->integer('current_stock')->default(0);
            $table->decimal('cost_per_unit', 10, 2);
            $table->decimal('selling_price_per_unit', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('glass_sizes');
    }
};
