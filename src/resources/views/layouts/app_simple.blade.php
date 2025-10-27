<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>COACHTECHフリマ</title>

        <!--フォント設定-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

        <!--CSS設定-->
        <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app_simple.css') }}">
        <link rel="stylesheet" href="{{ asset('css/layouts/app_simple.css') }}">
        @yield('css')
        
    </head>
    <body>
        <div class="inner">
            <!--ヘッダー共通部分-->
            <header class="header">
                <div class="header__inner">
                    <div class="header__logo">
                        <img src="{{ asset('images/logo.svg') }}" alt="ロゴ">
                    </div>
                </div>
            </header>
            <!--各部分メインコンテント-->
            <main>
            @yield('content')
            </main>
        </div>

    </body>
</html>