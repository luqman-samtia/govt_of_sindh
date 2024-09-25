<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

/**
 * App\Models\Setting
 *
 * @property mixed $media
 * @property mixed $value
 * @property int $id
 * @property string $key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed|string $logo_url
 * @property-read int|null $media_count
 *
 * @method static Builder|Setting newModelQuery()
 * @method static Builder|Setting newQuery()
 * @method static Builder|Setting query()
 * @method static Builder|Setting whereCreatedAt($value)
 * @method static Builder|Setting whereId($value)
 * @method static Builder|Setting whereKey($value)
 * @method static Builder|Setting whereUpdatedAt($value)
 * @method static Builder|Setting whereValue($value)
 *
 * @mixin \Eloquent
 *
 * @property string|null $tenant_id
 * @property-read \App\Models\MultiTenant|null $tenant
 *
 * @method static Builder|Setting whereTenantId($value)
 */
class Setting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, BelongsToTenant;

    public $table = 'settings';

    const PATH = 'settings';

    const DEFAULT_TEMPLATE = 1;

    const CURRENCY_AFTER_AMOUNT = 1;

    const PAYMENT_AUTO_APPROVED = 1;

    const INVOICE__TEMPLATE_ARRAY = [
        'defaultTemplate' => 'Default',
        'newYorkTemplate' => 'New York',
        'torontoTemplate' => 'Toronto',
        'rioTemplate' => 'Rio',
        'londonTemplate' => 'London',
        'istanbulTemplate' => 'Istanbul',
        'mumbaiTemplate' => 'Mumbai',
        'hongKongTemplate' => 'Hong Kong',
        'tokyoTemplate' => 'Tokyo',
        'parisTemplate' => 'Paris',
    ];

    const DateFormatArray = [
        'd-m-Y' => 'DD-MM-YYYY',
        'm-d-Y' => 'MM-DD-YYYY',
        'Y-m-d' => 'YYYY-MM-DD',
        'm/d/Y' => 'MM/DD/YYYY',
        'd/m/Y' => 'DD/MM/YYYY',
        'Y/m/d' => 'YYYY/MM/DD',
        'm.d.Y' => 'MM.DD.YYYY',
        'd.m.Y' => 'DD.MM.YYYY',
        'Y.m.d' => 'YYYY.MM.DD',
    ];

    public $fillable = [
        'key',
        'value',
        'tenant_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'key' => 'string',
        'value' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'app_name' => 'string|max:191',
        'company_name' => 'string|max:191',
        'app_logo' => 'nullable|mimes:jpg,jpeg,png',
        'company_logo' => 'nullable|mimes:jpg,jpeg,png',
        'country' => 'nullable|required_with:show_additional_address_in_invoice',
        'state' => 'nullable|required_with:show_additional_address_in_invoice',
        'city' => 'nullable|required_with:show_additional_address_in_invoice',
        'zipcode' => 'nullable|required_with:show_additional_address_in_invoice',
        'fax_no' => 'nullable|required_with:show_additional_address_in_invoice',
    ];

    public function getLogoUrlAttribute(): string
    {
        /** @var Media $media */
        $media = $this->media->first();
        if (! empty($media)) {
            return $media->getFullUrl();
        }

        return asset('assets/images/infyom.png');
    }
}
