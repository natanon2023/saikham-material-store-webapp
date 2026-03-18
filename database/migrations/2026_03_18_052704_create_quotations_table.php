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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('quotation_number')->unique(); 
            $table->decimal('total_product_amount', 10, 2)->default(0); 
            $table->decimal('total_expense_amount', 10, 2)->default(0); 
            $table->decimal('total_labor_amount', 10, 2)->default(0);   
            $table->decimal('service_charge_amount', 10, 2)->default(0); 
            $table->decimal('vat_amount', 10, 2)->default(0); 
            $table->decimal('grand_total', 10, 2)->default(0); 
            $table->integer('version')->default(1); 
            $table->string('status')->default('active'); 
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
        Schema::dropIfExists('quotations');
    }
};
