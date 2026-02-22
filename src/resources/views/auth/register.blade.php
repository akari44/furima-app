@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="title">
        <h1 class="title__text">会員登録</h1>
    </div>
    <div class="register-wrapper">
        <form class="register" action="{{ route('register.store') }}" method="post">
            @csrf
            <div class="register__form">
                <label for="name">ユーザー名</label>
                <input type="text" name="name" value="{{ old('name') }}" />

            @error('name')
            <div class="form__error">{{ $message }}</div>
            @enderror

            </div>
            
            <div class="register__form">
                <label for="email">メールアドレス</label>
                <input type="text" name="email" value="{{ old('email') }}" />

                @error('email')
            <div class="form__error">{{ $message }}</div>
            @enderror

            </div>
            
            <div class="register__form">
                <label for="password">パスワード</label>
                <input type="password" name="password" />

                @error('password')
            <div class="form__error">{{ $message }}</div>
            @enderror

            </div>
           
            <div class="register__form">
                <label for="password_confirmation">確認用パスワード</label>
                <input type="password" name="password_confirmation" />

                @error('password_confirmation')
            <div class="form__error">{{ $message }}</div>
            @enderror
            </div>
        

            <button type="submit" class="register__submit-button">登録する</button>
        </form>
        <div class="info-login">
            <a href="{{ route('login') }}">ログインはこちら</a>
        </div>
    </div>
</div>

@endsection