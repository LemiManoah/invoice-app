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
        $tables = ['invoices', 'orders', 'payments', 'expenses'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                // We make it nullable so we can migrate seamlessly, but we'll fall back to default currency if null
                $table->foreignId('currency_id')->nullable()->constrained('currencies');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['invoices', 'orders', 'payments', 'expenses'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['currency_id']);
                $table->dropColumn('currency_id');
            });
        }
    }
};
