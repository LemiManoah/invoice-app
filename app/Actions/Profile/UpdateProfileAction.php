<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Models\User;

final readonly class UpdateProfileAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();

        return $user;
    }
}
