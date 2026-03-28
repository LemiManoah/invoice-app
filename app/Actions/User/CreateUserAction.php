<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class CreateUserAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): User
    {
        return DB::transaction(function () use ($attributes): User {
            $roles = $attributes['roles'] ?? [];

            $user = User::query()->create([
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'phone' => $attributes['phone'] ?? null,
                'password' => $attributes['password'],
                'is_active' => (bool) ($attributes['is_active'] ?? true),
                'theme_preference' => $attributes['theme_preference'] ?? 'system',
            ]);

            $user->syncRoles($roles);
            $user->load('roles');

            $this->createAuditLog->handle('user.created', $user, null, $user->toArray());

            return $user;
        });
    }
}
