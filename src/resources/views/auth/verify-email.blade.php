@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email">
    <h2>メール認証のお願い</h2>

    <p>
        登録したメールアドレスに認証メールを送信しました。<br>
        「認証はこちらから」ボタンを押してメールアプリを開き、<br>
        <span>届いた認証メール内のリンクをクリック</span>してください。
    </p>

    
    <div class="verify-email__actions">
        <a href="mailto:" target="_blank" class="verify-email__button">
            認証はこちらから
        </a>

        <p>※メールアプリが開かない場合は、
            <br>ご自身でメールアプリを起動して、<br>
            認証メールを確認してください。
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="verify-email__again">
                認証メールを再送する
            </button>
        </form>
    </div>

    @if (session('message'))
        <div class="verify-email__message">
            {{ session('message') }}
        </div>
    @endif
</div>
@endsection