<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&family=Roboto:wght@700&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite('resources/css/app.scss')

        <!-- Scripts -->
        @routes
        @vite('resources/js/app.js')

        @if (app()->environment('production'))
            <!-- Yandex.Metrika counter -->
            <script type="text/javascript" >
                (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                    m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
                (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

                ym(87284038, "init", {
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            </script>
            <noscript><div><img src="https://mc.yandex.ru/watch/87284038" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
            <!-- /Yandex.Metrika counter -->

            @if (!Route::is('reviews.public.*'))
                @if (false)
                    <!-- Begin of Chaport Live Chat code -->
                    <script type="text/javascript">
                        (function(w,d,v3){
                            w.chaportConfig = {
                                appId : '63958d4cc5631e840e901566'
                            };

                            if(w.chaport)return;v3=w.chaport={};v3._q=[];v3._l={};v3.q=function(){v3._q.push(arguments)};v3.on=function(e,fn){if(!v3._l[e])v3._l[e]=[];v3._l[e].push(fn)};var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://app.chaport.com/javascripts/insert.js';var ss=d.getElementsByTagName('script')[0];ss.parentNode.insertBefore(s,ss)})(window, document);
                    </script>
                    <!-- End of Chaport Live Chat code -->
               @endif

                <script type="text/javascript">
                    window.dgSocialWidgetData = {
                        widgetId: '062501ca-6471-4288-ad32-60fa96ddf899',
                        apiUrl: 'https://app.daily-grow.com/sw/api/v1',
                    };
                </script>
                <script type="text/javascript" src="https://app.daily-grow.com/social-widget/init.js" defer></script>
            @endif
        @endif
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
