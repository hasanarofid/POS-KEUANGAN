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
        $tables = [
            'customers',
            'delivery_notes',
            'expenses',
            'products',
            'production_returns',
            'purchases',
            'sales',
            'settings',
            'stock_entries',
            'warehouse_pickups',
            'warehouse_returns',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'customers',
            'delivery_notes',
            'expenses',
            'products',
            'production_returns',
            'purchases',
            'sales',
            'settings',
            'stock_entries',
            'warehouse_pickups',
            'warehouse_returns',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            });
        }
    }
};
