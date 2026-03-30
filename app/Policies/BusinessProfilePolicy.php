<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\BusinessProfile;
use App\Models\User;

final readonly class BusinessProfilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('business-profile.view');
    }

    public function view(User $user, BusinessProfile $businessProfile): bool
    {
        return $user->can('business-profile.view');
    }

    public function create(User $user): bool
    {
        return $user->can('business-profile.create');
    }

    public function update(User $user, BusinessProfile $businessProfile): bool
    {
        return $user->can('business-profile.update');
    }

    public function delete(User $user, BusinessProfile $businessProfile): bool
    {
        return $user->can('business-profile.delete');
    }
}
