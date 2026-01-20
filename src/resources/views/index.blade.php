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
    <!--ここから商品一覧-->
    <div class="wrapper-outer">
        <div class="item-wrapper">
            @if($tab === 'mylist')
                <p>マイリスト機能は準備中です。</p>
            @else
              
                @forelse($items as $item)
                <div class="item-groups">
                   <a href="{{ url('/item/' . $item->id) }}">
                       <div class="item-img">
                            <img src="{{ $item->images->first() ? asset('storage/' . $item->images->first()->image_path) : asset('images/noimage.png') }}" alt="{{ $item->item_name }}">
                        </div>
                   </a>
                    
                   <p class="item-title">{{ $item->item_name }}</p>
            
                </div>
                @empty
                <p>商品はありません。</p>
                @endforelse

            @endif

        
        </div>
    </div>

</div>
@endsection