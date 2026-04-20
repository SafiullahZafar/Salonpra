<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upsell_performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->integer('total_upsells')->default(0);
            $table->decimal('upsell_revenue', 10, 2)->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0);
            $table->decimal('average_upsell_value', 8, 2)->default(0);
            $table->dateTime('last_upsell_date')->nullable();
            $table->timestamps();
            $table->unique('staff_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upsell_performances');
    }
};
