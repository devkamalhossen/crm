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
        Schema::create('sales_teams', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('name');
            $table->string('designation');
            $table->string('mobile_number', 20)->nullable();
            $table->string('email')->nullable()->unique();
            $table->date('joining_date')->nullable();
            $table->enum('status', ['active','inactive',])->default('active');
            $table->timestamps();
        });

        // pivot table Sales Team & Client Services 
        Schema::create('sales_team_client_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_team_id')->constrained('sales_teams')->cascadeOnDelete();
            $table->foreignId('client_service_id')->constrained('client_services')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_team_client_service');
        Schema::dropIfExists('sales_teams');
    }
};
