<?php

namespace App\Console\Commands;

use App\Jobs\CreateRecurringInvoiceJob;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RecurringInvoiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'in:create-recurring-invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Recurring Invoice to Customers';

    /**
     * Execute the console command.
     */
    public function handle(): bool
    {
        $recurringInvoices = Invoice::whereRecurringStatus(true)
            ->where('status', '!=', Invoice::DRAFT)
            ->with(['invoiceItems.invoiceItemTax'])
            ->get();

        foreach ($recurringInvoices as $recurringInvoice) {
            $lastRecurredDate = $recurringInvoice->invoice_date;
            if (! empty($recurringInvoice->last_recurring_on)) {
                $lastRecurredDate = $recurringInvoice->last_recurring_on;
            }
            $lastRecurredOn = Carbon::parse($lastRecurredDate)
                ->addDays($recurringInvoice->recurring_cycle)
                ->startOfDay();

            if ($lastRecurredOn->eq(Carbon::now()->startOfDay())) {
                Log::info('Start replicating Invoice '.$recurringInvoice->id);

                CreateRecurringInvoiceJob::dispatch($recurringInvoice);
            }
        }

        return true;
    }
}
