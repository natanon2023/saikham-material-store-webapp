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
        Schema::create('thai_tambons', function (Blueprint $table) {
            $table->id();
            $table->integer('zip_code');
            $table->string('name_th');
            $table->string('name_en');
            $table->foreignId('amphure_id')->constrained('thai_amphures')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thai_tambons');
    }
};
