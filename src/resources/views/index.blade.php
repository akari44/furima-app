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
        <!-- おすすめタブ（/ のとき active） -->
            @if(request('tab') === 'mylist')
                <a href="{{ url('/') }}" class="tab-title">おすすめ</a>
            @else
        <!-- それ以外（/）→ おすすめがアクティブ -->
                <a href="{{ url('/') }}" class="tab-title active">おすすめ</a>
            @endif


            <!-- マイリストタブ（/?tab=mylist のとき active） -->
            @if(request('tab') === 'mylist')
                <a href="{{ url('/?tab=mylist') }}" class="tab-title active">マイリスト</a>
            @else
                <a href="{{ url('/?tab=mylist') }}" class="tab-title">マイリスト</a>
            @endif
        </div>
        @endif
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