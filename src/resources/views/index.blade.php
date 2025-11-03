@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="content">

    <div class="tabs">
        <div class="tabs__recommended">
            <a href="" class="recommend-items">おすすめ</a>
        </div>
        <div class="tabs__favorites">
            <a href="" class="favorite-items">マイリスト</a>
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