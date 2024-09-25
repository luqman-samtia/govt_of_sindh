<?php

namespace App\Repositories;

use App\Mail\InvoiceCreateClientMail;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceItemTax;
use App\Models\InvoiceSetting;
use App\Models\Notification;
use App\Models\PaymentQrCode;
use App\Models\Product;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Tax;
use App\Models\TenantWiseClient;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stancl\Tenancy\Database\TenantScope;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class InvoiceRepository
 */
class InvoiceRepository extends BaseRepository
{
    /**
     * @var string[]
     */
    public $fieldSearchable = [];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Invoice::class;
    }

    public function getProductNameList(): mixed
    {
        /** @var Product $product */
        static $product;

        if (! isset($product) && empty($product)) {
            $product = Product::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        }

        return $product;
    }

    public function getTaxNameList(): mixed
    {
        /** @var Tax $tax */
        static $tax;

        if (! isset($tax) && empty($tax)) {
            $tax = Tax::get();
        }

        return $tax;
    }

    public function getInvoiceItemList(array $invoice = []): mixed
    {
        /** @var InvoiceItem $invoiceItems */
        static $invoiceItems;

        if (! isset($invoiceItems) && empty($invoiceItems)) {
            $invoiceItems = InvoiceItem::when($invoice, function ($q) use ($invoice) {
                $q->whereInvoiceId($invoice[0]->id);
            })->whereNotNull('product_name')->pluck(
                'product_name',
                'product_name'
            )->toArray();
        }

        return $invoiceItems;
    }

    public function getSyncList(array $invoice = []): array
    {
        $data['products'] = $this->getProductNameList();
        if (! empty($invoice)) {
            $data['productItem'] = $this->getInvoiceItemList($invoice);
            $data['products'] += $data['productItem'];
        }
        $data['associateProducts'] = $this->getAssociateProductList($invoice);

        $clientWiseTenantIds = TenantWiseClient::whereTenantId(getLogInUser()->tenant_id)->toBase()->pluck('user_id')->toArray();
        $data['clients'] = \App\Models\User::whereIn(
            'id',
            $clientWiseTenantIds
        )->withoutGlobalScope(new TenantScope())->get()->pluck('full_name', 'id')->toArray();

        $data['discount_type'] = Invoice::DISCOUNT_TYPE;
        $invoiceStatusArr = Invoice::STATUS_ARR;
        unset($invoiceStatusArr[Invoice::STATUS_ALL]);
        $invoiceRecurringArr = Invoice::RECURRING_ARR;
        $data['statusArr'] = $invoiceStatusArr;
        $data['recurringArr'] = $invoiceRecurringArr;
        $data['taxes'] = $this->getTaxNameList();
        $data['defaultTax'] = getDefaultTax();
        $data['associateTaxes'] = $this->getAssociateTaxList();
        $data['template'] = InvoiceSetting::toBase()->pluck('template_name', 'id')->toArray();
        $data['paymentQrCodes'] = PaymentQrCode::where('user_id', Auth::user()->id)->pluck('title','id') ?? null;
        $data['defaultPaymentQRCode'] = PaymentQrCode::whereIsDefault(true)->value('id') ?? null;

        return $data;
    }

    public function getAssociateProductList(array $invoice = []): array
    {
        $result = $this->getProductNameList();
        if (! empty($invoice)) {
            $invoiceItem = $this->getInvoiceItemList($invoice);
            $result += $invoiceItem;
        }
        $products = [];
        foreach ($result as $key => $item) {
            $products[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $products;
    }

    public function getAssociateTaxList(): array
    {
        $result = $this->getTaxNameList();
        $taxes = [];
        foreach ($result as $item) {
            $taxes[] = [
                'id' => $item->id,
                'name' => $item->name,
                'value' => $item->value,
                'is_default' => $item->is_default,
            ];
        }

        return $taxes;
    }

    public function saveInvoice(array $input): Invoice
    {
        try {
            DB::beginTransaction();
            $input['tax_id'] = json_decode($input['tax_id']);
            $input['tax'] = json_decode($input['tax']);
            $input['recurring_status'] = isset($input['recurring_status']);
            if (! empty(getSettingValue('invoice_no_prefix'))) {
                $input['invoice_id'] = getSettingValue('invoice_no_prefix').'-'.$input['invoice_id'];
            }
            if (! empty(getSettingValue('invoice_no_suffix'))) {
                $input['invoice_id'] .= '-'.getSettingValue('invoice_no_suffix');
            }

            if (empty($input['final_amount'])) {
                $input['final_amount'] = 0;
            }

            if (! empty($input['recurring_status']) && empty($input['recurring_cycle'])) {
                throw new UnprocessableEntityHttpException('Please enter the value in Recurring Cycle.');
            }

            $invoiceItemInputArray = Arr::only($input, ['product_id', 'quantity', 'price', 'tax', 'tax_id']);
            $invoiceExist = Invoice::where('invoice_id', $input['invoice_id'])->exists();
            $invoiceItemInput = $this->prepareInputForInvoiceItem($invoiceItemInputArray);
            $total = [];
            foreach ($invoiceItemInput as $key => $value) {
                $total[] = $value['price'] * $value['quantity'];
            }
            if (! empty($input['discount'])) {
                if (array_sum($total) <= $input['discount']) {
                    throw new UnprocessableEntityHttpException('Discount amount should not be greater than sub total.');
                }
            }

            if ($invoiceExist) {
                throw new UnprocessableEntityHttpException('Invoice id already exist');
            }

            $inputInvoiceTaxes = isset($input['taxes']) ? $input['taxes'] : [];
            $input = Arr::only($input, [
                'invoice_id', 'invoice_date', 'due_date', 'discount_type', 'discount', 'amount', 'final_amount',
                'note', 'term', 'template_id', 'payment_qr_code_id', 'status', 'tax_id', 'tax', 'tenant_id', 'client_id', 'currency_id',
                'recurring_status', 'recurring_cycle',
            ]);

            /** @var Client $clientUser */
            $clientUser = Client::whereUserId($input['client_id'])->withoutGlobalScope(new TenantScope())->first();
            $input['client_id'] = $clientUser->id;

            /** @var Invoice $invoice */
            $invoice = Invoice::create($input);

            if (count($inputInvoiceTaxes) > 0) {
                $invoice->invoiceTaxes()->sync($inputInvoiceTaxes);
            }

            $totalAmount = 0;
            $products = Product::toBase()->pluck('id')->toArray();
            foreach ($invoiceItemInput as $key => $data) {
                $validator = Validator::make($data, InvoiceItem::$rules);

                if ($validator->fails()) {
                    throw new UnprocessableEntityHttpException($validator->errors()->first());
                }
                $data['product_name'] = is_numeric($data['product_id']);
                if (in_array($data['product_id'], $products)) {
                    $data['product_name'] = null;
                } else {
                    $data['product_name'] = $data['product_id'];
                    $data['product_id'] = null;
                }
                $data['amount'] = $data['price'] * $data['quantity'];

                $data['total'] = $data['amount'];
                $totalAmount += $data['amount'];

                /** @var InvoiceItem Items $invoiceItem */
                $invoiceItem = new InvoiceItem($data);
                $invoiceItems = $invoice->invoiceItems()->save($invoiceItem);

                $invoiceItemTaxIds = ($input['tax_id'][$key] != 0) ? $input['tax_id'][$key] : $input['tax_id'][$key] = [0 => 0];
                $invoiceItemTaxes = ($input['tax'][$key] != 0) ? $input['tax'][$key] : $input['tax'][$key] = [0 => null];

                foreach ($invoiceItemTaxes as $index => $tax) {
                    InvoiceItemTax::create([
                        'invoice_item_id' => $invoiceItems->id,
                        'tax_id' => $invoiceItemTaxIds[$index],
                        'tax' => $tax,
                    ]);
                }
            }

            DB::commit();
            if ($invoice->status != Invoice::DRAFT) {
                $input['invoiceData'] = $invoice;
                $input['clientData'] = $invoice->client->user;

                if (getSettingValue('mail_notification')) {
                    Mail::to($input['clientData']['email'])->send(new InvoiceCreateClientMail($input));
                }
            }

            return $invoice;
        } catch (Exception $exception) {
            throw new UnprocessableEntityHttpException($exception->getMessage());
        }
    }

    public function prepareInputForInvoiceItem(array $input): array
    {
        $items = [];
        foreach ($input as $key => $data) {
            foreach ($data as $index => $value) {
                $items[$index][$key] = $value;
                if (! (isset($items[$index]['price']) && $key == 'price')) {
                    continue;
                }
                $items[$index]['price'] = removeCommaFromNumbers($items[$index]['price']);
            }
        }

        return $items;
    }

    public function updateInvoice($invoiceId, $input)
    {
        try {
            DB::beginTransaction();
            $input['tax_id'] = json_decode($input['tax_id']);
            $input['tax'] = json_decode($input['tax']);
            $input['recurring_status'] = isset($input['recurring_status']);
            if ($input['discount_type'] == 0) {
                $input['discount'] = 0;
            }

            $inputInvoiceTaxes = isset($input['taxes']) ? $input['taxes'] : [];
            $invoiceItemInputArr = Arr::only($input, ['product_id', 'quantity', 'price', 'tax', 'tax_id', 'id']);
            $invoiceItemInput = $this->prepareInputForInvoiceItem($invoiceItemInputArr);
            $total = [];
            foreach ($invoiceItemInput as $key => $value) {
                $total[] = $value['price'] * $value['quantity'];
            }
            if (! empty($input['discount'])) {
                if (array_sum($total) <= $input['discount']) {
                    throw new UnprocessableEntityHttpException('Discount amount should not be greater than sub total.');
                }
            }

            if (! empty($input['recurring_status']) && empty($input['recurring_cycle'])) {
                throw new UnprocessableEntityHttpException('Please enter the value in Recurring Cycle.');
            }

            /** @var Invoice $invoice */
            $clientUser = Client::whereUserId($input['client_id'])->withoutGlobalScope(new TenantScope())->first();
            $input['client_id'] = $clientUser->id;
            $invoice = $this->update(Arr::only(
                $input,
                [
                    'invoice_date', 'due_date', 'discount_type', 'discount', 'amount', 'final_amount', 'note',
                    'term', 'template_id', 'payment_qr_code_id', 'recurring_status', 'recurring_cycle',
                    'status', 'tax_id', 'tax', 'tenant_id', 'client_id', 'currency_id',
                ]
            ), $invoiceId);

            $invoice->invoiceTaxes()->detach();
            if (count($inputInvoiceTaxes) > 0) {
                $invoice->invoiceTaxes()->attach($inputInvoiceTaxes);
            }

            $totalAmount = 0;
            $products = Product::toBase()->pluck('id')->toArray();
            foreach ($invoiceItemInput as $key => $data) {
                $validator = Validator::make($data, InvoiceItem::$rules, [
                    'product_id.integer' => 'Please select a Product',
                ]);
                if ($validator->fails()) {
                    throw new UnprocessableEntityHttpException($validator->errors()->first());
                }
                $data['product_name'] = is_numeric($data['product_id']);
                if (in_array($data['product_id'], $products)) {
                    $data['product_name'] = null;
                } else {
                    $data['product_name'] = $data['product_id'];
                    $data['product_id'] = null;
                }

                $data['amount'] = $data['price'] * $data['quantity'];
                $data['total'] = $data['amount'];
                $totalAmount += $data['amount'];
                $invoiceItemInput[$key] = $data;
            }

            /** @var InvoiceItemRepository $invoiceItemRepo */
            $invoiceItemRepo = app(InvoiceItemRepository::class);
            $invoiceItemRepo->updateInvoiceItem($invoiceItemInput, $invoice->id);

            $changes = $invoice->getChanges();
            if (isset($changes['due_date']) && $invoice->status == Invoice::OVERDUE) {
                $invoice->update([
                    'status' => Invoice::UNPAID,
                ]);
            }

            if ($input['invoiceStatus'] === '1') {
                if (count($changes) > 0) {
                    $this->updateNotification($invoice, $input, $changes);
                }
                if ($input['status'] == Invoice::DRAFT) {
                    $this->draftStatusUpdate($invoice);
                }
            }

            DB::commit();

            return $invoice;
        } catch (Exception $exception) {
            throw new UnprocessableEntityHttpException($exception->getMessage());
        }
    }

    public function getInvoiceData($invoice): array
    {
        $data = [];

        $invoice = Invoice::with([
            'client' => function ($query) {
                $query->select(['id', 'user_id', 'address']);
                $query->with([
                    'user' => function ($query) {
                        $query->select(['first_name', 'last_name', 'email', 'id']);
                    },
                ]);
            },
            'parentInvoice',
            'payments',
            'invoiceItems' => function ($query) {
                $query->with(['product', 'invoiceItemTax']);
            },
            'invoiceTaxes'
        ])->withCount('childInvoices')->whereId($invoice->id)->first();

        $data['invoice'] = $invoice;
        $invoiceItems = $invoice->invoiceItems;
        $data['totalTax'] = [];

        foreach ($invoiceItems as $keys => $item) {
            $totalTax = $item->invoiceItemTax->sum('tax');
            $data['totalTax'][] = $item['quantity'] * $item['price'] * $totalTax / 100;
        }

        $data['dueAmount'] = 0;
        $data['paid'] = 0;
        if ($invoice->status != \App\Models\Invoice::PAID) {
            foreach ($invoice->payments as $payment) {
                if ($payment->payment_mode == \App\Models\Payment::MANUAL && $payment->is_approved !== \App\Models\Payment::APPROVED) {
                    continue;
                }
                $data['paid'] += $payment->amount;
            }
        } else {
            $data['paid'] += $invoice->final_amount;
        }

        $data['dueAmount'] = $invoice->final_amount - $data['paid'];

        return $data;
    }

    public function prepareEditFormData($invoice): array
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::with([
            'invoiceItems' => function ($query) {
                $query->with(['invoiceItemTax']);
            },
            'client',
        ])->whereId($invoice->id)->firstOrFail();
        $paymentQrCodes = PaymentQrCode::where('user_id', Auth::user()->id)->pluck('title','id') ?? null;
        $data = $this->getSyncList([$invoice]);
        $data['client_id'] = $invoice->client->user_id;
        $data['invoice'] = $invoice;
        $data['paymentQrCodes'] = $paymentQrCodes;
        $invoiceItems = $invoice->invoiceItems;

        $data['selectedTaxes'] = [];
        foreach ($invoiceItems as $invoiceItem) {
            $invoiceItemTaxes = $invoiceItem->invoiceItemTax;
            foreach ($invoiceItemTaxes as $invoiceItemTax) {
                $data['selectedTaxes'][$invoiceItem->id][] = $invoiceItemTax->tax_id;
            }
        }

        return $data;
    }

    public function getDefaultTemplate($invoice)
    {
        $invoiceSetting = DB::table('invoice-settings')->where('id', $invoice['template_id'])->first();
        $data['invoice_template_name'] = $invoiceSetting->key;

        return $data['invoice_template_name'];
    }

    public function getPdfData($invoice): array
    {
        $data = [];
        $data['invoice'] = $invoice;
        $data['client'] = $invoice->client;
        $invoiceItems = $invoice->invoiceItems;
        $invoiceSetting = DB::table('invoice-settings')->where('id', $invoice['template_id'])->first();
        $data['invoice_template_color'] = $invoiceSetting->template_color;
        $data['totalTax'] = [];

        foreach ($invoiceItems as $keys => $item) {
            $totalTax = $item->invoiceItemTax->sum('tax');
            $data['totalTax'][] = $item['quantity'] * $item['price'] * $totalTax / 100;
        }

        if (! Auth::check()) {
            $data['setting'] = Setting::where('tenant_id', $invoice->tenant_id)->pluck('value', 'key')->toArray();
        } else {
            $user = Auth::user();
            if ($user->hasRole(Role::ROLE_CLIENT)) {
                $data['setting'] = Setting::where('tenant_id', getClientAdminTenantId())
                    ->pluck('value', 'key')->toArray();
            } elseif ($user->hasRole(Role::ROLE_ADMIN)) {
                $data['setting'] = Setting::where('tenant_id', $user->tenant_id)
                    ->pluck('value', 'key')->toArray();
            } else {
                $data['setting'] = Setting::pluck('value', 'key')->toArray();
            }
        }

        return $data;
    }

    public function saveNotification(array $input, $invoice = null)
    {
        $userId = $input['client_id'];
        $input['invoice_id'] = $invoice->invoice_id;
        $title = 'New invoice created #'.$input['invoice_id'].'.';
        if ($input['status'] != Invoice::DRAFT) {
            addNotification([
                Notification::NOTIFICATION_TYPE['Invoice Created'],
                $userId,
                $title,
            ]);
        }
    }

    public function updateNotification($invoice, $input, array $changes = [])
    {
        $invoice->load('client.user');
        $userId = $invoice->client->user_id;
        $title = 'Your invoice #'.$invoice->invoice_id.' was updated.';
        if ($input['status'] != Invoice::DRAFT) {
            if (isset($changes['status'])) {
                $title = 'Status of your invoice #'.$invoice->invoice_id.' was updated.';
            }
            addNotification([
                Notification::NOTIFICATION_TYPE['Invoice Updated'],
                $userId,
                $title,
            ]);
        }
    }

    public function draftStatusUpdate(Invoice $invoice): bool
    {
        $invoice->update([
            'status' => Invoice::UNPAID,
        ]);
        $invoice->load('client.user');
        $userId = $invoice->client->user_id;
        $title = 'Status of your invoice #'.$invoice->invoice_id.' was updated.';
        addNotification([
            Notification::NOTIFICATION_TYPE['Invoice Updated'],
            $userId,
            $title,
        ]);
        $input['invoiceData'] = $invoice->toArray();
        $input['clientData'] = $invoice->client->user;
        if (getSettingValue('mail_notification')) {
            Mail::to($invoice->client->user->email)->send(new InvoiceCreateClientMail($input));
        }

        return true;
    }
}
