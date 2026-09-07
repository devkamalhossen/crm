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
        Schema::create('sms_reminder_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('trigger_type', ['before_due','after_due',]);
            $table->unsignedInteger('days')->default(0);
            $table->text('message');
            $table->enum('status', ['active','inactive',])->default('active');
            $table->timestamps();

            $table->index(['trigger_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_reminder_settings');
    }
};
