<!DOCTYPE html>
<html lang="en">
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
        @yield('css')
        
    </head>
    <body>
        <!--ヘッダー共通部分-->
        <header class="header">
            <div class="header__inner">
                <div class="header__logo">
                    <link rel="stylesheet" href="{{asset('images/logo.svg')}}" alt="ロゴ">
                </div>
                <!--ヘッダー検索窓-->
                <div class="header__search-window">
                    <form action="">
                        <input class="search-window"type="text" name="" id="">
                    </form>
                </div>
                <!--ヘッダーボタングループ-->
                <div class="header__button-group">
                    <div class="header__logout-button">
                        <a href="">ログアウト</a>
                    </div>
                    <div class="header__mypage-button">
                        <a href="">マイページ</a>
                    </div>
                    <div class="header__sell-button">
                        <a href="">出品</a>
                    </div>
                </div>
            </div>
        </header>

        <!--各部分メインコンテント-->
        <main>
        @yield('content')
        </main> 

    </body>
</html>