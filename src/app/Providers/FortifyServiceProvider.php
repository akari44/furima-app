<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 会員登録画面
        Fortify::registerView(fn () => view('auth.register'));

        // ログイン画面
        Fortify::loginView(fn () => view('auth.login'));

        // ログイン回数制限（教材どおり）
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });

        // ⭐ ここには何も追加しなくてOK！！
        // ログイン後のリダイレクトは FortifyServiceProvider ではなく、
        // config/fortify.php の 'home' で制御する方式に変更したため。
    }
}
