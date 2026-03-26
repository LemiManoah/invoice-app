<?php

declare(strict_types=1);

namespace App\Actions\Password;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final readonly class UpdatePasswordAction
{
    public function handle(User $user, string $newPassword): void
    {
        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
