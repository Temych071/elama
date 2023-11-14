@component('mail::message')
<b>{{$message['title']}}</b>

{!! $message['text'] !!}
@endcomponent
