<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Stancl\Tenancy\Database\TenantScope;

/**
 * App\Models\Invoice
 *
 * @property int $id
 * @property string $invoice_id
 * @property int $client_id
 * @property string|null $tenant_id
 * @property string $invoice_date
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
 * @property-read Collection|AdminPayment[] $AdminPayment
 * @property-read int|null $admin_payment_count
 * @property-read Client $client
 * @property-read string $status_label
 * @property-read Collection|InvoiceItem[] $invoiceItems
 * @property-read int|null $invoice_items_count
 * @property-read InvoiceSetting|null $invoiceTemplate
 * @property-read Payment $payment
 * @property-read Collection|Payment[] $payments
 * @property-read int|null $payments_count
 * @property-read MultiTenant|null $tenant
 * @property-read \App\Models\User $user
 *
 * @method static Builder|Invoice newModelQuery()
 * @method static Builder|Invoice newQuery()
 * @method static Builder|Invoice query()
 * @method static Builder|Invoice whereAmount($value)
 * @method static Builder|Invoice whereClientId($value)
 * @method static Builder|Invoice whereCreatedAt($value)
 * @method static Builder|Invoice whereDiscount($value)
 * @method static Builder|Invoice whereDiscountType($value)
 * @method static Builder|Invoice whereDueDate($value)
 * @method static Builder|Invoice whereFinalAmount($value)
 * @method static Builder|Invoice whereId($value)
 * @method static Builder|Invoice whereInvoiceDate($value)
 * @method static Builder|Invoice whereInvoiceId($value)
 * @method static Builder|Invoice whereNote($value)
 * @method static Builder|Invoice whereRecurring($value)
 * @method static Builder|Invoice whereStatus($value)
 * @method static Builder|Invoice whereTemplateId($value)
 * @method static Builder|Invoice whereTenantId($value)
 * @method static Builder|Invoice whereTerm($value)
 * @method static Builder|Invoice whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property int|null $parent_id
 * @property Setting|mixed $currency_id
 * @property int $recurring_status
 * @property int|null $recurring_cycle
 * @property string|null $last_recurring_on
 *
 * @method static Builder|Invoice whereCurrencyId($value)
 * @method static Builder|Invoice whereLastRecurringOn($value)
 * @method static Builder|Invoice whereParentId($value)
 * @method static Builder|Invoice whereRecurringCycle($value)
 * @method static Builder|Invoice whereRecurringStatus($value)
 */
class Invoice extends Model
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

    const UNPAID = 1;

    const PAID = 2;

    const PARTIALLY = 3;

    const OVERDUE = 4;

    const STATUS_ALL = 5;

    const PROCESSING = 6;

    const STATUS_ARR = [
        self::STATUS_ALL => 'All',
        self::DRAFT => 'Draft',
        self::UNPAID => 'Unpaid',
        self::PAID => 'Paid',
        self::PARTIALLY => 'Partially Paid',
        self::OVERDUE => 'Overdue',
        self::PROCESSING => 'Processing',
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

    public const RECURRING_OFF = 0;

    public const RECURRING_ON = 1;

    public const RECURRING_STATUS_ARR = [
        self::RECURRING_ON => 'On',
        self::RECURRING_OFF => 'Off',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'client_id' => 'required',
        'invoice_id' => 'required|is_unique:invoices,invoice_id',
        'invoice_date' => 'required',
        'due_date' => 'required',
    ];

    public static $messages = [
        'client_id.required' => 'The Client field is required.',
        'invoice_date.required' => 'The invoice date field is required.',
        'due_date' => 'The invoice Due date field is required.',
    ];

    public $table = 'invoices';

    public $appends = ['status_label'];

    public $fillable = [
        'client_id',
        'invoice_date',
        'due_date',
        'invoice_id',
        'currency_id',
        'amount',
        'discount_type',
        'discount',
        'final_amount',
        'payment_qr_code_id',
        'note',
        'term',
        'template_id',
        'status',
        'tenant_id',
        'recurring_status',
        'recurring_cycle',
        'last_recurring_on',
    ];

    protected $casts = [
        'client_id' => 'integer',
        'parent_id' => 'integer',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'invoice_id' => 'string',
        'currency_id' => 'integer',
        'amount' => 'double',
        'discount_type' => 'integer',
        'discount' => 'double',
        'final_amount' => 'double',
        'note' => 'string',
        'term' => 'string',
        'template_id' => 'integer',
        'status' => 'integer',
        'recurring_status' => 'integer',
        'recurring_cycle' => 'integer',
        'last_recurring_on' => 'date',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_ARR[$this->status];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id')->withoutGlobalScope(new TenantScope());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'id')->withoutGlobalScope(new TenantScope());
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateUniqueInvoiceId(): string
    {
        $invoiceId = mb_strtoupper(Str::random(6));
        while (true) {
            $isExist = self::whereInvoiceId($invoiceId)->exists();
            if ($isExist) {
                self::generateUniqueInvoiceId();
            }
            break;
        }

        return $invoiceId;
    }

    public function getCurrencyIdAttribute($value): mixed
    {
        if (! empty($value)) {
            return $value;
        } else {
            return getSettingValue('current_currency');
        }
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'invoice_id', 'id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'invoice_id', 'id');
    }

    public function paymentQrCode(): BelongsTo
    {
        return $this->belongsTo(PaymentQrCode::class, 'payment_qr_code_id', 'id');
    }

    public function parentInvoice(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function childInvoices(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function AdminPayment(): HasMany
    {
        return $this->hasMany(AdminPayment::class, 'invoice_id', 'id');
    }

    public function invoiceTemplate(): BelongsTo
    {
        return $this->belongsTo(InvoiceSetting::class, 'template_id', 'id');
    }

    public function setInvoiceDateAttribute($value)
    {
        $this->attributes['invoice_date'] = Carbon::createFromFormat(currentDateFormat(), $value)->translatedFormat('Y-m-d');
    }

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = Carbon::createFromFormat(currentDateFormat(), $value)->translatedFormat('Y-m-d');
    }

    public function invoiceTaxes(): BelongsToMany
    {
        return $this->belongsToMany(Tax::class, 'invoice_taxes');
    }
}
