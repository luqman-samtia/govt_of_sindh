<?php

namespace App\MediaLibrary;

use App\Models\AdminTestimonial;
use App\Models\Payment;
use App\Models\PaymentQrCode;
use App\Models\Product;
use App\Models\SectionOne;
use App\Models\SectionThree;
use App\Models\Setting;
use App\Models\SuperAdminSetting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserSetting;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

/**
 * Class CustomPathGenerator
 */
class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $path = '{PARENT_DIR}'.DIRECTORY_SEPARATOR.$media->id.DIRECTORY_SEPARATOR;

        switch ($media->collection_name) {
            case User::PROFILE:
                return str_replace('{PARENT_DIR}', User::PROFILE, $path);
            case Product::Image:
                return str_replace('{PARENT_DIR}', Product::Image, $path);
            case Setting::PATH:
                return str_replace('{PARENT_DIR}', Setting::PATH, $path);
            case SuperAdminSetting::PATH:
                return str_replace('{PARENT_DIR}', SuperAdminSetting::PATH, $path);
            case SectionOne::SECTION_ONE_PATH:
                return str_replace('{PARENT_DIR}', SectionOne::SECTION_ONE_PATH, $path);
            case SectionThree::SECTION_THREE_PATH:
                return str_replace('{PARENT_DIR}', SectionThree::SECTION_THREE_PATH, $path);
            case AdminTestimonial::PATH:
                return str_replace('{PARENT_DIR}', AdminTestimonial::PATH, $path);
            case Payment::PAYMENT_ATTACHMENT:
                return str_replace('{PARENT_DIR}', Payment::PAYMENT_ATTACHMENT, $path);
            case Transaction::PAYMENT_ATTACHMENTS:
                return str_replace('{PARENT_DIR}', Transaction::PAYMENT_ATTACHMENTS, $path);
            case PaymentQrCode::PAYMENT_QR_CODE:
                return str_replace('{PARENT_DIR}', PaymentQrCode::PAYMENT_QR_CODE, $path);
            case UserSetting::USER_SETTING_IMAGE:
                return str_replace('{PARENT_DIR}', UserSetting::USER_SETTING_IMAGE, $path);
            case 'default':
                return '';
        }
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media).'thumbnails/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media).'rs-images/';
    }
}
