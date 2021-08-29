@component('mail::message')
# Evento

Thanks,
{{ config('app.name') }}<br>
Evento<br>
    tipo: {{ $data['event']['type'] }}<br>
    monto: {{ $data['event']['amount'] }}<br>
token: {{ $data['token'] }}<br>
@endcomponent
