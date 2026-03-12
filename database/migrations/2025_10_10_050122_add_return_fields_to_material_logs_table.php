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
        Schema::table('material_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('original_withdraw_id')->nullable()->after('cost_per_unit');
            $table->text('return_note')->nullable()->after('is_size_changed');
            
   
            $table->foreign('original_withdraw_id')
                ->references('id')
                ->on('material_logs')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('material_logs', function (Blueprint $table) {
            $table->dropForeign(['original_withdraw_id']);
            $table->dropColumn([
                'original_withdraw_id',
                'return_note'
            ]);
        });
    }
};
