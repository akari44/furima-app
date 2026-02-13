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
            @forelse($items as $item)
                <x-item-card :item="$item" />
            @empty
                @if($tab === 'mylist')
                    <p>いいねした商品はまだありません。</p>
                @else
                    <p>商品はありません。</p>
                @endif
            @endforelse
        </div>
</div>
</div>
@endsection