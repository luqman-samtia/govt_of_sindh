<?php

namespace App\Http\Controllers;

use Laracasts\Flash\Flash;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UserSettingRepository;
use App\Http\Requests\UpdateUserSettingRequest;

class UserSettingController extends AppBaseController
{

    /** @var UserSettingRepository */
    public $userSettingRepository;

    /**
     * @param  UserSettingRepository  $userSettingRepo
     */
    public function __construct(UserSettingRepository $userSettingRepo)
    {
        $this->userSettingRepository = $userSettingRepo;
    }

    public function edit()
    {
        $userSetting = $this->userSettingRepository->edit();
        $data = UserSetting::pluck('value', 'key')->toArray();

        return view('super_admin_new_user_settings.general', $userSetting, compact('userSetting', 'data'));
    }

    public function update(UpdateUserSettingRequest $request)
    {
        $this->userSettingRepository->userUpdateSetting($request->all());
        Flash::success(__('messages.flash.setting_updated'));

        return redirect()->back();
    }
}
