<?php

namespace App\Http\Controllers;

use App\Models\SuperAdminSetting;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class CookieWarningController extends Controller
{
    public function index()
    {
        $showCookie = getSuperAdminSetting('show_cookie');
        $cookieWarning = getSuperAdminSetting('cookie_warning');

        return view('landing.cookie_warning', compact('showCookie', 'cookieWarning'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'show_cookie' => 'required',
            'cookie_warning' => 'required',
        ]);

        $input = $request->all();

        foreach ($input as $key => $value) {
            $setting = SuperAdminSetting::where('key', $key)->first();

            if (empty($setting)) {
                continue;
            }
            $setting->update(['value' => $value]);
        }

        Flash::success(__('messages.flash.cookie_warning_updated_successfully'));

        return redirect(route('cookie.warning.index'));
    }
}
