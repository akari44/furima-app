@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item_create.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="title">
        <h1 class="title__text">商品の出品</h1>
    </div>
    <form class="create-wrapper" action="/sell" method="POST" enctype="multipart/form-data">
    @csrf
        <!--商品画像-->
        <div class="item-image">
            <label for="image" class="item-image__title">商品画像</label>
            <div class="item-image__upload-box">
                <!--画像選択ボタン-->
                <label for="images" class="upload-button">画像を選択する</label>
                <input type="file" id="image" name="image" accept="image/*" hidden>

                <!--バリデーション-->
                @error('image')
                <div class="create__error">{{$message}}</div>
                @enderror
            </div>
        </div>

        <!--商品の詳細-->
        <div class="item-detail">
            <h3 class="item-detail-title">商品の詳細</h3>
            <hr>
            <label class="item-category__title" >カテゴリー</label>
            <div class="category-buttons">
                <button type="button" class="category-btn"  data-value="ファッション">ファッション</button>
                <button type="button" class="category-btn"  data-value="">家電</button>
                <button type="button" class="category-btn"  data-value="">インテリア</button>
                <button type="button" class="category-btn"  data-value="">レディース</button>
                <button type="button" class="category-btn"  data-value="">メンズ</button>
                <button type="button" class="category-btn"  data-value="">コスメ</button>
                <button type="button" class="category-btn"  data-value="">本</button>
                <button type="button" class="category-btn"  data-value="">ゲーム</button>
                <button type="button" class="category-btn"  data-value="">スポーツ</button>
                <button type="button" class="category-btn"  data-value="">キッチン</button>
                <button type="button" class="category-btn"  data-value="">ハンドメイド</button>
                <button type="button" class="category-btn"  data-value="">アクセサリー</button>
                <button type="button" class="category-btn"  data-value="">おもちゃ</button>
                <button type="button" class="category-btn" data-value="">ベビー・キッズ</button>
            </div>
             <!--後でここ色が変わるようにする-->
            <!-- あとでここに選択された値が入る（サーバーに送信される） -->
          <input type="hidden" name="category" id="selected-category"> 

            <!--バリデーション-->
                @error('category')
                <div class="create__error">{{$message}}</div>
                @enderror

            <label class="item-conditions__title">商品の状態</label>
            <select name="condition" id="" >
                <option value="" selected disabled>選択してください</option>
                <option value="good">良好</option>
                <option value="no_visible_damage">目立った傷や汚れなし</option>
                <option value="some_damage">やや傷や汚れあり</option>
                <option value="bad">状態が悪い</option>
            </select>
            <!--バリデーション-->
                @error('condition')
                <div class="create__error">{{$message}}</div>
                @enderror
        </div>

        <!--商品名と説明-->
        <div class="item-descriptions">
            <h3 class="item-description__title">商品名と説明</h3>
            <hr>
            <div class="item-descriptions__group">
                <label class="item-name__title">商品名</label>
                <input name="name" type="text" action="/sell" method="post" value="{{ old('name') }}">
                <!--バリデーション-->
                @error('name')
                <div class="create__error">{{$message}}</div>
                @enderror
            </div>

            <div class="item-descriptions__group">
                <label class="item-descriptions__title">ブランド名</label>
                <input name="brand" type="text" action="/sell" method="post" value="{{ old('brand') }}">
            </div>

            <div class="item-descriptions__group">
                <label class="item-descriptions__title">商品の説明</label>
                <textarea name="description" action="/sell" method="post" value="{{ old('description') }}"></textarea>
                <!--バリデーション-->
                @error('description')
                <div class="create__error">{{$message}}</div>
                @enderror
            </div>

            <div class="item-descriptions__group">
                <label class="item-descriptions__title">販売価格</label>
                <div class="price-input">
                    <input  type="text" name="price" id="price" action="/sell"  method="post" value="{{ old('price')}}">
                </div>
                <!--バリデーション-->
                @error('price')
                <div class="create__error">{{$message}}</div>
                @enderror
            </div>
        </div>

        <!--出品ボタン-->
        <button class="sell-button">出品する</button>

    </form>



</div>
@endsection
