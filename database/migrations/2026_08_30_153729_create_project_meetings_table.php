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
        Schema::create('project_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_report_id')->nullable()->constrained('project_reports')->nullOnDelete();
            $table->date('meeting_date');
            $table->enum('status', ['scheduled','completed','cancelled','missed',])->default('scheduled');
            $table->string('title')->nullable();
            $table->text('agenda')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('completed_at')->nullable();
            // Reminder
            $table->dateTime('reminder_sent_at')->nullable();

            $table->timestamps();

            $table->index('project_id');
            $table->index('meeting_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_meetings');
    }
};
