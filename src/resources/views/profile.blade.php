@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="content">

    <!-- プロフ画像エリア -->
    <div class="icon-area">
        <div class="icon-wrapper">
            <div class="profile-icon">
                <span>SRC</span>
            </div>
            
            <div class="username-area">
                <p class="username">ユーザー名</p>
            </div>
        </div>

        <button class="profile-edit">
            プロフィールを編集
        </button>
    </div>
    <div class="tabs">
        <div class="tabs__sell">
            <a href="" class="item-sell">出品した商品</a>
        </div>
        <div class="tabs__buy">
            <a href="" class="item-buy">購入した商品</a>
        </div>
    </div>

    <hr>
    <!--ここから商品一覧-->
    <div class="item-wrapper">
        <!--あとでforeachで回す-->
        <div class="item-groups">

            <!--あとでdivを　img　に変えて回す-->
            <div class="item-img">商品画像</div>
            <p class="item-title">商品名aaaaaaaaaaaaaaaaaaaaaaaaa</p>
        </div>
        <!--以下動作確認のため複数入力あとで消す-->
        <div class="item-groups">

            <!--あとでdivを　img　に変えて回す-->
            <div class="item-img">商品画像</div>
            <p class="item-title">商品名</p>
        </div>
        <div class="item-groups">

            <!--あとでdivを　img　に変えて回す-->
            <div class="item-img">商品画像</div>
            <p class="item-title">商品名</p>
        </div>
        <div class="item-groups">

            <!--あとでdivを　img　に変えて回す-->
            <div class="item-img">商品画像</div>
            <p class="item-title">商品名</p>
        </div>
        <div class="item-groups">

            <!--あとでdivを　img　に変えて回す-->
            <div class="item-img">商品画像</div>
            <p class="item-title">商品名</p>
        </div>
        <div class="item-groups">

            <!--あとでdivを　img　に変えて回す-->
            <div class="item-img">商品画像</div>
            <p class="item-title">商品名</p>
        </div>
        <div class="item-groups">

            <!--あとでdivを　img　に変えて回す-->
            <div class="item-img">商品画像</div>
            <p class="item-title">商品名</p>
        </div>
        <div class="item-groups">

            <!--あとでdivを　img　に変えて回す-->
            <div class="item-img">商品画像</div>
            <p class="item-title">商品名</p>
        </div>


    </div>
</div>
@endsection