<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final readonly class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('users.view');
    }

    public function view(User $user, User $subject): bool
    {
        return $user->can('users.view');
    }

    public function create(User $user): bool
    {
        return $user->can('users.create');
    }

    public function update(User $user, ?User $subject = null): bool
    {
        return $user->can('users.update');
    }

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

    public function delete(User $user, ?User $subject = null): bool
    {
        return $user->can('users.delete') || ($subject && $user->is($subject));
    }
}
