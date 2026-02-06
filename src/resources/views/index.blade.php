@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="content">

    <div class="tabs">
     <!--未ログイン--> 
        @if(!Auth::check())
        <div class="tabs__recommended">  
            <a href="{{ url('/') }}" class="tab-title active">おすすめ</a>
        </div>
        @endif

     <!--ログイン後-->   
         @if(Auth::check())
        <div class="tabs__likes"> 
            <a href="{{ url('/') }}"
            class="tab-title {{ $tab !== 'mylist' ? 'active' : '' }}">
            おすすめ</a>

            <a href="{{ url('/?tab=mylist') }}"
            class="tab-title {{ $tab === 'mylist' ? 'active' : '' }}">
            マイリスト</a>
        </div>
        @endif

    </div>

    <hr>
   <!-- 商品一覧 -->
    <div class="wrapper-outer">
        <div class="item-wrapper">
            @if($tab === 'mylist')
                <p>マイリスト機能は準備中です。</p>
                @else
                @forelse($items as $item)
                    <x-item-card :item="$item" />
                @empty
                    <p>商品はありません。</p>
                @endforelse
            @endif
        </div>
</div>
</div>
@endsection