<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

/**
 * App\Models\Transaction
 *
 * @method static create(array $transactionData)
 *
 * @property int $id
 * @property string $transaction_id
 * @property string|null $tenant_id
 * @property int|null $payment_mode
 * @property float $amount
 * @property int $user_id
 * @property bool $status
 * @property int|null $is_manual_payment
 * @property array $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Subscription $subscription
 * @property-read MultiTenant|null $tenant
 * @property-read Subscription|null $transactionSubscription
 * @property-read User $user
 *
 * @method static Builder|Transaction newModelQuery()
 * @method static Builder|Transaction newQuery()
 * @method static Builder|Transaction query()
 * @method static Builder|Transaction whereAmount($value)
 * @method static Builder|Transaction whereCreatedAt($value)
 * @method static Builder|Transaction whereId($value)
 * @method static Builder|Transaction whereIsManualPayment($value)
 * @method static Builder|Transaction whereMeta($value)
 * @method static Builder|Transaction wherePaymentMode($value)
 * @method static Builder|Transaction whereStatus($value)
 * @method static Builder|Transaction whereTenantId($value)
 * @method static Builder|Transaction whereTransactionId($value)
 * @method static Builder|Transaction whereUpdatedAt($value)
 * @method static Builder|Transaction whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Transaction extends Model implements HasMedia
{
    use HasFactory, BelongsToTenant, Multitenantable, InteractsWithMedia;

    protected $table = 'transactions';

    public $fillable = ['transaction_id', 'amount', 'status', 'meta', 'tenant_id', 'user_id', 'payment_mode'];

    const PAYMENT_ATTACHMENTS = 'payment_attachments';

    /**
     * @var string[]
     */
    protected $casts = [
        'transaction_id' => 'string',
        'amount' => 'double',
        'user_id' => 'integer',
        'payment_mode' => 'integer',
        'meta' => 'json',
        'is_manual_payment' => 'integer',
    ];

    const PAID = 'Paid';

    const UNPAID = 'Unpaid';

    const APPROVED = 1;

    const DENIED = 2;

    const TYPE_STRIPE = 1;

    const TYPE_PAYPAL = 2;

    const TYPE_RAZORPAY = 3;

    const TYPE_CASH = 4;

    const TYPE_PAYSTACK = 5;

    const PAYMENT_TYPES = [
        self::TYPE_STRIPE => 'Stripe',
        self::TYPE_PAYPAL => 'PayPal',
        self::TYPE_RAZORPAY => 'RazorPay',
        self::TYPE_CASH => 'Manual',
        self::TYPE_PAYSTACK => 'Paystack',
    ];

    protected $appends = ['payment_attachments'];

    public function getPaymentAttachmentsAttribute(): string
    {
        /** @var Media $media */
        $media = $this->getMedia(self::PAYMENT_ATTACHMENTS)->first();
        if (! empty($media)) {
            return $media->getFullUrl();
        }

        return false;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'transaction_id');
    }

    public function transactionSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'transaction_id', 'id');
    }
}
