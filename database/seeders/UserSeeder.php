<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get roles from RoleAndPermissionSeeder
        $adminRole = Role::where('name', 'Admin')->first();
        $salesRole = Role::where('name', 'Sales')->first();
        $accountantRole = Role::where('name', 'Accountant')->first();
        $tailorRole = Role::where('name', 'Tailor')->first();

        // Create Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@suits.com'],
            [
                'name' => 'System Administrator',
                'phone' => '+254700000000',
                'password' => Hash::make('password'),
                'is_active' => true,
                'theme_preference' => 'light',
            ]
        );
        $admin->syncRoles([$adminRole]);

        // Create Sales users
        $salesUsers = [
            [
                'name' => 'John Salesman',
                'email' => 'john.sales@suits.com',
                'phone' => '+254711111111',
            ],
            [
                'name' => 'Mary Seller',
                'email' => 'mary.seller@suits.com',
                'phone' => '+254722222222',
            ],
        ];

        foreach ($salesUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'theme_preference' => 'light',
                ])
            );
            $user->syncRoles([$salesRole]);
        }

        // Create Accountant users
        $accountantUsers = [
            [
                'name' => 'James Accountant',
                'email' => 'james.accountant@suits.com',
                'phone' => '+254733333333',
            ],
            [
                'name' => 'Patricia Finance',
                'email' => 'patricia.finance@suits.com',
                'phone' => '+254744444444',
            ],
        ];

        foreach ($accountantUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'theme_preference' => 'light',
                ])
            );
            $user->syncRoles([$accountantRole]);
        }

        // Create Tailor users
        $tailorUsers = [
            [
                'name' => 'Robert Tailor',
                'email' => 'robert.tailor@suits.com',
                'phone' => '+254755555555',
            ],
            [
                'name' => 'Susan Designer',
                'email' => 'susan.designer@suits.com',
                'phone' => '+254766666666',
            ],
        ];

        foreach ($tailorUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'theme_preference' => 'light',
                ])
            );
            $user->syncRoles([$tailorRole]);
        }

        // Create some inactive users for testing
        $inactiveUsers = [
            [
                'name' => 'Former Employee',
                'email' => 'former.employee@suits.com',
                'phone' => '+254777777777',
                'role' => $salesRole,
            ],
        ];

        foreach ($inactiveUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'phone' => $userData['phone'],
                    'password' => Hash::make('password'),
                    'is_active' => false,
                    'theme_preference' => 'light',
                ]
            );
            if (isset($userData['role'])) {
                $user->syncRoles([$userData['role']]);
            }
        }
    }
}
