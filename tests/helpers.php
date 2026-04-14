<?php

declare(strict_types=1);

if (! function_exists('seedBaselineCurrencies')) {
    /**
     * Create the baseline currencies (UGX default, USD, KES).
     *
     * @return array{ugx: \App\Models\Currency, usd: \App\Models\Currency, kes: \App\Models\Currency}
     */
    function seedBaselineCurrencies(): array
    {
        $upsertCurrency = static fn (array $attributes, array $values): \App\Models\Currency => \App\Models\Currency::query()
            ->updateOrCreate($attributes, $values);

        return [
            'ugx' => $upsertCurrency(
                ['code' => 'UGX'],
                [
                    'name' => 'Ugandan Shilling',
                    'symbol' => 'UGX',
                    'decimal_places' => 0,
                    'exchange_rate' => 1.000000,
                    'is_default' => true,
                    'is_active' => true,
                    'sort_order' => 1,
                ],
            ),
            'usd' => $upsertCurrency(
                ['code' => 'USD'],
                [
                    'name' => 'US Dollar',
                    'symbol' => '$',
                    'decimal_places' => 2,
                    'exchange_rate' => 3800.000000,
                    'is_default' => false,
                    'is_active' => true,
                    'sort_order' => 2,
                ],
            ),
            'kes' => $upsertCurrency(
                ['code' => 'KES'],
                [
                    'name' => 'Kenyan Shilling',
                    'symbol' => 'KSh',
                    'decimal_places' => 0,
                    'exchange_rate' => 30.000000,
                    'is_default' => false,
                    'is_active' => true,
                    'sort_order' => 3,
                ],
            ),
        ];
    }
}

if (! function_exists('adminActor')) {
    function adminActor(): \App\Models\User
    {
        $permissions = [
            'invoices.view', 'invoices.create', 'invoices.update', 'invoices.issue',
            'invoices.cancel', 'invoices.print',
            'payments.view', 'payments.create', 'payments.void',
            'orders.view', 'orders.create', 'orders.update', 'orders.delete',
            'reports.view', 'customers.view',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::findOrCreate($permission, 'web');
        }

        $admin = \Spatie\Permission\Models\Role::findOrCreate('Admin', 'web');
        $admin->syncPermissions($permissions);

        $user = \App\Models\User::factory()->create();
        $user->assignRole($admin);

        return $user;
    }
}

if (! function_exists('userWithPermissions')) {
    /**
     * @param  array<int, string>  $permissions
     */
    function userWithPermissions(array $permissions): \App\Models\User
    {
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::findOrCreate($permission, 'web');
        }

        $user = \App\Models\User::factory()->create();
        if ($permissions) {
            $user->givePermissionTo($permissions);
        }

        return $user;
    }
}
