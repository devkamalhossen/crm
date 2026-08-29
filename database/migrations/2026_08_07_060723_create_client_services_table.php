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
        Schema::create('client_services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('sales_team_id')->nullable()->constrained('sales_teams')->nullOnDelete();
            $table->enum('service_type', ['seo','website','digital_marketing']);
            $table->enum('payment_type', ['monthly','project_based','yearly']);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('advance_amount', 12, 2)->default(0);
            $table->integer('installment_months')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('project_status', ['in_progress','completed','delivered'])->nullable();
            $table->enum('status', ['active','expired','cancelled'])->default('active');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_services');
    }
};
