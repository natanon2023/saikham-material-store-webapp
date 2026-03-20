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
        Schema::create('withdrawal_item_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('withdrawal_item_id')->constrained('withdrawal_items');
            $table->integer('old_quantity');
            $table->integer('new_quantity');
            $table->string('reason')->nullable(); 
            $table->foreignId('edited_by')->constrained('users');
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
        Schema::dropIfExists('withdrawal_item_logs');
    }
};
