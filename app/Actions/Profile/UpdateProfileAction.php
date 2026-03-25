<?php

namespace App\Actions\Profile;

use App\Models\User;

class UpdateProfileAction
{
    public function __invoke(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();

        return $user;
    }
}
