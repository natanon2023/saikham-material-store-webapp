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
        Schema::table('consumable_items', function (Blueprint $table) {
            //
            $table->integer('current_stock')->default(0);
            $table->decimal('cost_per_unit', 10, 2);
            $table->decimal('selling_price_per_unit', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consumable_items', function (Blueprint $table) {
            //
            $table->dropColumn('current_stock');
            $table->dropColumn('cost_per_unit');
            $table->dropColumn('selling_price_per_unit');
        });
    }
};
