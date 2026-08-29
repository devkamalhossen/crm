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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            // Client Service
            $table->foreignId('client_service_id')->constrained('client_services')->cascadeOnDelete();
            // Project Manager
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->unsignedInteger('duration_months')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['not_started','in_progress','completed','delivered','on_hold','cancelled',])->default('not_started');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('client_service_id');
            $table->index('project_manager_id');
            $table->index('start_date');
            $table->index('end_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
