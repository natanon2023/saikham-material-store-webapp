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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();

            $table->string('material_type', 50);



            $table->foreignId('aluminium_item_id')->nullable()->constrained('aluminium_items')->onDelete('restrict');
            $table->foreignId('glass_item_id')->nullable()->constrained('glass_items')->onDelete('restrict');
            $table->foreignId('accessory_item_id')->nullable()->constrained('accessory_items')->onDelete('restrict');
            $table->foreignId('tool_item_id')->nullable()->constrained('tool_items')->onDelete('restrict');
            $table->foreignId('consumable_item_id')->nullable()->constrained('consumable_items')->onDelete('restrict');


            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};















