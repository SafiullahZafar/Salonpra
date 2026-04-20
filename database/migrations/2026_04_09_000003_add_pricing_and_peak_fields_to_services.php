<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->json('pricing_levels')->nullable()->after('price')->comment('Staff-level pricing tiers');
            $table->boolean('peak_pricing_enabled')->default(false)->after('duration');
            $table->decimal('peak_price', 10, 2)->nullable()->after('peak_pricing_enabled');
            $table->time('peak_start')->nullable()->after('peak_price');
            $table->time('peak_end')->nullable()->after('peak_start');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['pricing_levels', 'peak_pricing_enabled', 'peak_price', 'peak_start', 'peak_end']);
        });
    }
};
