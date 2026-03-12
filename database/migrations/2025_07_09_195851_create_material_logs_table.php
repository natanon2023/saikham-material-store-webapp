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
        Schema::create('material_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');

            $table->foreignId('glass_size_id')->nullable()->constrained('glass_sizes')->onDelete('cascade');
            $table->foreignId('aluminium_length_id')->nullable()->constrained('aluminium_lengths')->onDelete('cascade');

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->enum('direction', ['in', 'out']);
            $table->enum('action_type', ['purchase','usage','return','manual_adjustment']);


            $table->integer('quantity_changed');
            $table->integer('result_stock')->nullable();


            $table->text('note')->nullable();
            $table->timestamp('action_date')->useCurrent();
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
        Schema::dropIfExists('material_logs');
    }
};
