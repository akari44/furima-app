<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    //会員登録 ページ表示
    public function register(){
        return view ('auth.register');
    }
    //会員登録 バリデーション確認
    public function storeUser(RegisterRequest $request){
        $form = $request -> all();
        return redirect('/register');

    }

    //ログイン ページ表示
    public function login(){
        return view ('auth.login');
    }
    //ログイン バリデーション確認
    public function loginUser(LoginRequest $request){
        $form = $request -> all();
        return redirect('/login');

    }
}
