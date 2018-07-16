<!DOCTYPE html>
<html lang="ru-RU">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <meta name="description" content="Page description. No longer than 155 characters.">
    <meta name="keywords" content="">
    <meta name="robots" content="INDEX,FOLLOW">
    <link rel="icon" href="{{ asset('trade-admin/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="{{ asset('trade-admin/css/styles.css') }}">
</head>
<body>
<div class="wrapper">
    <div class="maincontent">
        <main>
            <header class="ui inverted huge borderless fixed fluid menu"><a class="header item">Trade</a>
                <div class="right menu">
                    <!--.item-->
                    <!--    .ui.small.input-->
                    <!--        input(placeholder='Search...')-->
                    <a href="/logout" class="item">Exit</a>
                </div>
            </header>
            <div class="ui grid">
                <div class="row">
                    <div class="column" id="sidebar">
                        <div class="ui secondary vertical fluid menu">
                            <a class="item" href="{{ route('email') }}">Настройки Email</a>
                            <a class="item" href="{{ route('payment') }}">Платежные системы</a>
                            <a class="item" href="{{ route('plan.index') }}">Торговые планы</a>
                            <a class="item" href="{{ route('faq.index') }}">FAQ Менеджер</a>
                            <a class="item" href="{{ route('referral') }}">Реферальные награды</a>
                            <div class="ui hidden divider"></div>
                            <a class="item" href="{{ route('users') }}">Пользователи</a>
                            <a class="item" href="{{ route('statistics') }}">Статистика</a>
                            <!--a class="item" href="{{ route('stat') }}">Статистика</a-->
                            <a class="item" href="{{ route('queries') }}">Запросы на вывод</a>
                        </div>
                    </div>
                    <div class="column" id="content">
                        <div class="ui grid">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <footer>Trade</footer>
</div>
<script defer="defer" src="{{ asset('trade-admin/js/vendor/vendor.js') }}"></script>
<script defer="defer" src="{{ asset('trade-admin/js/script.js') }}"></script>
</body>
</html>