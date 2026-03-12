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
        Schema::create('stock_edit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id'); 
            $table->unsignedBigInteger('user_id');     
            $table->integer('old_quantity');          
            $table->integer('new_quantity');          
            $table->text('reason');                      
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('stock_edit_logs');
    }
};
