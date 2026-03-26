<?php

declare(strict_types=1);

namespace App\Actions\Appearance;

use App\Models\User;

final readonly class UpdateAppearanceAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }
}
