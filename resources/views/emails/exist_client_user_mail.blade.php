@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset(getLogoUrl()) }}" class="logo" alt="{{ getAppName() }}">
        @endcomponent
    @endslot
    @php
        $styleCss = 'style';
    @endphp
    {{-- Body --}}
    <div>
        <h2>Welcome {{ $admin }}, <b></b></h2><br>
        <p><strong>{{ $admin }}</strong> invited you to his organization.</p>
        <p>You can click below button to join his organization.</p><br>
        <div {{ $styleCss }}="display: flex;justify-content: center">
            <a href="{{ route('login') }}"
                {{ $styleCss }}="
        padding: 7px 15px;text-decoration: none;font-size: 14px;background-color:  #0000FF;font-weight: 500;border: none;border-radius: 8px;color: white
        ">
                Join Now
            </a>
        </div>
    </div>

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent
