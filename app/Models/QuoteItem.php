<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\QuoteItem
 *
 * @property int $id
 * @property int $quote_id
 * @property int|null $product_id
 * @property string|null $product_name
 * @property int $quantity
 * @property float $price
 * @property float $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 *
 * @method static \Illuminate\Database\Eloquent\Builder|QuoteItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuoteItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuoteItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuoteItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuoteItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuoteItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuoteItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuoteItem whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuoteItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuoteItem whereQuoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuoteItem whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuoteItem whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class QuoteItem extends Model
{
    use HasFactory;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_id' => 'required',
        'quantity' => 'required',
        'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $messages = [
        'product_id.required' => 'The product field is required',
    ];

    protected $table = 'quote_items';

    public $fillable = [
        'quote_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'total',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'quote_id' => 'integer',
        'product_id' => 'integer',
        'product_name' => 'string',
        'quantity' => 'double',
        'price' => 'double',
        'total' => 'double',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
