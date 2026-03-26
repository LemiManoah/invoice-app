<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Measurement;
use App\Models\User;

final readonly class MeasurementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('measurements.view');
    }

    public function view(User $user, Measurement $measurement): bool
    {
        return $user->can('measurements.view');
    }

    public function create(User $user): bool
    {
        return $user->can('measurements.create');
    }

    public function update(User $user, Measurement $measurement): bool
    {
        return $user->can('measurements.update');
    }

    public function delete(User $user, Measurement $measurement): bool
    {
        return $user->can('measurements.delete');
    }
}
