@component('mail::message')
# Evento

Thanks,
{{ config('app.name') }}<br>
usuario: {{ $data['account'] }}
token: {{ $data['token'] }}<br>
@endcomponent
