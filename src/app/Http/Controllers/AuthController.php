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
        // 必要な項目だけ取得
        $form = $request->only(['name', 'email', 'password']);

        // パスワード暗号化
        $form['password'] = Hash::make($form['password']);

        // 登録
        User::create($form);

        // 登録後はログイン画面へ
        return redirect('/login');
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

            // ⭐ 初回ログイン判定：
            // postal_code がまだ null → プロフィール未設定とみなす
            if (is_null($user->postal_code)) {
                return redirect('/mypage/profile');
            }

            // ⭐ それ以外（プロフィール設定済み）は、いつものトップ（マイリストタブ）へ
            return redirect('/?tab=mylist');
        }

        // パスワード・メールどちらか間違っていた場合
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->withInput();
    }
}
