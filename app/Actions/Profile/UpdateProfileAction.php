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
        if (array_key_exists('email', $data) && $data['email'] !== $user->email) {
            $user->forceFill(['email_verified_at' => null]);
        }

        $user->fill($data);
        $user->save();

        return $user;
    }
}
