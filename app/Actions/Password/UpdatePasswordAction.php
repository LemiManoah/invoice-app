<?php

namespace App\Actions\Password;

use Illuminate\Support\Facades\Hash;

class UpdatePasswordAction
{
    public function __invoke($user, string $newPassword): void
    {
        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
