@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item_detail.css') }}">
@endsection

@section('content')
<div class="content-wrapper">
<!--左側（商品画像）-->
    <div class="wrapper-left">
        <div class="item-image">   
            <x-item-image :item="$item" />
        </div>
    </div>

<!--右側（商品詳細）-->
    <div class="wrapper-right">
        <!--購入エリア(クエリパラメータを送る）-->
        <div class="detail__name">
            <x-item-title :item="$item"/>
        </div>
        <div class="detail__brand">
            <p>{{ $item->brand}}</p>
        </div>
        <div class="detail__price">
            <p class="item-price">￥<span>{{number_format($item->price) }}</span>（税込）</p>

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
            @if($item->status === 'sold')
            <a href="/">この商品は売り切れです</a>
            @else
            <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}">購入手続きへ</a>
            @endif
        </div>

        <!--商品の情報-->
        <h5>商品説明</h5>
        <div class="detail__item-description">
            <p>{{ $item->description}}</p>
        </div>
        <h5>商品の情報</h5>
        <div class="detail__item-info">
            <div class="categories__wrapper">
                <p class="info-text">カテゴリー</p>
                <div class="item-categories">
                         @foreach ($item->categories as $category)
                        <p class="category-name">{{ $category->category_name }}</p>
                        @endforeach
                        
                </div>
            </div>
            <div class="conditions__wrapper">
                <p class="info-text">商品の状態</p> 
                <div class="item-condition">{{ $item->condition_label}}</div>
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
                <button disabled>コメントを送信する</button>
            </div>
        </form>
    </div>
</div>
@endsection