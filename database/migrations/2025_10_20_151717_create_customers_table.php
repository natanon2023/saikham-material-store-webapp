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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->string('house_number', 50)->nullable();
            $table->string('village', 255)->nullable();
            $table->string('alley', 255)->nullable();
            $table->string('road', 255)->nullable();

            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('amphure_id')->nullable();
            $table->unsignedBigInteger('tambon_id')->nullable();
            $table->string('zip_code', 10)->nullable();

            $table->foreign('province_id')->references('id')->on('thai_provinces')->nullOnDelete();
            $table->foreign('amphure_id')->references('id')->on('thai_amphures')->nullOnDelete();
            $table->foreign('tambon_id')->references('id')->on('thai_tambons')->nullOnDelete();
           
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
        Schema::dropIfExists('customers');
    }
};
