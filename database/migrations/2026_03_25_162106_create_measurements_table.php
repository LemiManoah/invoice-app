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
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->decimal('neck', 8, 2)->nullable();
            $table->decimal('chest', 8, 2)->nullable();
            $table->decimal('waist', 8, 2)->nullable();
            $table->decimal('hips', 8, 2)->nullable();
            $table->decimal('shoulder', 8, 2)->nullable();
            $table->decimal('sleeve_length', 8, 2)->nullable();
            $table->decimal('jacket_length', 8, 2)->nullable();
            $table->decimal('trouser_waist', 8, 2)->nullable();
            $table->decimal('trouser_length', 8, 2)->nullable();
            $table->decimal('inseam', 8, 2)->nullable();
            $table->decimal('thigh', 8, 2)->nullable();
            $table->decimal('knee', 8, 2)->nullable();
            $table->decimal('cuff', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->text('posture_notes')->nullable();
            $table->text('fitting_notes')->nullable();
            $table->boolean('is_current')->default(false);
            $table->foreignId('measured_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('measurement_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};
