<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    @php
        $settingValue = getSuperAdminSettingValue();
    @endphp
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | ACE Sindh </title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- slick-slider -->
    <link href="{{ asset('landing-page/css/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('landing-page/css/slick-theme.css') }}" rel="stylesheet" type="text/css">
    <!-- owl-carousel -->
    <link rel="stylesheet" href="{{ asset('landing-page/css/owl.carousel.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing-page/css/owl.theme.default.css') }}" />
    <!-- bootstrap css -->
    <link href="{{ asset('landing-page/scss/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <!-- custom css -->
    <link href="{{ asset('landing-page/scss/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/front-third-party.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
    @include('landing.layouts.header')
    @yield('content')
    @include('landing.layouts.footer')

    @if (!empty(getSuperAdminSetting('show_cookie')) && getSuperAdminSetting('show_cookie')->value == 1)
        @include('cookie-consent::index')
    @endif

    @if (!empty($settingValue['home_page_support_link']['value']))
        <div class="chat-support-link">
            <a href="{{ $settingValue['home_page_support_link']['value'] }}" target="_blank" class="text-white fs-4">
                <i class="fa-solid fa-headset"></i>
            </a>
        </div>
    @endif
</body>
@routes
<script src="{{ asset('landing-page/js/jquery.min.js') }}"></script>
<script src="{{ asset('landing-page/js/slick.min.js') }}"></script>
<script src="{{ asset('landing-page/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('landing-page/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ mix('assets/js/front-pages.js') }}"></script>
<script>
    $('.owl-carousel').owlCarousel({
        loop: false,
        margin: 40,
        arrows: true,
        dots: false,
        nav: true,
        autoplay: false,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1,
                margin: 10
            },
            768: {
                items: 2,
                margin: 20
            },
            1024: {
                items: 3,
                margin: 25
            },
            1400: {
                items: 4
            }
        }
    })
</script>
<script>
    let rev = $('.rev_slider');
    rev.on('init', function(event, slick, currentSlide) {
        let
            cur = $(slick.$slides[slick.currentSlide]),
            next = cur.next(),
            prev = cur.prev();
        prev.addClass('slick-sprev');
        next.addClass('slick-snext');
        cur.removeClass('slick-snext').removeClass('slick-sprev');
        slick.$prev = prev;
        slick.$next = next;
    }).on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        var
            cur = $(slick.$slides[nextSlide]);
        slick.$prev.removeClass('slick-sprev');
        slick.$next.removeClass('slick-snext');
        next = cur.next(),
            prev = cur.prev();
        prev.prev();
        prev.next();
        prev.addClass('slick-sprev');
        next.addClass('slick-snext');
        slick.$prev = prev;
        slick.$next = next;
        cur.removeClass('slick-next').removeClass('slick-sprev');
    });

    rev.slick({
        speed: 1000,
        arrows: true,
        dots: true,
        focusOnSelect: true,
        prevArrow: '<i class="fa-solid fa-arrow-left-long"></i>',
        nextArrow: '<i class="fa-solid fa-arrow-right-long"></i>',
        infinite: true,
        autoplay: false,
        autoplayTimeout: 5000,
        centerMode: true,
        slidesPerRow: 1,
        slidesToShow: 1,
        slidesToScroll: 1,
        centerPadding: '0',
        swipe: true,
        customPaging: function(slider, i) {
            return '';
        },
    });
</script>
@yield('scripts')

</html>
