@php
    $styleCss = 'style';
    $settingValue = getSuperAdminSettingValue();
@endphp
<section class="footer-section">
    <div class="container-md container-fluid">
        <div class="footer">
            <div class="row justify-content-between">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="footer-desc">
                        <div class="footer-logo">
                            <img src="{{ asset($settingValue['app_logo']['value']) }}" class="img-fluid front-logo" alt="footer-logo">
                        </div>
                        <p class="paragraph mb-0 mt-4 text-white fs-16">
                            {!! $settingValue['footer_text']['value'] !!}
                        </p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-12 mb-2 mb-md-0">
                    <div class="contact-us ">
                        <h5 class="text-primary mb-4">{{ __('messages.landing.contact_us') }}</h5>
                            <a href="mailto:{{ $settingValue['email']['value'] }}" class="text-decoration-none text-white fs-16 mb-3 d-block">
                                {{ $settingValue['email']['value'] }}
                            </a>
                            <a href="tel:{{ $settingValue['phone']['value'] }}" class="text-decoration-none text-white fs-16 mb-3 d-block">
                                {{ '+'.$settingValue['region_code']['value'] .' '. $settingValue['phone']['value'] }}
                            </a>
                    </div>
                </div>
                @if(!(empty($settingValue['twitter_url']['value']) && empty($settingValue['facebook_url']['value']) &&
    empty($settingValue['linkedin_url']['value']) && empty($settingValue['youtube_url']['value'])))
                    <div class="col-lg-2 col-md-6 col-sm-6 col-12 text-start">
                        <div class="follow-us">
                            <h5 class="text-primary mb-4">{{ __('messages.landing.follow_us') }}</h5>
                            <div class="social-icon d-flex align-items-center">
                                <a href="{{ $settingValue['twitter_url']['value'] }}" target="_blank"
                                   class="text-decoration-none social-box d-flex align-items-center justify-content-center
                                    {{ empty($settingValue['twitter_url']['value']) ? 'd-none' : ''}}">
                                    <i class="fa-brands fa-twitter"></i>
                                </a>
                                <a href="{{ $settingValue['facebook_url']['value'] }}" target="_blank"
                                   class="text-decoration-none social-box d-flex align-items-center justify-content-center {{ empty($settingValue['facebook_url']['value']) ? 'd-none' : ''}}">
                                    <i class="fa-brands fa-facebook"></i>
                                </a>
                                <a href="{{ $settingValue['linkedin_url']['value'] }}" target="_blank"
                                   class="text-decoration-none social-box d-flex align-items-center justify-content-center {{ empty($settingValue['linkedin_url']['value']) ? 'd-none' : ''}}">
                                    <i class="fa-brands fa-linkedin-in"></i>
                                </a>
                                <a href="{{ $settingValue['youtube_url']['value'] }}" target="_blank"
                                   class="text-decoration-none social-box d-flex align-items-center justify-content-center {{ empty($settingValue['youtube_url']['value']) ? 'd-none' : ''}}">
                                    <i class="fa-brands fa-youtube"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
