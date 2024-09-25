@extends('landing.layouts.app')
@section('title')
    {{ __('messages.landing.home') }}
@endsection
@section('content')
    <!-- banner-section -->
    <section class="banner-section">
        <div class="container">
            <div class="banner">
                <div class="row flex-column-reverse flex-lg-row">
                    <div class="col-lg-6">
                        <div class="banner-left mt-5 mt-lg-0">
                            <h1 class="mb-4 pb-3 d-none d-lg-block">{{ $sectionOne['text_main'] }}</h1>
                            <h1 class="mb-3 mb-sm-4 d-block d-lg-none">{{ $sectionOne['text_main'] }}</h1>
                            <p class="paragraph mb-4 mb-md-5">
                                {{ $sectionOne['text_secondary'] }}
                            </p>
                            @if(!\Illuminate\Support\Facades\Auth::check())
                                <a href="{{ route('register') }}" class="btn btn-secondary">{{ __('messages.landing.sign_up') }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="banner-right text-center text-lg-end">
                            <img src="{{ isset($sectionOne['img_url_one']) ? asset($sectionOne['img_url_one']) : asset('landing-page/images/hero-image.png') }}" alt="Hero Image" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- smart-way-section -->
    <section class="section smart-way-section">
        <div class="container">
            <div class="text-center">
                <h4 class="fs-6 text-primary fw-bold mb-3">{{ $sectionTwo['text_main'] }}</h4>
                <h2 class="fw-bolder max-w-680 mx-auto">{{ $sectionTwo['text_secondary'] }}</h2>
            </div>
        </div>
        <div class="invoice-card-section">
            <div class="container">
                <div class="invoice-cards">
                    <div class="row">
                        <div class="col-xxl-4 col-md-6 invoice-block">
                            <div class="invoice-card mx-lg-2">
                                <div class="icon">
                                    <i class="fa-solid fa-money-check-dollar"></i>
                                </div>
                                <h3 class="fs-4 fw-bold">{{ $sectionTwo['card_one_text'] }}</h3>
                                <p class="mb-0 paragraph fs-16">
                                    {{ $sectionTwo['card_one_text_secondary'] }}
                                </p>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 invoice-block">
                            <div class="invoice-card">
                                <div class="icon">
                                    <i class="fa-solid fa-calendar-days" style="padding:0 3px;"></i>
                                </div>
                                <h3 class="fs-4 fw-bold">{{ $sectionTwo['card_two_text'] }}</h3>
                                <p class="mb-0 paragraph fs-16">
                                    {{ $sectionTwo['card_two_text_secondary'] }}
                                </p>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 invoice-block">
                            <div class="invoice-card mx-lg-2">
                                <div class="icon">
                                    <i class="fa-solid fa-address-card"></i>
                                </div>
                                <h3 class="fs-4 fw-bold">{{ $sectionTwo['card_three_text'] }}</h3>
                                <p class="mb-0 paragraph fs-16">
                                    {{ $sectionTwo['card_three_text_secondary'] }}
                                </p>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 invoice-block">
                            <div class="invoice-card mx-lg-2">
                                <div class="icon">
                                    <i class="fa-solid fa-file-lines" style="padding:0 5px;"></i>
                                </div>
                                <h3 class="fs-4 fw-bold">{{ $sectionTwo['card_four_text'] }}</h3>
                                <p class="mb-0 paragraph fs-16">
                                    {{ $sectionTwo['card_four_text_secondary'] }}
                                </p>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 invoice-block">
                            <div class="invoice-card mx-lg-2">
                                <div class="icon">
                                    <i class="fa-solid fa-file" style="padding:0 5px;"></i>
                                </div>
                                <h3 class="fs-4 fw-bold">{{ $sectionTwo['card_five_text'] }}</h3>
                                <p class="mb-0 paragraph fs-16">
                                    {{ $sectionTwo['card_five_text_secondary'] }}
                                </p>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 invoice-block">
                            <div class="invoice-card mx-lg-2">
                                <div class="icon">
                                    <i class="fa-solid fa-file-invoice-dollar"></i>
                                </div>
                                <h3 class="fs-4 fw-bold">{{ $sectionTwo['card_six_text'] }}</h3>
                                <p class="mb-0 paragraph fs-16">
                                    {{ $sectionTwo['card_six_text_secondary'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- app-features-section -->
    <section class="app-features-section">
        <div class="container">
            <div class="app-features">
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="app-features-left">
                            <img src="{{ isset($sectionThree['img_url']) ? asset($sectionThree['img_url']) : asset('landing-page/images/app-features.png') }}" alt="app-features" width="100%">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="app-features-right">
                            <div class="text-sm-start text-center">
                                <h4 class="fs-6 text-primary fw-bold mb-3">{{ $sectionThree['text_main'] }}</h4>
                                <h2 class="fw-bolder mb-5">{{ $sectionThree['text_secondary'] }}</h2>
                            </div>
                            <div class="feature-content">
                                <div class="row mb-4">
                                    <div class="col-sm-2 mb-3 mb-sm-0">
                                        <div class="icon mx-auto">
                                            <i class="fa-solid fa-money-check-dollar"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="content ps-md-2 text-center text-sm-start">
                                            <h4 class="text-success fw-bold">{{ $sectionThree['text_one'] }}</h4>
                                            <p class="paragraph">
                                                {{ $sectionThree['text_one_secondary'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-sm-2 mb-3 mb-sm-0">
                                        <div class="icon mx-auto">
                                            <i class="fa-solid fa-money-check-dollar"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="content ps-md-2 text-center text-sm-start">
                                            <h4 class="text-success fw-bold">{{ $sectionThree['text_two'] }}</h4>
                                            <p class="paragraph">
                                                {{ $sectionThree['text_two_secondary'] }}
                                            </p>

                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-sm-2 mb-3 mb-sm-0">
                                        <div class="icon mx-auto">
                                            <i class="fa-solid fa-money-check-dollar"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="content ps-md-2 text-center text-sm-start">
                                            <h4 class="text-success fw-bold">{{ $sectionThree['text_three'] }}</h4>
                                            <p class="paragraph">
                                                {{ $sectionThree['text_three_secondary'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('landing.landing_pricing_plan.index', ['screenFrom' => Route::currentRouteName()])

    <!-- feedback-section -->
    <section class="feedback-section">
        <div class="container">
            <div class="text-center">
                <h4 class="fs-6 text-primary fw-bold mb-3">{{ strtoupper(__('messages.landing.feedbacks')) }}</h4>
                <h2 class="fw-bold max-w-680 mx-auto mb-5">{{ __('messages.landing.what_people') }}</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-10 text-center">
                    <div class="rev_slider testimonial-carousel">
                        @foreach($testimonials as $testimonial)
                            <div class="rev_slide">
                                <div class="test">
                                    <img src="{{ asset('landing-page/images/quotation-left.png') }}" alt="ql" class="feedback1">
                                    <img src="{{ asset('landing-page/images/quotation-right.png') }}" alt="qr" class="feedback2">

                                    <div class="user">
                                        <img src="{{ $testimonial->image_url }}" alt="testimonial-image">
                                    </div>
                                    <p class="paragraph text-start">
                                        {{ $testimonial->description }}
                                    </p>
                                    <h3 class="mb-0 fs-6">{{ $testimonial->name }}</h3>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- discover-section -->
    <section class="discover-section">
        <div class="container">
            <div class="text-center">
                <h4 class="fs-6 text-primary fw-bold mb-3">{{ strtoupper(__('messages.landing.discover')) }}</h4>
                <h2 class="fw-bolder max-w-680 mx-auto mb-5">{{ __('messages.landing.some_frequently') }}</h2>
            </div>
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="accordionExample">
                    @foreach($faqs as $faq)
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="faq-{{ $faq->id }}">
                                <button class="accordion-button fs-4 fw-bold px-0 pb-0" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{$faq->id}}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="faq-collapse-{{$faq->id}}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="faq-collapse-{{$faq->id}}" class="accordion-collapse collapse {{ $loop->first ? 'show' : ''}}" aria-labelledby="faq-{{ $faq->id }}" data-bs-parent="#accordionExample">
                                <div class="accordion-body px-0 pb-0">
                                    <p class="paragraph mb-0">
                                        {{ $faq->answer }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- contact-section -->
    <section class="contact-section" id="contact-us">
        <div class="container">
            <div class="text-center">
                <h4 class="fs-6 text-primary fw-bold mb-3">{{ strtoupper(__('messages.landing.contact')) }}</h4>
                <h2 class="fw-bold mb-5 max-w-680 mx-auto">{{ __('messages.landing.write_message') }}</h2>
            </div>
            <div class="contact-form">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        <form id="contactEnquiryForm">
                            @method('POST')
                            @csrf
                            <div class="form">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="position-relative d-flex align-items-center">
                                            <span class="position-absolute start-0 ms-3 input-icon"><i class="fa-regular fa-user text-primary fs-4"></i></span>
                                            <input type="text" name="full_name" id="name-field" placeholder="{{ __('messages.user.full_name') }}" class="form-control ps-5">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="position-relative d-flex align-items-center">
                                            <span class="position-absolute start-0 ms-3 input-icon"><i class="fa-regular fa-envelope text-primary fs-4"></i></span>
                                            <input type="email" name="email" id="email-field" placeholder="{{ __('messages.user.email') }}" class="form-control ps-5">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-12">
                                    <div class="position-relative d-flex align-items-center">
                                        <span class="position-absolute start-0 ms-3 input-icon"><i class="fa-regular fa-message text-primary fs-4"></i></span>
                                        <textarea placeholder="{{ __('messages.user.message') }}" name="message" id="message-field" class="form-control ps-5" rows="4" style="height: 120px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center mt-4 mb-5">
                                    <button type="submit" class="btn btn-success">{{ __('messages.landing.send_message') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="subscribe-section">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="subscribe-left">
                            <h3 class="text-white mb-3">{{ __('messages.landing.subscribe_now') }}</h3>
                            <p class="paragraph mb-0 text-white">{{ __('messages.landing.enter_your_email') }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-4 mt-lg-0">
                        <div class="subscribe-right position-relative">
                            <form id="subscribe-form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="email">
                                    <input type="email" id="email-field-1" name="email" placeholder="{{ __('messages.landing.email_address') }}">
                                    <a href="#" class="subscribe-btn d-block d-sm-none" id="subscribeBtn"><i class="fa-solid fa-paper-plane"></i></a>
                                    <a href="#" class="subscribe-btn d-none d-sm-block" id="subscribeBtn">{{ __('messages.landing.subscribe') }}</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        let getLoggedInUserdata = "{{ getLoggedInUser() }}"
        let logInUrl = "{{ url('login') }}"
        let fromPricing = true
        let makePaymentURL = "{{ route('purchase-subscription') }}"
        let subscribeText = "{{ __('messages.subscription_pricing_plans.choose_plan') }}"
        let toastData = JSON.parse('@json(session('toast-data'))')
    </script>
@endsection
