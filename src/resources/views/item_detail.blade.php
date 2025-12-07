@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item_detail.css') }}">
@endsection

@section('content')
<div class="content-wrapper">
<!--左側（商品画像）-->
    <div class="wrapper-left">
        <div class="item-image">
            <img src="" alt="">
            <p>商品画像</p>
        </div>
    </div>
<!--右側（商品詳細）-->
    <div class="wrapper-right">
        <!--購入エリア(クエリパラメータを送る）-->
        <div class="detail__name">
            <h4>商品名ドルitem->item_name</h4>
        </div>
        <div class="detail__brand">
            <p>ブランド名ドルitem->brand</p>
        </div>
        <div class="detail__price">
            <p class="item-price">￥<span>{{ number_format(どるitem->price) }}</span>（税込）</p>

        </div>    

    
        <!--いいねとコメントマーク-->
        <div class="detail__marks-wrapper">
            <div class="likes">
                <img src="{{asset ('images/likes.png')}}" alt="イイね">
                <p>3</p>
            </div>
            <div class="comments">
                <img src="{{asset ('images/comments.png')}}" alt="コメント">
                <p>1</p>
            </div>
        </div> 


        <div class="detail__buy-button">
            <a href="">購入手続きへ</a>
        </div>

        <!--商品の情報-->
        <h5>商品説明</h5>
        <div class="detail__item-description">
            <p>カラーとか、新品とかドルitem->description</p>
        </div>
        <h5>商品の情報</h5>
        <div class="detail__item-info">
            <div class="categories__wrapper">
                <p class="info-text">カテゴリー</p>
                <div class="item-categories">
                        <p class="category-name">洋服</p>
                        <p class="category-name">洋服</p>
                        <p class="category-name">洋服</p>
                </div>
            </div>
            <div class="conditions__wrapper">
                <p class="info-text">商品の状態</p> 
                <div class="item-condition">良好とかどるitem->condition</div>
            </div>
        </div>
        <!--商品へのコメントフォーム-->
        <h5>コメント（あとで数字はいるように）</h5>
        <form action="/comments" method="POST">
            @csrf
            <div class="seller-profile">
                <img class="seller-icon" src="" alt="">
                <p class="seller-name">出品者の名前</p>
            </div>
            <div class="other-comments">
                <p>ほかにコメントがあればここに入る</p>
            </div>
            <div class="create-comment">
                <label for="">商品へのコメント</label>
                <textarea name="comment"></textarea>
            </div>
            <div class="detail__comment-button">
                <button>コメントを送信する</button>
            </div>
        </form>
    </div>
</div>
@endsection