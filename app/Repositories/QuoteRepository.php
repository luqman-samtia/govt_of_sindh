<?php

namespace App\Repositories;

use App\Mail\QuoteCreateClientMail;
use App\Models\Client;
use App\Models\InvoiceSetting;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Setting;
use App\Models\TenantWiseClient;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stancl\Tenancy\Database\TenantScope;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class QuoteRepository
 */
class QuoteRepository extends BaseRepository
{
    /**
     * @var string[]
     */
    public $fieldSearchable = [

    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Quote::class;
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

    public function getQuoteItemList(array $quote = [])
    {
        $quoteItems = [];

        if (!empty($quote[0]->id)) {
            $quoteItems = QuoteItem::when($quote, function ($q) use ($quote) {
                $q->whereQuoteId($quote[0]->id);
            })->whereNotNull('product_name')->toBase()->pluck('product_name', 'product_name')->toArray();
        }

        return $quoteItems;
    }

    public function getSyncList(array $quote = []): array
    {
        $data['products'] = $this->getProductNameList();
        if (! empty($quote)) {
            $data['productItem'] = $this->getQuoteItemList($quote);
            $data['products'] = $data['products'] + $data['productItem'];
        }
        $data['associateProducts'] = $this->getAssociateProductList($quote);
        $clientWiseTenantIds = TenantWiseClient::whereTenantId(getLogInUser()->tenant_id)->toBase()->pluck('user_id')->toArray();
        $data['clients'] = \App\Models\User::whereIn('id',
            $clientWiseTenantIds)->withoutGlobalScope(new TenantScope())->get()->pluck('full_name', 'id')->toArray();
        $data['discount_type'] = Quote::DISCOUNT_TYPE;
        $quoteStatusArr = Arr::only(Quote::STATUS_ARR, Quote::DRAFT);
        $quoteRecurringArr = Quote::RECURRING_ARR;
        $data['statusArr'] = $quoteStatusArr;
        $data['recurringArr'] = $quoteRecurringArr;
        $data['template'] = InvoiceSetting::toBase()->pluck('template_name', 'id')->toArray();

        return $data;
    }

    public function getAssociateProductList(array $quote = []): array
    {
        $result = $this->getProductNameList();
        if (! empty($quote)) {
            $quoteItem = $this->getQuoteItemList($quote);
            $result += $quoteItem;
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

    public function saveQuote(array $input): Quote
    {
        try {
            DB::beginTransaction();
            $input['final_amount'] = $input['amount'];
            if ($input['final_amount'] == 'NaN') {
                $input['final_amount'] = 0;
            }
            $quoteItemInputArray = Arr::only($input, ['product_id', 'quantity', 'price']);
            $quoteExist = Quote::where('quote_id', $input['quote_id'])->exists();
            $quoteItemInput = $this->prepareInputForQuoteItem($quoteItemInputArray);
            $total = [];
            foreach ($quoteItemInput as $value) {
                $total[] = $value['price'] * $value['quantity'];
            }
            if (! empty($input['discount'])) {
                if (array_sum($total) <= $input['discount']) {
                    throw new UnprocessableEntityHttpException('Discount amount should not be greater than sub total.');
                }
            }

            if ($quoteExist) {
                throw new UnprocessableEntityHttpException('Quote id already exist');
            }

            /** @var Quote $quote */
            $clientUser = Client::whereUserId($input['client_id'])->withoutGlobalScope(new TenantScope())->first();
            $input['client_id'] = $clientUser->id;
            $input = Arr::only($input, [
                'client_id', 'quote_id', 'quote_date', 'due_date', 'discount_type', 'discount', 'final_amount',
                'note', 'term', 'template_id', 'status', 'tenant_id',
            ]);
            $quote = Quote::create($input);
            $totalAmount = 0;
            $products = Product::toBase()->pluck('id')->toArray();
            foreach ($quoteItemInput as $data) {
                $validator = Validator::make($data, QuoteItem::$rules, QuoteItem::$messages);

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
                $quoteItem = new QuoteItem($data);

                $quoteItem = $quote->quoteItems()->save($quoteItem);
            }

            $quote->amount = $totalAmount;
            $quote->save();

            DB::commit();
            if (getSettingValue('mail_notification')) {
                $user = \App\Models\User::whereId($clientUser->user_id)->withoutGlobalScope(new TenantScope())->first();
                $input['quoteData'] = $quote;
                $input['clientData'] = $user;
                Mail::to($input['clientData']['email'])->send(new QuoteCreateClientMail($input));
            }

            return $quote;
        } catch (Exception $exception) {
            throw new UnprocessableEntityHttpException($exception->getMessage());
        }
    }

    public function updateQuote($quoteId, $input)
    {
        try {
            DB::beginTransaction();
            if ($input['discount_type'] == 0) {
                $input['discount'] = 0;
            }
            $input['final_amount'] = $input['amount'];
            $quoteItemInputArr = Arr::only($input, ['product_id', 'quantity', 'price', 'id']);
            $quoteItemInput = $this->prepareInputForQuoteItem($quoteItemInputArr);
            $total = [];
            foreach ($quoteItemInput as $key => $value) {
                $total[] = $value['price'] * $value['quantity'];
            }
            if (! empty($input['discount'])) {
                if (array_sum($total) <= $input['discount']) {
                    throw new UnprocessableEntityHttpException('Discount amount should not be greater than sub total.');
                }
            }

            /** @var Quote $quote */
            $input['client_id'] = Client::whereUserId($input['client_id'])->withoutGlobalScope(new TenantScope())->first()->id;
            $quote = $this->update(Arr::only($input,
                [
                    'client_id', 'quote_date', 'due_date', 'discount_type', 'discount', 'final_amount', 'note',
                    'term', 'template_id', 'price',
                    'status',
                ]), $quoteId);
            $totalAmount = 0;

            foreach ($quoteItemInput as $key => $data) {
                $validator = Validator::make($data, QuoteItem::$rules, QuoteItem::$messages);
                if ($validator->fails()) {
                    throw new UnprocessableEntityHttpException($validator->errors()->first());
                }
                $data['product_name'] = is_numeric($data['product_id']);
                if ($data['product_name'] == true) {
                    $data['product_name'] = null;
                } else {
                    $data['product_name'] = $data['product_id'];
                    $data['product_id'] = null;
                }
                $data['amount'] = $data['price'] * $data['quantity'];
                $data['total'] = $data['amount'];
                $totalAmount += $data['amount'];
                $quoteItemInput[$key] = $data;
            }

            /** @var QuoteItemRepository $quoteItemRepo */
            $quoteItemRepo = app(QuoteItemRepository::class);
            $quoteItemRepo->updateQuoteItem($quoteItemInput, $quote->id);
            $quote->amount = $totalAmount;
            $quote->save();
            DB::commit();

            return $quote;
        } catch (Exception $exception) {
            throw new UnprocessableEntityHttpException($exception->getMessage());
        }
    }

    public function getPdfData($quote): array
    {
        $data = [];
        $data['quote'] = $quote;
        $data['client'] = $quote->client;
        $quoteItems = $quote->quoteItems;
        $data['quote_template_color'] = $quote->quoteTemplate->template_color;
        $data['setting'] = Setting::toBase()->pluck('value', 'key')->toArray();

        return $data;
    }

    public function getDefaultTemplate($quote): mixed
    {
        $data['quote_template_name'] = $quote->quoteTemplate->key;

        return $data['quote_template_name'];
    }

    public function prepareInputForQuoteItem(array $input): array
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

    public function saveNotification(array $input, $quote = null): void
    {
        $userId = $input['client_id'];
        $input['quote_id'] = $quote->quote_id;
        $title = 'New Quote created #'.$input['quote_id'].'.';
        if ($input['status'] != Quote::DRAFT) {
            addNotification([
                Notification::NOTIFICATION_TYPE['Quote Created'],
                $userId,
                $title,
            ]);
        }
    }

    public function updateNotification($quote, $input, array $changes = [])
    {
        $quote->load('client.user');
        $userId = $quote->client->user_id;
        $title = 'Your Quote #'.$quote->quote_id.' was updated.';
        if ($input['status'] != Quote::DRAFT) {
            if (isset($changes['status'])) {
                $title = 'Status of your Quote #'.$quote->quote_id.' was updated.';
            }
            addNotification([
                Notification::NOTIFICATION_TYPE['Quote Updated'],
                $userId,
                $title,
            ]);
        }
    }

    public function getQuoteData($quote): array
    {
        $data = [];

        $quote = Quote::with([
            'client' => function ($query) {
                $query->select(['id', 'user_id', 'address']);
                $query->with([
                    'user' => function ($query) {
                        $query->select(['first_name', 'last_name', 'email', 'id']);
                    },
                ]);
            },
            'quoteItems',
        ])->whereId($quote->id)->first();

        $data['quote'] = $quote;
        $quoteItems = $quote->quoteItems;

        return $data;
    }

    public function prepareEditFormData($quote): array
    {
        /** @var Quote $quote */
        $quote = Quote::with([
            'quoteItems',
            'client',
        ])->whereId($quote->id)->firstOrFail();

        $data = $this->getSyncList([$quote]);
        $data['client_id'] = $quote->client->user_id;
        $data['$quote'] = $quote;

        $quoteItems = $quote->quoteItems;

        return $data;
    }
}
