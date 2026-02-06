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
                    <x-item-image :item="$item" />
                </div>
                <div class="item__info">
                    <h3><x-item-title :item="$item" /></h3>
                    <h3><span>￥</span>{{$item ->price}}</h3>
                </div>
            </div>
            <hr>

            <div class="payment-wrapper">
                <h4>支払い方法</h4>
                <div class="payment-method">
                   <select name="payment_method_id" id="payment_method_id">
                    <option value="" disabled {{ old('payment_method_id', $purchase->payment_method_id) ? '' : 'selected' }}>
                        選択してください
                    </option>

                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}"
                        {{ (string)old('payment_method_id', $purchase->payment_method_id) === (string)$method->id ? 'selected' : '' }}>
                        {{ $method->display_name }}
                        </option>
                    @endforeach
                    </select>

                    @error('payment_method_id')
                    <div class="form__error">{{ $message }}</div>
                    @enderror
                    
                </div>
            </div>
            <hr>

            <div class="address-wrapper">
                <div class="address__title">
                    <h4>配送先</h4>
                    <a href="{{ route('address.create', ['item_id' => $item->id]) }}">変更する</a>
                </div>
                <div class="address">
                    <p class="postal-code">{{ $purchase->postal_code }}</p>
                    <p class="address-building">{{ $purchase->address }} {{ $purchase->building_name }}</p>
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
                @if($item->status === 'sold')
                <button disabled class="purchase-button">売り切れ</button>
                @else
               <button type="submit" class="purchase-button">
                購入する
                @endif
                </button>

        </div>
    </div>
</form>

<!--支払い方法を即反映させるJS-->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const select = document.getElementById('payment_method_id');
  const display = document.getElementById('selected-payment-method');
  if (!select || !display) return;

  const reflect = () => {
    const text = select.options[select.selectedIndex]?.textContent?.trim() ?? '未選択';
    display.textContent = text;
  };

  select.addEventListener('change', reflect);
  reflect();
});
</script>


@endsection
