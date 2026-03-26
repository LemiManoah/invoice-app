<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final readonly class UserPolicy
{
    public function updateProfile(User $user, User $subject): bool
    {
        return $user->is($subject) && $user->can('settings.profile.update');
    }

    public function updatePassword(User $user, User $subject): bool
    {
        return $user->is($subject) && $user->can('settings.password.update');
    }

    public function updateAppearance(User $user, User $subject): bool
    {
        return $user->is($subject) && $user->can('settings.appearance.update');
    }

    public function delete(User $user, User $subject): bool
    {
        return $user->is($subject);
    }
}
