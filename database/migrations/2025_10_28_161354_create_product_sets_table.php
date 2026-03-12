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
        Schema::create('product_sets', function (Blueprint $table) {
            $table->id();
            $table->string('detail')->nullable()->default('-');
            $table->foreignId('aluminium_profile_type_id')->constrained('aluminium_profile_types')->onDelete('cascade');
            $table->foreignId('glasstype_id')->constrained('glasstypes')->onDelete('cascade');
            $table->foreignId('colouritem_id')->constrained('colouritems')->onDelete('cascade');
            $table->foreignId('product_set_name_id')->constrained('product_set_names')->onDelete('cascade');

            $table->softDeletes();
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
        Schema::dropIfExists('product_sets');
    }
};
