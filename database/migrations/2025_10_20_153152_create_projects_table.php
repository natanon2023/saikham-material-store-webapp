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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code');
            $table->string('project_name');

             $table->unsignedBigInteger('customer_id')->nullable();
            

            $table->string('house_number', 50)->nullable();
            $table->string('village', 255)->nullable();
            $table->string('alley', 255)->nullable();
            $table->string('road', 255)->nullable();

            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('amphure_id')->nullable();
            $table->unsignedBigInteger('tambon_id')->nullable();
            $table->string('zip_code', 10)->nullable();

            $table->enum('status', [
                'pending_survey',      
                'surveying',           
                'pending_quotation',   
                'waiting_approval',    
                'approved',            
                'material_planning',  
                'waiting_purchase',    
                'ready_to_withdraw',    
                'materials_withdrawn', 
                'installing',         
                'completed',           
                'cancelled'            
            ])->default('pending_survey');

            $table->foreignId('assigned_surveyor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_installer_id')->nullable()->constrained('users')->onDelete('set null');

            $table->dateTime('survey_date')->nullable();
            $table->text('survey_notes')->nullable();
            $table->date('quotation_date')->nullable();
            $table->decimal('quotation_amount', 12, 2)->nullable();
            $table->date('approval_date')->nullable();
            $table->date('installation_start_date')->nullable();
            $table->date('installation_end_date')->nullable();

            $table->decimal('actual_material_cost', 12, 2)->default(0);
            $table->decimal('labor_cost', 12, 2)->default(0);
            $table->decimal('other_costs', 12, 2)->default(0);
            $table->decimal('total_profit', 12, 2)->default(0);

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            
            $table->foreign('province_id')->references('id')->on('thai_provinces')->nullOnDelete();
            $table->foreign('amphure_id')->references('id')->on('thai_amphures')->nullOnDelete();
            $table->foreign('tambon_id')->references('id')->on('thai_tambons')->nullOnDelete();
           
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
            $table->index('project_code');
            $table->index('survey_date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
