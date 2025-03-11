@component('emails.components.layout', ['title' => $heading2, 'heading' => $heading2, 'showFooter' => false])
    <p>Dear {{ $username }},</p>

    <p>{{ $message2 }}</p>

    <p>This message was sent from an unmonitored e-mail address. Please do not reply to this message. Please contact <a href="{{config('app.email')}}">{{config('app.email')}}</a> if you need additional help.</p>

    @include('emails.components.legal-disclaimer')
@endcomponent
