<?php

declare(strict_types=1);

use App\Actions\Report\ComputeSalesReportAction;
use App\Models\Invoice;
use App\Support\CurrencyManager;

beforeEach(function () {
    $this->currencies = seedBaselineCurrencies();
    $this->admin = adminActor();
    $this->actingAs($this->admin);
});

describe('ComputeSalesReportAction', function () {
    it('excludes draft and cancelled invoices from totals', function () {
        Invoice::factory()->draft()->create(['currency_id' => $this->currencies['ugx']->id, 'invoice_date' => now()->toDateString(), 'total_amount' => 1000]);
        Invoice::factory()->cancelled()->create(['currency_id' => $this->currencies['ugx']->id, 'invoice_date' => now()->toDateString(), 'total_amount' => 2000]);
        Invoice::factory()->issued()->create(['currency_id' => $this->currencies['ugx']->id, 'invoice_date' => now()->toDateString(), 'total_amount' => 3000, 'amount_paid' => 0, 'balance_due' => 3000]);

        $report = app(ComputeSalesReportAction::class)->handle(now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString());

        expect($report['summary']['invoice_count'])->toBe(1)
            ->and((float) $report['summary']['total_invoiced'])->toBe(3000.0);
    });

    it('converts amounts into the default currency when invoices span multiple currencies', function () {
        Invoice::factory()->issued()->create([
            'currency_id' => $this->currencies['ugx']->id,
            'invoice_date' => now()->toDateString(),
            'total_amount' => 3800,
            'amount_paid' => 0,
            'balance_due' => 3800,
        ]);
        // $10 USD invoice. With USD exchange_rate 3800, convertValue(10, USD) with default UGX (rate 1) = 10 * (3800/1) = 38000 UGX
        Invoice::factory()->issued()->create([
            'currency_id' => $this->currencies['usd']->id,
            'invoice_date' => now()->toDateString(),
            'total_amount' => 10,
            'amount_paid' => 0,
            'balance_due' => 10,
        ]);

        $report = app(ComputeSalesReportAction::class)->handle(now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString());

        expect((float) $report['summary']['total_invoiced'])->toBe(3800.0 + 38000.0);
    });

    /**
     * Business rule the user called out: "changing the currency on an invoice alters
     * the reports that come from invoices" — verifying that switching an invoice's
     * currency moves its contribution in the report.
     */
    it('reflects a currency change on an invoice in the aggregated sales report', function () {
        $invoice = Invoice::factory()->issued()->create([
            'currency_id' => $this->currencies['ugx']->id,
            'invoice_date' => now()->toDateString(),
            'total_amount' => 100,
            'amount_paid' => 0,
            'balance_due' => 100,
        ]);

        $before = app(ComputeSalesReportAction::class)->handle(now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString());
        expect((float) $before['summary']['total_invoiced'])->toBe(100.0);

        // Change invoice currency from UGX (rate 1) to USD (rate 3800). Same nominal amount
        // should now convert to 100 * 3800 = 380000 in the default UGX report.
        $invoice->update(['currency_id' => $this->currencies['usd']->id]);

        $after = app(ComputeSalesReportAction::class)->handle(now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString());
        expect((float) $after['summary']['total_invoiced'])->toBe(380000.0)
            ->and($after['summary']['total_invoiced'])->not->toBe($before['summary']['total_invoiced']);
    });
});

describe('CurrencyManager', function () {
    it('falls back to UGX when no currency exists', function () {
        \App\Models\Currency::query()->delete();
        $manager = new CurrencyManager;
        expect($manager->current()->code)->toBe('UGX');
    });

    it('uses the default currency when available', function () {
        $manager = new CurrencyManager;
        expect($manager->current()->code)->toBe('UGX')
            ->and($manager->current()->is_default)->toBeTrue();
    });

    it('converts using the source/target exchange_rate ratio', function () {
        $manager = new CurrencyManager;
        // 1 USD (rate 3800) to UGX (rate 1) → 3800 UGX
        $value = $manager->convertValue(1, $this->currencies['usd'], $this->currencies['ugx']);
        expect($value)->toBe(3800.0);

        // 100 UGX to USD → 100 * (1/3800)
        $value = $manager->convertValue(100, $this->currencies['ugx'], $this->currencies['usd']);
        expect(round($value, 6))->toBe(round(100 / 3800, 6));
    });

    it('returns 0 for null or empty input', function () {
        $manager = new CurrencyManager;
        expect($manager->convertValue(null))->toBe(0.0)
            ->and($manager->convertValue(''))->toBe(0.0);
    });

    it('formats values using the currency decimal_places', function () {
        $manager = new CurrencyManager;
        expect($manager->formatValue(1234.5, null, $this->currencies['ugx']))->toBe('UGX 1,235')
            ->and($manager->formatValue(1234.5, null, $this->currencies['usd']))->toBe('$ 1,234.50');
    });
});
