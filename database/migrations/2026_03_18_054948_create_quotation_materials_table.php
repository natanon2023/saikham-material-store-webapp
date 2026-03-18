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
        Schema::create('quotation_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->cascadeOnDelete();
            $table->string('material_type'); 
            $table->string('description')->nullable(); 
            $table->string('lot_number')->nullable(); 
            $table->decimal('unit_price', 10, 2)->default(0); 
            $table->integer('quantity')->default(0); 
            $table->decimal('total_price', 10, 2)->default(0); 
            $table->string('remark')->nullable(); 
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
        Schema::dropIfExists('quotation_materials');
    }
};
