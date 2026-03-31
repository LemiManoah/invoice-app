<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;

final readonly class QuotationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('quotations.view');
    }

    public function view(User $user, Quotation $quotation): bool
    {
        return $user->can('quotations.view');
    }

    public function create(User $user): bool
    {
        return $user->can('quotations.create');
    }

    public function update(User $user, Quotation $quotation): bool
    {
        return $user->can('quotations.update') && in_array($quotation->status, ['draft', 'sent'], true);
    }

    public function send(User $user, Quotation $quotation): bool
    {
        return $user->can('quotations.send') && $quotation->canBeSent();
    }

    public function convert(User $user, Quotation $quotation): bool
    {
        return $user->can('quotations.convert') && $quotation->canBeConverted();
    }

    public function delete(User $user, Quotation $quotation): bool
    {
        return $user->can('quotations.delete') && $quotation->invoice_id === null;
    }

    public function print(User $user, Quotation $quotation): bool
    {
        return $user->can('quotations.print');
    }
}
