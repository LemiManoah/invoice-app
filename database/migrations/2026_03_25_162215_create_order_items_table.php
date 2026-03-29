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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('garment_type');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 15, 2)->nullable();
            $table->text('style_notes')->nullable();
            $table->text('fabric_details')->nullable();
            $table->string('color')->nullable();
            $table->text('lining_details')->nullable();
            $table->text('button_details')->nullable();
            $table->string('monogram_text')->nullable();
            $table->boolean('urgent_flag')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
