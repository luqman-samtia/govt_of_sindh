<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SectionOne extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const SECTION_ONE_PATH = 'section_one_image';

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'text_main' => 'required|string|max:45',
        'text_secondary' => 'required|string|max:191',
        'img_url_one' => 'mimes:jpeg,jpg,png',
    ];

    /**
     * @var array
     */
    public $fillable = [
        'text_main',
        'text_secondary',
        'img_url_one',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'text_main' => 'string',
        'text_secondary' => 'string',
        'img_url_one' => 'string',
    ];

    public function getImgOneUrlAttribute(): string
    {
        /** @var Media $media */
        $media = $this->getMedia(self::SECTION_ONE_PATH)->first();
        if (! empty($media)) {
            return $media->getFullUrl();
        }

        return asset('landing-page/images/hero-image.png');
    }
}
