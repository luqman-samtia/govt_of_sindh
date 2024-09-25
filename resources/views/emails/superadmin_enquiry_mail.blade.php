@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset(getLogoUrl()) }}" class="logo" alt="{{ getAppName() }}">
        @endcomponent
    @endslot
    {{-- Body --}}
    <div>
        <h1 style="color:#686868; text-align:center;"><strong>Enquiry Message</strong></h1>
        <p>Dear Sir,</p>
        <p>Allow me to introduce myself:</p>
        <p><strong>Name : </strong>{{ $full_name }}</p>
        <p><strong>Email : </strong>{{ $email }}</p>
        <p><strong>Message: </strong>{{ $message }}</p>
        <p>I deeply value your time and consideration in this matter. Your insights and guidance are invaluable to me as I
            look forward to exploring the potential of working together.
        </p>
        <p> Thank you for considering my inquiry. I am excited about the possibility of collaborating with your team and
            bringing innovative design solutions to life.
        </p>
    </div>

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent
