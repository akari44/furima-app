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
                 <img src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : asset('images/default.png') }}"
         alt="プロフィール画像">
            </div>
            
            <div class="username-area">
                <p class="username">{{$user -> name}}</p>
            </div>
        </div>

        <a class="profile-edit" href="mypage/profile">
            プロフィールを編集
        </a>
    </div>
    <div class="tabs">

        <a href="{{ url('/mypage?page=sell') }}"
        class="mypage-tab {{ $tab === 'sell' ? 'active' : '' }}">
            出品した商品
        </a>

        <a href="{{ url('/mypage?page=buy') }}"
        class="mypage-tab {{ $tab === 'buy' ? 'active' : '' }}">
            購入した商品
        </a>

    </div>

    <hr>
    <!--ここから商品一覧-->
    <!--出品商品一覧-->
    @if ($tab === 'sell')
        <div class="item-wrapper">
            @forelse ($sellItems as $item)
                <div class="item-groups">

                    <!-- 商品画像（複数のうち1枚目を表示） -->
                    <div class="item-img">
                        <img src="{{ $item->images->first() 
                            ? asset('storage/' . $item->images->first()->image_path) 
                            : asset('images/noimage.png') }}"
                            alt="{{ $item->name }}">
                    </div>

                    <!-- 商品名 -->
                    <p class="item-title">{{ $item->name }}</p>

                </div>
            @empty
                <p>出品した商品はありません。</p>
            @endforelse
        </div>
    @endif

    @if ($tab === 'buy')
        <div class="item-wrapper">
            @forelse ($buyItems as $purchase)
                <div class="item-groups">

                    <!-- 購入した商品の画像（最初の1枚） -->
                    <div class="item-img">
                        <img src="{{ $purchase->item->images->first()
                            ? asset('storage/' . $purchase->item->images->first()->image_path)
                            : asset('images/noimage.png') }}"
                            alt="{{ $purchase->item->name }}">
                    </div>

                    <!-- 商品名 -->
                    <p class="item-title">{{ $purchase->item->name }}</p>

                </div>
            @empty
                <p>購入した商品はありません。</p>
            @endforelse
        </div>
    @endif

</div>
@endsection