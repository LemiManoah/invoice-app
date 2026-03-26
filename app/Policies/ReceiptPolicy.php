<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Receipt;
use App\Models\User;

final readonly class ReceiptPolicy
{
    public function view(User $user, Receipt $receipt): bool
    {
        return $user->can('receipts.view');
    }

    public function print(User $user, Receipt $receipt): bool
    {
        return $user->can('receipts.view');
    }
}
