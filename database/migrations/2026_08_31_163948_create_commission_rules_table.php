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
        Schema::create('commission_rules', function (Blueprint $table) {
            $table->id();
            
            $table->enum('service_type', [
                'seo',
                'website',
                'digital_marketing',
                'custom',
            ]);

            $table->enum('commission_type', [
                'fixed',
            ])->default('fixed');

            $table->decimal('commission_amount', 12, 2);

            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['service_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_rules');
    }
};
