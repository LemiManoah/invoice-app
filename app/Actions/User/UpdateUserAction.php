<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class UpdateUserAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(User $user, array $attributes): User
    {
        return DB::transaction(function () use ($user, $attributes): User {
            $roles = $attributes['roles'] ?? [];
            $isActive = (bool) ($attributes['is_active'] ?? false);

            if (Auth::id() === $user->id && ! $isActive) {
                throw ValidationException::withMessages([
                    'is_active' => 'You cannot deactivate your own account.',
                ]);
            }

            if (Auth::id() === $user->id && $user->hasRole('Admin') && ! in_array('Admin', $roles, true)) {
                throw ValidationException::withMessages([
                    'roles' => 'You cannot remove your own Admin role.',
                ]);
            }

            $oldValues = $user->load('roles')->toArray();

            $payload = [
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'phone' => $attributes['phone'] ?? null,
                'is_active' => $isActive,
                'theme_preference' => $attributes['theme_preference'] ?? $user->theme_preference,
            ];

            if (! empty($attributes['password'])) {
                $payload['password'] = $attributes['password'];
            }

            $user->update($payload);
            $user->syncRoles($roles);
            $user->load('roles');

            $this->createAuditLog->handle('user.updated', $user, $oldValues, $user->toArray());

            return $user;
        });
    }
}
