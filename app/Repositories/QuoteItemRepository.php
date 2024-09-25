<?php

namespace App\Repositories;

use App\Models\Quote;
use App\Models\QuoteItem;

/**
 * Class QuoteItemRepository
 *
 * @version February 24, 2020, 5:57 am UTC
 */
class QuoteItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'quantity',
        'price',
        'tax',
        'total',
    ];

    /**
     * Return searchable fields
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QuoteItem::class;
    }

    public function updateQuoteItem(array $quoteItemInput, $quoteId)
    {
        /** @var Quote $quote */
        $quote = Quote::find($quoteId);
        $quoteItemIds = [];

        foreach ($quoteItemInput as $key => $data) {
            if (isset($data['id']) && ! empty($data['id'])) {
                $quoteItemIds[] = $data['id'];
                $this->update($data, $data['id']);
            } else {
                /** @var QuoteItem $quoteItem */
                $quoteItem = new QuoteItem($data);
                $quoteItem = $quote->quoteItems()->save($quoteItem);
                $quoteItemIds[] = $quoteItem->id;
            }
        }

        if (! (isset($quoteItemIds) && count($quoteItemIds))) {
            return;
        }

        QuoteItem::whereNotIn('id', $quoteItemIds)->whereQuoteId($quote->id)->delete();
    }
}
