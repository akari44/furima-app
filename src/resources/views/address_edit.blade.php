@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address_edit.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="title">
        <h1 class="title__text">住所の変更</h1>
    </div>


    <div class="address-edit__wrapper">
        <form class="address" action="{{ route('address.store', ['item_id' => $purchase->item_id]) }}" method="post">
            @csrf
            <div class="shopping-address__form">
                <label for="postal_code">郵便番号</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}" />

                @error('postal_code')
                <div class="form__error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="shopping-address__form">
                <label for="address">住所</label>
                <input type="text" name="address" />

                @error('address')
                <div class="form__error">{{ $message }}</div>
                 @enderror
            </div>

            <div class="shopping-address__form">
                 <label for="building">建物名</label>
                <input type="text" name="building" />
            </div>



            <button type="submit" class="shopping-address__submit-button">更新する</button>
        </form>
        
    </div>
</div>


@endsection