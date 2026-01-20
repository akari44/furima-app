@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="POST" class="purchase-form">
@csrf
    <div class="content-wrapper">

        <!--左半分-->
        <div class="wrapper-left">
            <div class="item-wrapper">
                <div class="item__image">
                    <img src="{{ $item->images->first()
                    ? asset('storage/' . $item->images->first()->image_path)
                    : asset('images/noimage.png') }}"
                    alt="{{ $item->item_name }}">
                </div>
                <div class="item__info">
                    <h3>{{$item ->item_name}}</h3>
                    <h3><span>￥</span>{{$item ->price}}</h3>
                </div>
            </div>
            <hr>

            <div class="payment-wrapper">
                <h4>支払い方法</h4>
                <div class="payment-method">
                    <select name="payment_method_id" id="payment_method_id">
                       <option value="" disabled selected data-name="">選択してください</option>

                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method->id }}"
                                data-name="{{ $method->display_name }}">
                            {{ $method->display_name }}
                        </option>
                    @endforeach
                    </select>

                </div>
            </div>
            <hr>

            <div class="address-wrapper">
                <div class="address__title">
                    <h4>配送先</h4>
                    <a href="{{ route('address.create', ['item_id' => $item->id]) }}">変更する</a>
                </div>
                <div class="address">
                    <p class="postal-code">{{$user -> postal_code}}</p>
                    <p class="address-building">{{$user -> address}} {{$user -> building_name}}</p>
                </div>
            </div>
            <hr>

        </div>

        <!--右半分-->
        <div class="wrapper-right">
            <table class="order-summary">
            
                <tr class="order-summary__price">
                    <th>商品代金</th>
                    <th>￥{{$item -> price}}</th>
                </tr>

                <tr class="order-summary__payoment">
                
                    <td>支払い方法</td>
                    <td id="selected-payment-method">未選択</td>
                </tr>

            </table>

                
                <button type="submit" class="purchase-button">
                <a href="/">購入する</a>
                </button>
            

        </div>
    </div>
</form>

<!--支払い方法を即反映させるJS-->
<script>
document.getElementById('payment_method_id')
    .addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        document.getElementById('selected-payment-method').textContent =
            selected.dataset.name;
    });
</script>

@endsection
