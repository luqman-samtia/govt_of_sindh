<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\UpdateSectionTwoRequest;
use App\Models\SectionTwo;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class SectionTwoController extends AppBaseController
{
    /**
     * @return Factory|View
     */
    public function index(): View
    {
        $sectionTwo = SectionTwo::first();

        return view('landing.section_two.index', compact('sectionTwo'));
    }

    /**
     * @return Application|Factory|RedirectResponse|Redirector|View
     */
    public function update(UpdateSectionTwoRequest $request): RedirectResponse
    {
        $input = $request->all();
        $sectionTwo = SectionTwo::first();
        $sectionTwo->update($input);

        \Flash::success(__('messages.flash.section_two_updated'));

        return redirect(route('super.admin.section.two'));
    }
}
