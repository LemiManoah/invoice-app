<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

final readonly class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('invoices.view');
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $user->can('invoices.view');
    }

    public function create(User $user): bool
    {
        return $user->can('invoices.create');
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $user->can('invoices.update') && $invoice->status === 'draft';
    }

    public function issue(User $user, Invoice $invoice): bool
    {
        return $user->can('invoices.issue') && $invoice->status === 'draft';
    }

    public function cancel(User $user, Invoice $invoice): bool
    {
        return $user->can('invoices.cancel') && $invoice->canBeCancelled();
    }

    public function print(User $user, Invoice $invoice): bool
    {
        return $user->can('invoices.print');
    }
}
