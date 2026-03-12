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
        Schema::create('glass_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('glass_type_id')->constrained('glasstypes')->onDelete('cascade');
            $table->foreignId('colouritem_id')->constrained('colouritems')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glass_items');
    }
};
