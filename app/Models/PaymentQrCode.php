<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\PaymentQrCode
 *
 * @property int $id
 * @property string $title
 * @property int $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $qr_image
 * @property-read \Illuminate\Database\Eloquent\Collection|Invoice[] $invoices
 * @property-read int|null $invoices_count
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentQrCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentQrCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentQrCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentQrCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentQrCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentQrCode whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentQrCode whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentQrCode whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PaymentQrCode extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'payment_qr_codes';

    protected $fillable = ['title', 'is_default','user_id'];

    protected $appends = ['qr_image'];

    const PAYMENT_QR_CODE = 'payment-qr-code';

    public static $rules = [
        'title' => 'required',
        'qr_image' => 'mimes:jpeg,jpg,png|required',
    ];

    public function getQrImageAttribute(): string
    {
        /** @var Media $media */
        $media = $this->getMedia(self::PAYMENT_QR_CODE)->first();
        if (! empty($media)) {
            return $media->getFullUrl();
        }

        return asset('assets/images/avatar.png');
    }
}
