@component('mail::message')
<h2>New Email from EDOSUBEB</h2>
# {{ $emailDetails['subject'] }}

{{ $emailDetails['message'] }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
