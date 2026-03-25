<?php

namespace App\Actions\Appearance;

use App\Models\User;

class UpdateAppearanceAction
{
    public function __invoke(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }
}
