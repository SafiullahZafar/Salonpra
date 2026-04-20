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
        Schema::create('cash_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->date('date');
            $table->decimal('opening_balance', 10, 2);
            $table->decimal('expected_cash', 10, 2);
            $table->decimal('actual_cash', 10, 2);
            $table->decimal('difference', 10, 2);
            $table->text('notes')->nullable();
            $table->enum('status', ['matched', 'discrepancy'])->default('matched');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_reconciliations');
    }
};
