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

        <a href="{{ route('profile.show', ['page' => 'sell']) }}"
        class="mypage-tab {{ $tab === 'sell' ? 'active' : '' }}">
            出品した商品
        </a>

        <a href="{{ route('profile.show', ['page' => 'buy']) }}"
        class="mypage-tab {{ $tab === 'buy' ? 'active' : '' }}">
            購入した商品
        </a>

    </div>

    <hr>
    <!--ここから商品一覧-->
    <!--出品商品一覧-->
    @if ($tab === 'sell')
        <div class="wrapper-outer">
            <div class="item-wrapper">
                @forelse ($sellItems as $item)
                    <div class="item-groups">
                        <x-item-card :item="$item" />
                    </div>
                @empty
                    <p>出品した商品はありません。</p>
                @endforelse
            </div>
        </div> 
    @endif

    @if ($tab === 'buy')
        <div class="wrapper-outer">
                <div class="item-wrapper">
                @forelse ($buyItems as $purchase)
                    <div class="item-groups">
                        <x-item-card :item="$purchase->item" />
                    </div>
                @empty
                    <p>購入した商品はありません。</p>
                @endforelse
            </div>
        </div>
    @endif

</div>
@endsection