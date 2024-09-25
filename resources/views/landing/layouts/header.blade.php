@php
    $styleCss = 'style';
    $settingValue = getSuperAdminSettingValue();
@endphp
<header class="header">
    <div class="container-md container-fluid">
        <div class="top-header">
            <div class="row justify-content-between">
                <div class="col-lg-2 col-sm-2 col-3">
                    <div class="header-logo">
                        <img src="{{ asset($settingValue['app_logo']['value']) }}" class="img-fluid" alt="Invoice">
                    </div>
                </div>
                <div class="col-lg-7 col-sm-9 col-9 ps-0">
                    <div class="header-nav">
                        <ul class="nav justify-content-end align-items-center">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page"
                                   href="{{ route('landing.home') }}">{{ __('messages.landing.home') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#contact-us">{{ __('messages.landing.contact') }}</a>
                            </li>
                            <li class="nav-item">
                                @php
                                    $styleCss = 'style';
                                @endphp
                                <div class="dropdown">
                                    <a class="btn dropdown-toggle" href="javascript:void(0)" role="button"
                                       id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ __('messages.language') }}
                                    </a>
                                    <ul class="dropdown-menu" {{ $styleCss }}="min-width: 200px" aria-labelledby="
                                    languageDropdown">
                            @foreach(getLanguages() as $key => $value)
                                <li class="languageSelection {{ (checkLanguageSession() == $key) ? 'active' : '' }}"
                                    data-prefix-value="{{ $key }}" {{ $styleCss }}="max-height: 40px">
                                <a class="dropdown-item {{ (checkLanguageSession() == $key) ? 'active' : '' }}"
                                   href="javascript:void(0)">{{ $value }}
                                </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    </li>
                    @if(!empty( $settingValue['home_page_support_link']['value']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $settingValue['home_page_support_link']['value'] }}"
                               target="_blank">{{ __('messages.landing.support') }}</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="btn btn-primary ms-sm-4 ms-2"
                           href="{{ route('login') }}">{{ \Illuminate\Support\Facades\Auth::check() ? __('messages.landing.dashboard') : __('messages.landing.login') }}</a>
                    </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
