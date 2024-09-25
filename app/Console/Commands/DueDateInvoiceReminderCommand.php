<?php

namespace App\Console\Commands;

use App\Mail\InvoicePaymentReminderMail;
use App\Models\Invoice;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DueDateInvoiceReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:due-date-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'due date invoice reminder command';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('Started due date invoice reminder command.');

        $invoices = Invoice::with('client.user')->whereStatus(Invoice::UNPAID)->get();
        $setting = Setting::where('key', 'due_invoice_days')->first();
        $dueInvoiceDays = ! empty($setting) ? $setting->value : null;
        $currentDate = Carbon::now()->format('Y-m-d');

        if (! empty($dueInvoiceDays)) {
            foreach ($invoices as $invoice) {
                $subtractDate = Carbon::parse($invoice->due_date)->subDays($dueInvoiceDays);
                if ($currentDate == $subtractDate->format('Y-m-d')) {
                    Mail::to($invoice->client->user->email)->send(new InvoicePaymentReminderMail($invoice));
                }
            }
        }

        Log::info('Ended due date invoice reminder command.');
    }
}
