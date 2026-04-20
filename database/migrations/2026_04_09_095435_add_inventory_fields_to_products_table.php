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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('min_stock_level')->default(5);
            $table->integer('max_stock_level')->nullable();
            $table->enum('product_type', ['retail', 'service_supply'])->default('retail');
            $table->decimal('cost_price', 8, 2)->nullable();
            $table->string('sku')->nullable()->unique();
            $table->text('description')->nullable();
            $table->boolean('track_inventory')->default(true);
            $table->date('last_restocked')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn([
                'supplier_id',
                'min_stock_level',
                'max_stock_level',
                'product_type',
                'cost_price',
                'sku',
                'description',
                'track_inventory',
                'last_restocked'
            ]);
        });
    }
};
