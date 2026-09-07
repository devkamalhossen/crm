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
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('sms_reminder_setting_id')->nullable()->constrained('sms_reminder_settings')->nullOnDelete();
            $table->string('phone');
            $table->enum('trigger_type', ['before_due','after_due','manual',]);
            $table->text('message');
            $table->enum('status', ['pending','sent','failed',])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->text('provider_response')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['invoice_id', 'trigger_type']);
            $table->index(['user_id', 'status']);
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
