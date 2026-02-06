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
        <link rel="stylesheet" href="{{ asset('css/layouts/app.css') }}">
        @yield('css')
    
    </head>

    @yield('scripts')

    
    <body>
        <div class="inner">
            <!--ヘッダー共通部分-->
            <header class="header">
                <div class="header__nav">
                    <div class="header__logo">
                        <a href="/" class="header-auth__top"><img src="{{ asset('images/logo.svg') }}" alt="ロゴ"></a>
                    </div>
                    <!--ログイン認証後のみ表示部分-->
                    @if (Auth::check())
                    <div class="header-auth__search">
                            <form action="" method="post">
                            @csrf
                            <input class="header-auth__search__window"/>
                            </form>
                    </div>
                    <ul class="header-auth">
                        <li class="header-auth__logout">
                            <form action="/logout" method="post">
                            @csrf
                            <button class="header-auth__logout__button">ログアウト</button>
                            </form>
                        </li>
                        <li class="header-auth__mypage">
                        <a class="header-auth__mypage__link" href="/mypage">マイページ</a>
                        </li>
                        <li class="header-auth__sell">
                        <a class="header-auth__sell__link" href="/sell">出品</a>
                        </li>
                    </ul>
                    @endif
                    <!--ここまで-->
                </div>
            </header>
           
            <!--withメッセージ表示-->
            @if (session('success'))
            <div class="flash-message flash-message--success" id="flash-message">
            {{ session('success') }}
            </div>
            @endif

            @if (session('success'))
            <script>
                setTimeout(() => {
                    const flash = document.querySelector('.flash-message');
                    if (flash) {
                        flash.style.opacity = '0';
                        flash.style.transition = 'opacity 0.5s';
                    }
                }, 3000);
            </script>
            @endif
            
            <!--各部分メインコンテント-->
            <main>
            @yield('content')
            </main>
        </div>



    </body>
</html>