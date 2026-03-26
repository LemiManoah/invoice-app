<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;

final readonly class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('payments.view');
    }

    public function view(User $user, Payment $payment): bool
    {
        return $user->can('payments.view');
    }

    public function create(User $user, Invoice $invoice): bool
    {
        return $user->can('payments.create') && $invoice->canAcceptPayments();
    }

    public function void(User $user, Payment $payment): bool
    {
        return $user->can('payments.void') && ! $payment->isVoided();
    }
}
