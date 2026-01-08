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
            <div class="item-image__upload-box">
                <img id="imagePreview" class="image-preview" alt="" hidden>
                <!--画像選択ボタン-->
                <label for="image" class="upload-button">画像を選択する</label>
                <input type="file" id="image" name="image" accept="image/*" hidden>
            </div>

            <!--バリデーション-->
            @error('image')
            <div class="create__error">{{$message}}</div>
            @enderror
              
        </div>

        <!--商品の詳細-->
        <div class="item-detail">
            <h3 class="item-detail-title">商品の詳細</h3>
            <hr>
            <label class="item-category__title" >カテゴリー</label>
            <div class="category-buttons">
                <button type="button" class="category-btn"  data-value="ファッション">ファッション</button>
                <button type="button" class="category-btn"  data-value="家電">家電</button>
                <button type="button" class="category-btn"  data-value="インテリア">インテリア</button>
                <button type="button" class="category-btn"  data-value="レディース">レディース</button>
                <button type="button" class="category-btn"  data-value="メンズ">メンズ</button>
                <button type="button" class="category-btn"  data-value="コスメ">コスメ</button>
                <button type="button" class="category-btn"  data-value="本">本</button>
                <button type="button" class="category-btn"  data-value="ゲーム">ゲーム</button>
                <button type="button" class="category-btn"  data-value="スポーツ">スポーツ</button>
                <button type="button" class="category-btn"  data-value="キッチン">キッチン</button>
                <button type="button" class="category-btn"  data-value="ハンドメイド">ハンドメイド</button>
                <button type="button" class="category-btn"  data-value="アクセサリー">アクセサリー</button>
                <button type="button" class="category-btn"  data-value="おもちゃ">おもちゃ</button>
                <button type="button" class="category-btn" data-value="ベビー・キッズ">ベビー・キッズ</button>
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
                <option value="" disabled {{ old('condition') ? '' : 'selected' }}>選択してください</option>

                <option value="good" {{ old('condition') === 'good' ? 'selected' : '' }}>良好</option>

                <option value="no_visible_damage" {{ old('condition') === 'no_visible_damage' ? 'selected' : '' }}>
                    目立った傷や汚れなし
                </option>

                <option value="some_damage" {{ old('condition') === 'some_damage' ? 'selected' : '' }}>
                    やや傷や汚れあり
                </option>

                <option value="bad" {{ old('condition') === 'bad' ? 'selected' : '' }}>
                    状態が悪い
                </option>
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
                <input name="item_name" type="text" value="{{ old('item_name') }}">
                <!--バリデーション-->
                @error('item_name')
                <div class="create__error">{{$message}}</div>
                @enderror
            </div>

            <div class="item-descriptions__group">
                <label class="item-descriptions__title">ブランド名</label>
                <input name="brand" type="text" value="{{ old('brand') }}">
            </div>

            <div class="item-descriptions__group">
                <label class="item-descriptions__title">商品の説明</label>
                <textarea name="description">{{ old('description') }}</textarea>
                <!--バリデーション-->
                @error('description')
                <div class="create__error">{{$message}}</div>
                @enderror
            </div>

            <div class="item-descriptions__group">
                <label class="item-descriptions__title">販売価格</label>
                <div class="price-input">
                    <input  type="text" name="price" id="price" value="{{ old('price')}}">
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

    @section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {

  // 画像を選んだら、#imagePreview に表示
  const imageInput = document.getElementById('image');
  const imagePreview = document.getElementById('imagePreview');

  if (imageInput && imagePreview) {
    imageInput.addEventListener('change', (event) => {
      const file = event.target.files[0];
      if (!file) return;

      imagePreview.src = URL.createObjectURL(file);
      imagePreview.hidden = false;
    });
  }

  
  // カテゴリ複数選択（色が変わる + hiddenへ保存）
 
  const categoryButtons = document.querySelectorAll('.category-btn');
  const hiddenCategory = document.getElementById('selected-category');

  let selectedCategories = [];

  categoryButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      const value = btn.dataset.value;
      if (!value) return;

      // すでに選ばれてたら外す
      if (selectedCategories.includes(value)) {
        selectedCategories = selectedCategories.filter(v => v !== value);
        btn.classList.remove('selected');
      } else {
        selectedCategories.push(value);
        btn.classList.add('selected');
      }

      // hidden に JSON で保存
      if (hiddenCategory) {
        hiddenCategory.value = JSON.stringify(selectedCategories);
      }
    });
  });

});
    </script>
    @endsection

