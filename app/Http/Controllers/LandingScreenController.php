<?php

namespace App\Http\Controllers;

use App\Models\AdminTestimonial;
use App\Models\Faq;
use App\Models\LandingAboutUs;
use App\Models\SectionOne;
use App\Models\SectionThree;
use App\Models\SectionTwo;
use App\Models\ServiceSlider;
use App\Models\SubscriptionPlan;
use App\Repositories\SubscriptionPlanRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class LandingScreenController extends AppBaseController
{
    private $subscriptionPlanRepository;

    public function __construct(SubscriptionPlanRepository $subscriptionPlanRepo)
    {
        $this->subscriptionPlanRepository = $subscriptionPlanRepo;
    }

    public function index()
    {
        // $data['sectionOne'] = SectionOne::with('media')->first();
        // $data['sectionTwo'] = SectionTwo::first();
        // $data['sectionThree'] = SectionThree::with('media')->first();
        // $data['subscriptionPricingPlans'] = SubscriptionPlan::whereIsDefault(false)->toBase()->get();
        // $data['testimonials'] = AdminTestimonial::with('media')->get();
        // $data['faqs'] = Faq::toBase()->orderByDesc('created_at')->get();

        return redirect('/login');
    }

    public function aboutUs()
    {
        return redirect('/login');
    }

    public function services(): View
    {
        return redirect('/login');
    }


    public function contactUs(): View
    {
        return redirect('/login');
    }

    public function faq(): View
    {
        return redirect('/login');
    }

    public function pricing(): View
    {
        return redirect('/login');
    }

    public function changeLanguage(Request $request): RedirectResponse
    {
        return redirect('/login');
    }

    public function declinedCookie()
    {
        return redirect('/login');
    }
}
