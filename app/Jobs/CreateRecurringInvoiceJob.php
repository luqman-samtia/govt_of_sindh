<?php

namespace App\Jobs;

use App\Mail\InvoiceCreateClientMail;
use App\Models\Invoice;
use App\Models\InvoiceItemTax;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreateRecurringInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $recurringInvoice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Invoice $recurringInvoice)
    {
        $this->recurringInvoice = $recurringInvoice;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();

            $invoiceDueDate = Carbon::parse($this->recurringInvoice->due_date);
            $invoiceDate = Carbon::parse($this->recurringInvoice->invoice_date);
            $dueDateDifference = $invoiceDueDate->diffInDays($invoiceDate);

            Log::info('Due date difference calculated');

            // Clone recurring invoice record
            $invoiceRecord = $this->recurringInvoice->replicate();
            $invoiceRecord->invoice_id .= date('s');
            $invoiceRecord->parent_id = $this->recurringInvoice->id;
            $invoiceRecord->recurring_status = false;
            $invoiceRecord->status = $this->setStatus($this->recurringInvoice);
            $invoiceRecord->last_recurring_on = null;
            $invoiceRecord->invoice_date = Carbon::now()->toDateString();
            $invoiceRecord->due_date = Carbon::now()->addDays($dueDateDifference)->toDateString();
            $invoiceRecord->save();

            Log::info('Replicating completed and new invoice id is: '.$invoiceRecord->id);

            // Save invoice item to newly replicated invoice record
            foreach ($this->recurringInvoice->invoiceItems as $invoiceItem) {
                $replicateInvoiceItem = $invoiceItem->replicate();
                $replicateInvoiceItem->invoice_id = $invoiceRecord->id;
                $replicateInvoiceItem->save();
                $invoiceRecord->invoiceItems()->save($replicateInvoiceItem);

                Log::info('Invoice item successfully saved');

                // Save invoice item tax
                foreach ($invoiceItem->invoiceItemTax as $invoiceItemTax) {
                    InvoiceItemTax::create([
                        'invoice_item_id' => $replicateInvoiceItem->id,
                        'tax_id' => $invoiceItemTax->tax_id,
                        'tax' => $invoiceItemTax->tax,
                    ]);
                }
            }

            // Send mail to customer
            if ($this->recurringInvoice->status !== Invoice::DRAFT) {
                $input['invoiceData'] = $invoiceRecord;
                $input['clientData'] = $invoiceRecord->client->user->toArray();
                if (getSettingValue('mail_notification')) {
                    Mail::to($invoiceRecord->client->user->email)->send(new InvoiceCreateClientMail($input));
                }
            }

            // Update last synced on main invoice record
            $this->recurringInvoice->update([
                'last_recurring_on' => Carbon::now()->toDateString(),
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
        }
    }

    protected function setStatus(Invoice $invoice): int
    {
        $status = $invoice->status;
        if ($status === Invoice::DRAFT) {
            return $status;
        }

        return Invoice::UNPAID;
    }
}
