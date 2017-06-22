@component('mail::message')
{{-- Greeting --}}

# Hello!

{{-- Intro Lines --}}
{{ $introLine }}

{{-- Action Button --}}
@component('mail::button', ['url' => $actionUrl, 'color' => 'blue'])
{{ $actionText }}
@endcomponent

{{-- Outro Lines --}}
{{ $outroLine }}

<!-- Salutation -->
Regards, {{ config('app.name') }}

<!-- Subcopy -->
@isset($actionText)
@component('mail::subcopy')
If you’re having trouble clicking the "{{ $actionText }}" button, copy and paste the URL below
into your web browser: [{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endisset
@endcomponent
