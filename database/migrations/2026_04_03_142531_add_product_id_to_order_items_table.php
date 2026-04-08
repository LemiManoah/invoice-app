<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('order_items', 'product_id')) {
            return;
        }

        Schema::table('order_items', function (Blueprint $table) {
            // Older SQLite databases were created before this nullable relation existed.
            $table->unsignedBigInteger('product_id')->nullable()->after('garment_type');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('order_items', 'product_id')) {
            return;
        }

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });
    }
};
