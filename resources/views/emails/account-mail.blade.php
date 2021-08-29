@component('mail::message')
# Evento

Thanks,
{{ config('app.name') }}<br>
usuario: {{ $data['account']['name'] }}<br>
email: {{ $data['account']['email'] }}<br>
token: {{ $data['token'] }}<br>
@endcomponent
