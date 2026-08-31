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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_team_id')
                ->constrained('sales_teams')
                ->cascadeOnDelete();

            $table->foreignId('client_service_id')
                ->constrained('client_services')
                ->cascadeOnDelete();

            $table->foreignId('commission_rule_id')
                ->nullable()
                ->constrained('commission_rules')
                ->nullOnDelete();

            $table->decimal('commission_amount', 12, 2);

            $table->enum('status', [
                'pending',
                'approved',
                'paid',
                'cancelled',
            ])->default('pending');

            $table->date('earned_at')->nullable();

            $table->date('paid_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['sales_team_id', 'status']);
            $table->index('earned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
