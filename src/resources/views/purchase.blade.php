@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form action="{{ route('purchase.store') }}" method="POST" class="purchase-form">
@csrf
    <div class="content-wrapper">

        <!-- ここに hidden を置くのが王道！
        -->

        <!--左半分-->
        <div class="wrapper-left">
            <div class="item-wrapper">
                <div class="item__image">
                </div>
                <div class="item__info">
                    <h3>商品名aaaaaaaaaaa</h3>
                    <h3><span>￥</span>値段</h3>
                </div>
            </div>
            <hr>

            <div class="payment-wrapper">
                <h4>支払い方法</h4>
                <div class="payment-method">
                    <select name="payment__method" required>
                        <option value="">選択してください</option>
                        <option value="1">コンビニ払い</option>
                        <option value="2">カード払い</option>
                    </select>
                </div>
            </div>
            <hr>

            <div class="address-wrapper">
                <div class="address__title">
                    <h4>配送先</h4>
                    <a href="/purchase/address/">変更する</a>
                </div>
                <div class="address">
                    <p class="postal-code">郵便番号</p>
                    <p class="address-building">住所と建物名</p>
                </div>
            </div>
            <hr>

        </div>

        <!--右半分-->
        <div class="wrapper-right">
            <table class="order-summary">
            
                <tr class="order-summary__price">
                    <th>商品代金</th>
                    <th>￥ここに代金はいる</th>
                </tr>

                <tr class="order-summary__payoment">
                
                    <td>支払い方法</td>
                    <td>ここに方法はいる</td>
                </tr>

            </table>

                
                <button type="submit" class="purchase-button">
                購入する
                </button>
            

        </div>
    </div>
</form>
@endsection
