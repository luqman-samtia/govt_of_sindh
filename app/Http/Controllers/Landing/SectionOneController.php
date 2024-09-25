<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\UpdateSectionOneRequest;
use App\Models\SectionOne;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class SectionOneController extends AppBaseController
{
    /**
     * @return Factory|View
     */
    public function index(): View
    {
        $sectionOne = SectionOne::first();

        return view('landing.section_one.index', compact('sectionOne'));
    }

    /**
     * @return Application|Factory|RedirectResponse|Redirector|View
     */
    public function update(UpdateSectionOneRequest $request): RedirectResponse
    {
        $input = $request->all();
        $sectionOne = SectionOne::first();
        $sectionOne->update($input);

        if (isset($input['img_url_one']) && ! empty($input['img_url_one'])) {
            $sectionOne->clearMediaCollection(SectionOne::SECTION_ONE_PATH);
            $media = $sectionOne->addMedia($input['img_url_one'])->toMediaCollection(SectionOne::SECTION_ONE_PATH,
                config('app.media_disc'));
            $sectionOne->update(['img_url_one' => $media->getUrl()]);
        }

        \Flash::success(__('messages.flash.section_one_updated'));

        return redirect(route('super.admin.section.one'));
    }
}
