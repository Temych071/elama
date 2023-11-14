@component('mail::message')
# Добро пожаловать в Daily Grow

Отлично, вы сделали первый шаг для построения эффективной системы интернет-маркетинга и повышения продаж. Посмотрите наши обучающие видео, чтобы быстро подключить все каналы трафика и настроить аналитику.

<a href="https://www.youtube.com/watch?v=XC1gWCF5fk8" target="_blank">![Tutorial]({{ url('/images/video-tutor-preview.jpg') }})</a>

@component('mail::button', ['url' => 'https://dailygrow.ru/help/'])
Читать инструкцию
@endcomponent

<div style="text-align: center">
    Спасибо за регистрацию в <br><a href="{{ url('/') }}">{{ config('app.name') }}</a>
</div>

@endcomponent
