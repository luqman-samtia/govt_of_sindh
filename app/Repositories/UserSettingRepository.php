<?php

namespace App\Repositories;

use App\Models\UserSetting;
use Illuminate\Support\Arr;
use App\Repositories\BaseRepository;

/**
 * Class UserSettingRepository
 */
class UserSettingRepository extends BaseRepository
{
    public $fieldSearchable = [
        'app_name',
        'app_logo',
    ];

    /**
     * {@inheritDoc}
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return UserSetting::class;
    }

    public function edit()
    {
        $data = [];
        $timezones = app()->make(SettingRepository::class)->getTimezones();
        $data['timezones'] = $timezones;
        $data['dateFormats'] = UserSetting::DateFormatArray;

        return $data;
    }

    public function userUpdateSetting($input): bool
    {
        if (isset($input['app_logo']) && !empty($input['app_logo'])) {
            /** @var UserSetting $userSetting */
            $userSetting = UserSetting::where('key', '=', 'app_logo')->first();
            $userSetting = $this->uploadUserSettingImages($userSetting, $input['app_logo']);
        }

        if (isset($input['favicon_icon']) && !empty($input['favicon_icon'])) {
            /** @var UserSetting $userSetting */
            $userSetting = UserSetting::where('key', '=', 'favicon_icon')->first();
            $userSetting = $this->uploadUserSettingImages($userSetting, $input['favicon_icon']);
        }

        $input['company_phone'] = '+' . $input['prefix_code'] . $input['company_phone'];
        $userSettingInputArray = Arr::only($input, [
            'app_name', 'company_name', 'country_code', 'company_phone', 'date_format', 'time_zone','prefix_code'
        ]);

        foreach ($userSettingInputArray as $key => $value) {
            $value = is_null($value) ? '' : $value;
            $userSetting = UserSetting::where('key', '=', $key)->first();

            if (empty($userSetting)) {
                UserSetting::create([
                    'key' => $key,
                    'value' => $value,
                ]);

                continue;
            }

            $userSetting->update(['value' => $value]);
        }

        return true;
    }

    /**
     * @param $userSetting
     * @param $value
     * @return mixed
     */
    public function uploadUserSettingImages($userSetting, $value): mixed
    {
        // $userSetting->clearMediaCollection(UserSetting::USER_SETTING_IMAGE);
        $media = $userSetting->addMedia($value)->toMediaCollection(UserSetting::USER_SETTING_IMAGE, config('app.media_disc'));
        $userSetting = $userSetting->refresh();
        $userSetting->update(['value' => $media->getFullUrl()]);

        return $userSetting;
    }
}
