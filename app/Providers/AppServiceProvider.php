<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Measurement;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\User;
use App\Policies\AuditLogPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\InvoicePolicy;
use App\Policies\MeasurementPolicy;
use App\Policies\OrderPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\ReceiptPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::before(static function (User $user, string $ability): bool|null {
            if ($user->hasRole('Admin')) {
                return true;
            }

            return null;
        });

        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(Receipt::class, ReceiptPolicy::class);
        Gate::policy(Expense::class, ExpensePolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Measurement::class, MeasurementPolicy::class);
        Gate::policy(AuditLog::class, AuditLogPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
