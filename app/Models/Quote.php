<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

/**
 * App\Models\Quote
 *
 * @property int $id
 * @property string $quote_id
 * @property int $client_id
 * @property string|null $tenant_id
 * @property string $quote_date
 * @property string $due_date
 * @property float|null $amount
 * @property float|null $final_amount
 * @property int $discount_type
 * @property float $discount
 * @property string|null $note
 * @property string|null $term
 * @property int|null $template_id
 * @property int $recurring
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Client $client
 * @property-read string $status_label
 * @property-read Collection|QuoteItem[] $quoteItems
 * @property-read int|null $quote_items_count
 * @property-read InvoiceSetting|null $quoteTemplate
 * @property-read MultiTenant|null $tenant
 *
 * @method static Builder|Quote newModelQuery()
 * @method static Builder|Quote newQuery()
 * @method static Builder|Quote query()
 * @method static Builder|Quote whereAmount($value)
 * @method static Builder|Quote whereClientId($value)
 * @method static Builder|Quote whereCreatedAt($value)
 * @method static Builder|Quote whereDiscount($value)
 * @method static Builder|Quote whereDiscountType($value)
 * @method static Builder|Quote whereDueDate($value)
 * @method static Builder|Quote whereFinalAmount($value)
 * @method static Builder|Quote whereId($value)
 * @method static Builder|Quote whereNote($value)
 * @method static Builder|Quote whereQuoteDate($value)
 * @method static Builder|Quote whereQuoteId($value)
 * @method static Builder|Quote whereRecurring($value)
 * @method static Builder|Quote whereStatus($value)
 * @method static Builder|Quote whereTemplateId($value)
 * @method static Builder|Quote whereTenantId($value)
 * @method static Builder|Quote whereTerm($value)
 * @method static Builder|Quote whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Quote extends Model
{
    use HasFactory, BelongsToTenant, Multitenantable;

    public const SELECT_DISCOUNT_TYPE = 0;

    const FIXED = 1;

    const PERCENTAGE = 2;

    const DISCOUNT_TYPE = [
        self::SELECT_DISCOUNT_TYPE => 'Select Discount Type',
        self::FIXED => 'Fixed',
        self::PERCENTAGE => 'Percentage',
    ];

    const DRAFT = 0;

    const CONVERTED = 1;

    const STATUS_ALL = 2;

    const STATUS_ARR = [
        self::DRAFT => 'Draft',
        self::CONVERTED => 'Converted',
        self::STATUS_ALL => 'All',
    ];

    const MONTHLY = 1;

    const QUARTERLY = 2;

    const SEMIANNUALLY = 3;

    const ANNUALLY = 4;

    const RECURRING_ARR = [
        self::MONTHLY => 'Monthly',
        self::QUARTERLY => 'Quarterly',
        self::SEMIANNUALLY => 'Semi Annually',
        self::ANNUALLY => 'Annually',
    ];

    protected $with = ['quoteItems.product'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'client_id' => 'required',
        'quote_id' => 'required|unique:quotes,quote_id',
        'quote_date' => 'required',
        'due_date' => 'required',
    ];

    public $table = 'quotes';

    public $appends = ['status_label'];

    public $fillable = [
        'client_id',
        'quote_date',
        'due_date',
        'quote_id',
        'amount',
        'discount_type',
        'discount',
        'final_amount',
        'note',
        'term',
        'template_id',
        'status',
        'tenant_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'client_id' => 'integer',
        'quote_date' => 'string',
        'due_date' => 'string',
        'quote_id' => 'string',
        'amount' => 'double',
        'discount_type' => 'integer',
        'discount' => 'double',
        'final_amount' => 'double',
        'note' => 'string',
        'term' => 'string',
        'template_id' => 'integer',
        'status' => 'integer',
        'recurring' => 'integer',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_ARR[$this->status];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id')->withoutGlobalScopes();
    }

    public function quoteItems(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public static function generateUniqueQuoteId(): string
    {
        $quoteId = mb_strtoupper(Str::random(6));
        while (true) {
            $isExist = self::whereQuoteId($quoteId)->exists();
            if ($isExist) {
                self::generateUniqueQuoteId();
            }
            break;
        }

        return $quoteId;
    }

    public function quoteTemplate(): BelongsTo
    {
        return $this->belongsTo(InvoiceSetting::class, 'template_id', 'id');
    }

    public function setQuoteDateAttribute($value)
    {
        $this->attributes['quote_date'] = Carbon::createFromFormat(currentDateFormat(),
            $value)->translatedFormat('Y-m-d');
    }

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = Carbon::createFromFormat(currentDateFormat(),
            $value)->translatedFormat('Y-m-d');
    }
}
