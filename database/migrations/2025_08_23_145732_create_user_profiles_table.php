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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('house_number')->nullable();
            $table->string('moo')->nullable();
            $table->string('road')->nullable();
            $table->string('village')->nullable();
            $table->foreignId('province_id')->constrained('thai_provinces')->onDelete('cascade');
            $table->foreignId('amphure_id')->constrained('thai_amphures')->onDelete('cascade');
            $table->foreignId('tambon_id')->constrained('thai_tambons')->onDelete('cascade');
            $table->date('birth_date')->nullable();
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
        Schema::dropIfExists('user_profiles');
    }
};
