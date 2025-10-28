@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile_edit.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="title">
        <h1 class="title__text">プロフィール設定</h1>
    </div>

<!-- プロフ画像エリア -->
        <div class="icon-area">
            <div class="profile-icon">
                <span>SRC</span>
            </div>
            <button class="icon-select">
                画像を選択する
            </button>
        </div>

<!-- プロフ入力・送信エリア -->
    <div class="edit-wrapper">


        <!-- プロフ入力・送信エリア -->
        <form class="edit" action="/mypage/profile" method="post">
            @csrf
            <div class="profile__form">
                <label for="name">ユーザー名</label>
                <input type="text" name="name" value="{{ old('name') }}" />

            @error('name')
            <div class="form__error">{{ $message }}</div>
            @enderror

            </div>
            
            <div class="profile__form">
                <label for="postal_code">郵便番号</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}" />

            @error('postal_code')
            <div class="form__error">{{ $message }}</div>
            @enderror

            </div>
            
            <div class="profile__form">
                <label for="address">住所</label>
                <input type="text" name="address" />

            @error('address')
            <div class="form__error">{{ $message }}</div>
            @enderror

            </div>
            
            <div class="profile__form">
                <label for="building">建物名</label>
                <input type="text" name="building" />
            </div>
        

            <button type="submit" class="edit__submit-button">更新する</button>
        </form>
        
    </div>
</div>

@endsection