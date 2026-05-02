<?php

namespace App\Http\Controllers;

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
        $form = $request->only(['name', 'email', 'password']);
        $form['password'] = Hash::make($form['password']);
        $user = User::create($form);
        
        //メール認証
        $user->sendEmailVerificationNotification();
        Auth::login($user);
        return redirect()->route('verification.notice');
    }
    
    // ログイン ページ表示
    public function login()
    {
        return view('auth.login');
    }

    //  ログイン処理
    public function loginUser(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

             // メール未認証の場合は認証案内画面へ
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            // プロフィール未登録の場合はプロフィール設定画面へ
            if (is_null($user->postal_code)) {
                return redirect()->route('profile.edit');
            }

            // ログイン後はマイリストへ遷移
            return redirect('/?tab=mylist');
        }

        // パスワード・メールどちらか間違っていた場合
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->withInput();
    }

    //ログアウト処理
    public function logoutUser(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
