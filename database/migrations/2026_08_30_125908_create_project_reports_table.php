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
        Schema::create('project_reports', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->enum('report_type', ['weekly','bi_weekly','monthly',]);
            $table->date('report_date');
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending','completed','overdue',])->default('pending');
            $table->text('report_link')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('project_id');
            $table->index('report_type');
            $table->index('report_date');
            $table->index('due_date');
            $table->index('status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_reports');
    }
};
