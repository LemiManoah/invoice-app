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

        // Create Admin user
        $admin = User::firstOrCreate(
            ['email' => 'lemi.manoah@gmail.com'],
            [
                'name' => 'Lemi Admin',
                'phone' => '+254700000000',
                'password' => Hash::make('password'),
                'is_active' => true,
                'theme_preference' => 'light',
            ]
        );
        $admin->syncRoles([$adminRole]);
    }
}
