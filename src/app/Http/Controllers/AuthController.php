<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 会員登録 ページ表示
    public function register()
    {
        return view('auth.register');
    }

    // 会員登録 登録、バリデーション、リダイレクト
    public function storeUser(RegisterRequest $request)
    {
        // 必要な項目だけ取得
        $form = $request->only(['name', 'email', 'password']);

        // パスワード暗号化
        $form['password'] = Hash::make($form['password']);

        // 登録
        $user = User::create($form);
        // メール認証
        $user->sendEmailVerificationNotification();
        Auth::login($user);
        

        // 登録後はメール認証案内画面へ
        return redirect()->route('verification.notice');
    }
    
    // ログイン ページ表示
    public function login()
    {
        return view('auth.login');
    }

    // 🔥 ログイン処理
    public function loginUser(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

             // ①メール未認証なら誘導画面へ
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            // ②プロフィール未登録ならプロフ設定画面へ
            if (is_null($user->postal_code)) {
                return redirect()->route('profile.edit');
            }

            // ③①と②をクリアした通常user
            return redirect('/?tab=mylist');
        }

        // パスワード・メールどちらか間違っていた場合
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->withInput();
    }
}
