<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

/**
 * App\Models\UserSetting
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $logo_url
 * @property-read MediaCollection|Media[] $media
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereValue($value)
 * @mixin \Eloquent
 */
class UserSetting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public $table = 'user_settings';

    const USER_SETTING_IMAGE = 'user-settings';

    public $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'key' => 'string',
        'value' => 'string',
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

    public static $rules = [
        'app_name' => 'string|max:191',
        'company_name' => 'string|max:191',
        'app_logo' => 'nullable|mimes:jpg,jpeg,png',
        'favicon_icon' => 'nullable|mimes:jpg,jpeg,png',
    ];

    /**
     * @return string
     */
    public function getProfileImageAttribute(): string
    {
        /** @var Media $media */
        $media = $this->getMedia(self::USER_SETTING_IMAGE)->first();
        if (!empty($media)) {
            return $media->getFullUrl();
        }

        return asset('assets/images/infyom.png');
    }
}
