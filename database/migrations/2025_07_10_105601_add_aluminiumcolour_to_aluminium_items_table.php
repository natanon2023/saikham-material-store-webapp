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
        Schema::table('aluminium_items', function (Blueprint $table) {
            //
            $table->foreignId('aluminium_profile_types_id')->constrained('aluminium_profile_types')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aluminium_items', function (Blueprint $table) {
            //
            $table->dropColumn('aluminium_profile_types_id');
        });
    }
};
