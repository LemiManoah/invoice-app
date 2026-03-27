<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('amount')->constrained('payment_methods')->nullOnDelete();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('amount')->constrained('payment_methods')->nullOnDelete();
        });

        $now = now();

        $defaults = [
            ['name' => 'Cash', 'sort_order' => 1],
            ['name' => 'Bank Transfer', 'sort_order' => 2],
            ['name' => 'Mobile Money', 'sort_order' => 3],
            ['name' => 'Card', 'sort_order' => 4],
            ['name' => 'Check', 'sort_order' => 5],
            ['name' => 'Other', 'sort_order' => 6],
        ];

        foreach ($defaults as $method) {
            DB::table('payment_methods')->insert([
                'name' => $method['name'],
                'slug' => Str::slug($method['name']),
                'is_active' => true,
                'sort_order' => $method['sort_order'],
                'notes' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $normalizations = [
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'bank transfer' => 'Bank Transfer',
            'mobile_money' => 'Mobile Money',
            'mobile money' => 'Mobile Money',
            'card' => 'Card',
            'credit_card' => 'Card',
            'credit card' => 'Card',
            'check' => 'Check',
            'cheque' => 'Check',
            'other' => 'Other',
        ];

        foreach (['payments', 'expenses'] as $table) {
            $rows = DB::table($table)->select('id', 'payment_method')->get();

            foreach ($rows as $row) {
                if ($row->payment_method === null) {
                    continue;
                }

                $lookupKey = Str::of($row->payment_method)->lower()->replace('-', '_')->replace(' ', '_')->value();
                $mappedName = $normalizations[$lookupKey]
                    ?? Str::of($row->payment_method)->replace('_', ' ')->title()->value();

                $paymentMethodId = DB::table('payment_methods')->where('name', $mappedName)->value('id');

                if ($paymentMethodId === null) {
                    $baseSlug = Str::slug($mappedName) ?: 'payment-method';
                    $slug = $baseSlug;
                    $suffix = 2;

                    while (DB::table('payment_methods')->where('slug', $slug)->exists()) {
                        $slug = $baseSlug.'-'.$suffix;
                        $suffix++;
                    }

                    $paymentMethodId = DB::table('payment_methods')->insertGetId([
                        'name' => $mappedName,
                        'slug' => $slug,
                        'is_active' => true,
                        'sort_order' => 999,
                        'notes' => 'Imported from existing financial data.',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                DB::table($table)
                    ->where('id', $row->id)
                    ->update([
                        'payment_method' => $mappedName,
                        'payment_method_id' => $paymentMethodId,
                    ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_method_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_method_id');
        });

        Schema::dropIfExists('payment_methods');
    }
};
