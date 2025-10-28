@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="title">
        <h1 class="title__text">ログイン</h1>
    </div>
    <div class="login-wrapper">
        <form class="login" action="/login" method="post">
            @csrf
            <div class="login__form">
                <label for="email">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}" />

                @error('email')
            <div class="form__error">{{ $message }}</div>
            @enderror

            </div>
            
            <div class="login__form">
                <label for="password">パスワード</label>
                <input type="password" name="password" />

                @error('password')
            <div class="form__error">{{ $message }}</div>
            @enderror

            </div>

            <button type="submit" class="login__submit-button">ログインする</button>
        </form>
        <div class="info-login">
            <a href="/register">会員登録はこちら</a>
        </div>
    </div>
</div>

@endsection