<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('measurements', function (Blueprint $table) {
            // Jacket piece fields (jacket_length already exists)
            $table->decimal('jacket_shoulder', 8, 2)->nullable()->after('jacket_length');
            $table->decimal('jacket_chest', 8, 2)->nullable()->after('jacket_shoulder');
            $table->decimal('jacket_stomach_waist', 8, 2)->nullable()->after('jacket_chest');
            $table->decimal('jacket_sleeve', 8, 2)->nullable()->after('jacket_stomach_waist');
            $table->decimal('jacket_biceps', 8, 2)->nullable()->after('jacket_sleeve');
            $table->decimal('jacket_wrist', 8, 2)->nullable()->after('jacket_biceps');
            $table->decimal('jacket_lower_arm', 8, 2)->nullable()->after('jacket_wrist');
            $table->decimal('jacket_hip_line', 8, 2)->nullable()->after('jacket_lower_arm');

            // Trouser piece fields (trouser_waist already exists)
            $table->decimal('trouser_thigh_cuff', 8, 2)->nullable()->after('trouser_length');
            $table->decimal('trouser_length_fit', 8, 2)->nullable()->after('trouser_thigh_cuff');
            $table->decimal('trouser_ankle_fit', 8, 2)->nullable()->after('trouser_length_fit');
            $table->decimal('trouser_knee_fit', 8, 2)->nullable()->after('trouser_ankle_fit');
            $table->decimal('trouser_fly_fit', 8, 2)->nullable()->after('trouser_knee_fit');
            $table->decimal('trouser_hips', 8, 2)->nullable()->after('trouser_fly_fit');

            // Waistcoat piece fields
            $table->decimal('waistcoat_chest', 8, 2)->nullable()->after('trouser_hips');
            $table->decimal('waistcoat_waist', 8, 2)->nullable()->after('waistcoat_chest');
            $table->decimal('waistcoat_length', 8, 2)->nullable()->after('waistcoat_waist');

            // Skirt piece fields (for female customers)
            $table->decimal('skirt_waist', 8, 2)->nullable()->after('waistcoat_length');
            $table->decimal('skirt_hip_line', 8, 2)->nullable()->after('skirt_waist');
            $table->decimal('skirt_full_length', 8, 2)->nullable()->after('skirt_hip_line');

            // Shirt piece fields
            $table->decimal('shirt_chest', 8, 2)->nullable()->after('skirt_full_length');
            $table->decimal('shirt_waist', 8, 2)->nullable()->after('shirt_chest');
            $table->decimal('shirt_shoulder', 8, 2)->nullable()->after('shirt_waist');
            $table->decimal('shirt_full_length', 8, 2)->nullable()->after('shirt_shoulder');
            $table->decimal('shirt_bottom_cut', 8, 2)->nullable()->after('shirt_full_length');
        });
    }

    public function down(): void
    {
        Schema::table('measurements', function (Blueprint $table) {
            $table->dropColumn([
                'jacket_shoulder', 'jacket_chest', 'jacket_stomach_waist', 'jacket_sleeve',
                'jacket_biceps', 'jacket_wrist', 'jacket_lower_arm', 'jacket_hip_line',
                'trouser_thigh_cuff', 'trouser_length_fit', 'trouser_ankle_fit',
                'trouser_knee_fit', 'trouser_fly_fit', 'trouser_hips',
                'waistcoat_chest', 'waistcoat_waist', 'waistcoat_length',
                'skirt_waist', 'skirt_hip_line', 'skirt_full_length',
                'shirt_chest', 'shirt_waist', 'shirt_shoulder', 'shirt_full_length', 'shirt_bottom_cut',
            ]);
        });
    }
};
