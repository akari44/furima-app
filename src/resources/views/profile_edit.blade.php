@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile_edit.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="title">
        <h1 class="title__text">プロフィール設定</h1>
    </div>

    <form class="edit" action="/mypage/profile" method="post" enctype="multipart/form-data">
     @csrf
<!-- プロフ画像エリア -->
        <div class="icon-area">
           <div class="profile-icon">
               <img id="preview"
                src="{{ empty($user->avatar_path) ? asset('images/default.png') : asset('storage/' . $user->avatar_path) }}"
                alt="プロフィール画像">

            </div>

            <!-- input を隠す -->
            <input type="file" id="avatar" name="avatar" accept="image/*" style="display:none;">

            <!-- 自作ボタン -->
            <label for="avatar" class="icon-select">画像を選択する</label>
        </div>

<!-- プロフ入力・送信エリア -->
        <div class="edit-wrapper">


            <!-- プロフ入力・送信エリア -->
            
                <div class="profile__form">
                    <label for="name">ユーザー名</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" />

                @error('name')
                <div class="form__error">{{ $message }}</div>
                @enderror

                </div>
                
                <div class="profile__form">
                    <label for="postal_code">郵便番号</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" />

                @error('postal_code')
                <div class="form__error">{{ $message }}</div>
                @enderror

                </div>
                
                <div class="profile__form">
                    <label for="address">住所</label>
                    <input type="text" name="address" value="{{ old('address', $user->address) }}" />

                @error('address')
                <div class="form__error">{{ $message }}</div>
                @enderror

                </div>
                
                <div class="profile__form">
                    <label for="building">建物名</label>
                    <input type="text" name="building_name" value="{{ old('building_name', $user->building_name) }}" />
                </div>
            

                <button type="submit" class="edit__submit-button">更新する</button>

            
        </div>
    </form>
</div>

<!--プロフ画像プレビュー機能(js)-->
<script>
document.getElementById('avatar').addEventListener('change', function(e) {
        const file=e.target.files[0];
        if ( !file) return;

        const reader=new FileReader();

        reader.onload=function(e) {
            document.getElementById('preview').src=e.target.result;
        }

        ;
        reader.readAsDataURL(file);
    });
</script>

@endsection