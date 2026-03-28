<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\AuditLog;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Measurement;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Receipt;
use App\Models\User;
use App\Policies\AuditLogPolicy;
use App\Policies\CurrencyPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\InvoicePolicy;
use App\Policies\MeasurementPolicy;
use App\Policies\OrderPolicy;
use App\Policies\PaymentMethodPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\ReceiptPolicy;
use App\Policies\UserPolicy;
use App\Support\CurrencyManager;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Customer::class => CustomerPolicy::class,
        Currency::class => CurrencyPolicy::class,
        Invoice::class => InvoicePolicy::class,
        Payment::class => PaymentPolicy::class,
        PaymentMethod::class => PaymentMethodPolicy::class,
        Receipt::class => ReceiptPolicy::class,
        Expense::class => ExpensePolicy::class,
        Order::class => OrderPolicy::class,
        Measurement::class => MeasurementPolicy::class,
        AuditLog::class => AuditLogPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view): void {
            $currencyManager = app(CurrencyManager::class);

            $view->with('currencyFormatter', $currencyManager);
            $view->with('activeCurrency', $currencyManager->current());
            $view->with('activeCurrencyConfig', $currencyManager->javascriptConfig());
        });
    }
}
