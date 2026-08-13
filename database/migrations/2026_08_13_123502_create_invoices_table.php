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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('client_service_id')
                ->nullable()
                ->constrained('client_services')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Invoice Information
            |--------------------------------------------------------------------------
            */

            $table->string('invoice_number')->unique();

            $table->date('invoice_date');

            $table->date('due_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Amounts
            |--------------------------------------------------------------------------
            */

            $table->decimal('subtotal', 12, 2)->default(0);

            $table->decimal('discount', 12, 2)->default(0);

            $table->decimal('tax', 12, 2)->default(0);

            $table->decimal('total_amount', 12, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'draft',
                'unpaid',
                'partial',
                'paid',
                'overdue',
                'cancelled',
            ])->default('draft');

            /*
            |--------------------------------------------------------------------------
            | Additional Information
            |--------------------------------------------------------------------------
            */

            $table->text('notes')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('user_id');
            $table->index('client_service_id');
            $table->index('invoice_date');
            $table->index('due_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
