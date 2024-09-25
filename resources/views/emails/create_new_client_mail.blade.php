@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset(getLogoUrl()) }}" class="logo" alt="{{ getAppName() }}">
        @endcomponent
    @endslot
    {{-- Body --}}
    <div>
        <h2>Welcome to {{ $clientName }}, <b></b></h2><br>
        <p>Your account has been successfully created on {{ getAppName() }}</p>
        <p>You are invited by <strong>{{ $admin }}</strong></p>
        <p>Your email address is <strong>{{ $email }}</strong></p>
        <p>In {{ getAppName() }}, you can manage all of your invoices.</p>
        <p>Thank for joining and have a great day!</p><br>
        <div style="display: flex;justify-content: center">
            <a href="{{ route('client.password.reset', $client_id) }}"
                style="
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
